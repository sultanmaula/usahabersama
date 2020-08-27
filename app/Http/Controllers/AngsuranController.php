<?php

namespace App\Http\Controllers;

use App\Angsuran;
use App\MarginKeuntungan;
use App\Transaksi;
use DB;
use Illuminate\Http\Request;

class AngsuranController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:administrator');
    }
    function list() {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        return view('angsuran.index', $data);
    }

    public static function list_transaksi($id_transaksi)
    {
        $transaksi = DB::table('transaksis')
            ->leftJoin('nasabahs', 'id_nasabah', 'nasabahs.id')
            ->leftJoin('kelompoks', 'id_kelompok', 'kelompoks.id')
            ->select('transaksis.*', 'nama', 'nama_kelompok')
            ->whereNull('transaksis.deleted_at')
            ->where('transaksis.id', $id_transaksi)
            ->first();

        $transaksi->jatuh_tempo        = date('d-m-Y', strtotime(date("Y-m-d", strtotime($transaksi->tanggal)) . " + 2 year"));
        $prosentase                    = MarginKeuntungan::pluck('prosentase')->first();
        $transaksi->laba               = ($transaksi->harga_produk * $prosentase) / 100;
        $transaksi->laba               = 'Rp. ' . number_format($transaksi->laba, 0, ",", ".");
        $transaksi->harga_produk       = 'Rp. ' . number_format($transaksi->harga_produk, 0, ",", ".");
        $transaksi->angsuran_pokok     = 'Rp. ' . number_format($transaksi->angsuran_pokok, 0, ",", ".");
        $transaksi->angsuran_bagihasil = 'Rp. ' . number_format($transaksi->angsuran_bagihasil, 0, ",", ".");
        $transaksi->jumlah_cicilan     = 'Rp. ' . number_format($transaksi->jumlah_cicilan, 0, ",", ".");
        $transaksi->hargaPlusLaba      = 'Rp. ' . number_format($transaksi->jumlah_pinjaman_pokok + $transaksi->jumlah_pinjaman_laba, 0, ",", ".");
        $transaksi->jumlah_pinjaman_pokok     = 'Rp. ' . number_format($transaksi->jumlah_pinjaman_pokok, 0, ",", ".");
        $transaksi->jumlah_pinjaman_laba     = 'Rp. ' . number_format($transaksi->jumlah_pinjaman_laba, 0, ",", ".");
        $transaksi->status         = ($transaksi->status == 1) ? 'LUNAS' : 'BELUM LUNAS';

        return $transaksi;
    }

    public function get_list()
    {
        $data_transaksi = DB::table('transaksis')->get();

        $array_angsuran = [];
        foreach ($data_transaksi as $transaksi) {
            $data_angsurans = DB::table('angsurans')
                                    ->where('id_transaksi', $transaksi->id)
                                    ->orderBy('cicilan_ke', 'desc')
                                    ->limit(1)
                                    ->get();

            if ($data_angsurans->isNotEmpty()) {
                $array_angsuran[] = $data_angsurans;
            }
        }

        return datatables()->of($array_angsuran)
            ->addColumn('status', function ($data) {
                return ($data[0]->status == 1) ? 'Lunas' : 'Belum Lunas';
            })
            ->addColumn('transaksi', function ($data) {
                $trans     = self::list_transaksi($data[0]->id_transaksi);
                $transaksi = $trans->nama . ' - ' . $trans->nama_kelompok . ' - ' . $trans->nama_produk;
                return $transaksi;
            })->addColumn('cicilan_ke', function ($data) {
                return $data[0]->cicilan_ke;
            })->addColumn('tanggal', function ($data) {
                return $data[0]->tanggal;
            })
            ->addColumn('action', function ($data) {
                $button = "&nbsp";
                $button .= '<a href=' . route("detail-angsuran", $data[0]->id) . ' class="btn btn-xs btn-warning " type="button"><span class="btn-label"><i class="fa fa-eye"></i></span></a>';
                $button .= "&nbsp";

                return $button;
            })
            ->rawColumns(['action'])->make(true);
    }

    public function add()
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['transaksi'] = DB::table('transaksis')
            ->leftJoin('nasabahs', 'id_nasabah', 'nasabahs.id')
            ->leftJoin('kelompoks', 'id_kelompok', 'kelompoks.id')
            ->select('transaksis.id', 'nama', 'nama_kelompok', 'nama_produk')
            ->whereNull('transaksis.deleted_at')
            ->where('status', 0)
            ->get();

        return view('angsuran.add', $data);
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $request->validate([
            'id_transaksi'  => 'required',
            'jml_angsuran'  => 'required',
            'tanggal'       => 'required',
        ]);

        $jml_angsuran   = intval(preg_replace('/\D/', '', $request->jml_angsuran));
        $prosentase     = MarginKeuntungan::pluck('prosentase')->first();
        $transaksi      = Transaksi::find($request->id_transaksi);

        $total_pinjaman         = $transaksi->total_pinjaman;
        $total_angsuran_bulanan = $transaksi->jumlah_cicilan;    
        $pokok                  = $transaksi->angsuran_pokok;
        $laba                   = $transaksi->angsuran_bagihasil;
        $jumlah_pinjaman_laba   = $transaksi->jumlah_pinjaman_laba;
        $jumlah_pinjaman_pokok  = $transaksi->jumlah_pinjaman_pokok;

        $sum_cicilan_terbayar = DB::table('angsurans')
                                    ->select(DB::raw('SUM(angsuran_pokok) as sum_angsuran_terbayar'))
                                    ->where('id_transaksi', $request->id_transaksi)
                                    ->pluck('sum_angsuran_terbayar');

        $sum_cicilan_laba_terbayar = DB::table('angsurans')
                                    ->select(DB::raw('SUM(angsuran_laba) as sum_angsuran_laba_terbayar'))
                                    ->where('id_transaksi', $request->id_transaksi)
                                    ->pluck('sum_angsuran_laba_terbayar');

        $total_cicilan_terbayar = $sum_cicilan_terbayar[0];
        $total_cicilan_laba_terbayar = $sum_cicilan_laba_terbayar[0];

        if (empty($total_cicilan_terbayar)) {
            $total_cicilan_terbayar = 0;
        }

        if (empty($total_cicilan_laba_terbayar)) {
            $total_cicilan_laba_terbayar = 0;
        }

        $sisa_pinjaman_pokok = ($jumlah_pinjaman_pokok-$total_cicilan_terbayar);
        $sisa_pinjaman_keuntungan = ($jumlah_pinjaman_laba-$total_cicilan_laba_terbayar);

        $total_sisa_keseluruhan = $sisa_pinjaman_pokok+$sisa_pinjaman_keuntungan;
        $total_pinjaman_keseluruhan =  $jumlah_pinjaman_pokok+$jumlah_pinjaman_laba;

        if ($total_cicilan_terbayar == 0) {
            $total_sisa_keseluruhan = $total_pinjaman_keseluruhan;
        }

        $mod = $jml_angsuran/$total_angsuran_bulanan;

        if ($mod <= 0) {
            if ($jml_angsuran <= $pokok) {
                $angsuran_laba = floor($mod)*$laba;
                $sisa_pinjaman_pokok = $jumlah_pinjaman_pokok-$total_cicilan_terbayar;
                $angsuran_pokok = $jml_angsuran - $angsuran_laba;

                if ($sisa_pinjaman_pokok < $angsuran_pokok) {
                    $angsuran_laba = $jml_angsuran - $sisa_pinjaman_pokok;
                    // $sisa_pinjaman = $sisa_pinjaman_pokok-$angsuran_pokok;
                    $sisa_pinjaman_laba = ($jumlah_pinjaman_laba-$total_cicilan_laba_terbayar) - $angsuran_laba;
                } else {
                    $sisa_pinjaman_laba = ($jumlah_pinjaman_laba-$total_cicilan_laba_terbayar) - $angsuran_laba;
                }
            } else {
                $angsuran_laba = $jml_angsuran - $pokok;
                $sisa_pinjaman_laba = ($jumlah_pinjaman_laba-$total_cicilan_laba_terbayar) - $angsuran_laba;
            }
        
        } else {
            if ($jml_angsuran < $total_sisa_keseluruhan) {

                $angsuran_laba = floor($mod)*$laba;
                $sisa_pinjaman_pokok = $jumlah_pinjaman_pokok-$total_cicilan_terbayar;
                $angsuran_pokok = $jml_angsuran - $angsuran_laba;

                if ($sisa_pinjaman_pokok < $angsuran_pokok) {
                    $angsuran_laba = $jml_angsuran - $sisa_pinjaman_pokok;
                    // $sisa_pinjaman = $sisa_pinjaman_pokok-$angsuran_pokok;
                    $sisa_pinjaman_laba = ($jumlah_pinjaman_laba-$total_cicilan_laba_terbayar) - $angsuran_laba;
                } else {
                    $sisa_pinjaman_laba = ($jumlah_pinjaman_laba-$total_cicilan_laba_terbayar) - $angsuran_laba;
                }

            } else {
                $angsuran_laba = $sisa_pinjaman_keuntungan;
                $sisa_pinjaman_laba = 0;
            }
        }

        if ($jml_angsuran <= $total_sisa_keseluruhan) {
            $sisa_pinjaman_pokok = $jumlah_pinjaman_pokok-$total_cicilan_terbayar;
            $angsuran_pokok = $jml_angsuran - $angsuran_laba;

            if ($sisa_pinjaman_pokok < $angsuran_pokok) {
                $angsuran_pokok = $sisa_pinjaman_pokok;
                $sisa_pinjaman = $sisa_pinjaman_pokok-$angsuran_pokok;
            } else {
                $sisa_pinjaman =  $sisa_pinjaman_pokok-$angsuran_pokok;
            }

        } else {
            $angsuran_pokok = $sisa_pinjaman_pokok;
            $sisa_pinjaman = 0;
            $jml_angsuran = $total_sisa_keseluruhan;
        }

        $angsura_ke = DB::table('angsurans')->where('id_transaksi', $request->id_transaksi)->latest()->pluck('cicilan_ke');

        if ($angsura_ke->isNotEmpty()) {
            $angsuran_ke = $angsura_ke[0]+1;
        } else {
            $angsuran_ke = 1;
        }

        DB::table('angsurans')->insert([
            'id_transaksi' => $request->id_transaksi,
            'cicilan_ke' => $angsuran_ke,
            'jml_angsuran' => $jml_angsuran,
            'sisa_pinjaman' => $sisa_pinjaman,
            'tanggal' => $request->tanggal,
            'created_at' => date('Y-m-d H:i:s'),
            'angsuran_pokok' => $angsuran_pokok,
            'angsuran_laba' => $angsuran_laba,
            'sisa_laba' => $sisa_pinjaman_laba
        ]);

        if ($sisa_pinjaman == 0 && $sisa_pinjaman_laba == 0) {
            DB::table('transaksis')->where('id', $request->id_transaksi)->update(['status' => 1]);
            DB::table('angsurans')->where('id_transaksi', $request->id_transaksi)->update(['status' => 1]);
        }

        return redirect()->route('list-angsuran');
    }

    public function detail($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['angsuran']                = Angsuran::find($id);
        $data['angsuran']->transaksi     = self::list_transaksi($data['angsuran']->id_transaksi);
        $data['angsuran']->status        = ($data['angsuran']->status == 1) ? 'Aktif' : 'Tidak Aktif';
        $data['angsuran']->tanggal       = date('d-m-Y', strtotime($data['angsuran']->tanggal));
        $data['angsuran']->jml_angsuran  = 'Rp. ' . number_format($data['angsuran']->jml_angsuran, 0, ",", ".");

        $data['list_angsuran'] = DB::table('angsurans')->where('id_transaksi', $data['angsuran']->id_transaksi)->get();

        $total_angsuran_pokok = 0;
        $total_angsuran_laba = 0;
        foreach ($data['list_angsuran'] as $angsuran) {
            $total_angsuran_pokok = $total_angsuran_pokok + $angsuran->angsuran_pokok;
            $total_angsuran_laba = $total_angsuran_laba + $angsuran->angsuran_laba;
        }

        $data['total_angsuran_pokok'] = $total_angsuran_pokok;
        $data['total_angsuran_laba'] = $total_angsuran_laba;

        return view('angsuran.detail', $data);
    }

    public function edit($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['angsuran']                = Angsuran::find($id);
        $data['angsuran']->tanggal       = date('d-m-Y', strtotime($data['angsuran']->tanggal));
        $data['angsuran']->jml_angsuran  = 'Rp. ' . number_format($data['angsuran']->jml_angsuran, 0, ",", ".");
        $data['angsuran']->sisa_pinjaman = 'Rp. ' . number_format($data['angsuran']->sisa_pinjaman, 0, ",", ".");
        $data['transaksi']               = Transaksi::where('id', '!=', $data['angsuran']->transaksi->id)->get();

        return view('angsuran.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_transaksi'  => 'required',
            'cicilan_ke'    => 'required',
            'jml_angsuran'  => 'required',
            'sisa_pinjaman' => 'required',
            'tanggal'       => 'required',
            'keterangan'    => 'required',
            'status'        => 'required',
        ]);

        $data['jml_angsuran']  = intval(preg_replace('/\D/', '', $data['jml_angsuran']));
        $data['sisa_pinjaman'] = intval(preg_replace('/\D/', '', $data['sisa_pinjaman']));
        $data['status']        = intval($data['status']);

        Angsuran::find($id)->update($data);

        return redirect()->route('list-angsuran');
    }

    public function delete($id)
    {
        Angsuran::destroy($id);
    }

    public function scan()
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        return view('angsuran.scan-qr-code', $data);
    }

    public function mobileView($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['nasabah'] = DB::table('nasabahs')->select('nasabahs.*', 'kelompoks.*', 'nasabahs.id as id_nasabah')->leftJoin('kelompoks', 'kelompoks.id', '=', 'nasabahs.id_kelompok')->where('nasabahs.id', $id)->get();

        $data['transaksi'] = DB::table('transaksis')->select('transaksis.*', 'transaksis.id as id_transaksi')->where('id_nasabah', $id)->where('transaksis.status', 0)->get();

        $data['id_transaksi'] = 0;
        if ($data['transaksi']->isNotEmpty()) {
            $data['id_transaksi'] =  $data['transaksi'][0]->id_transaksi;

            $data['angsuran'] = DB::table('angsurans')->where('id_transaksi', $data['transaksi'][0]->id_transaksi)->latest()->first();

            if (!empty($data['angsuran'])) {
                $data['sisa_pinjaman_total'] = $data['angsuran']->sisa_pinjaman + $data['angsuran']->sisa_laba;
            }
        }

        

        return view('angsuran.angsuranmobile', $data);
    }

    public function storeMobileView(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $request->validate([
            'jml_angsuran'  => 'required'
        ]);

        $jml_angsuran   = intval(preg_replace('/\D/', '', $request->jml_angsuran));
        $prosentase     = MarginKeuntungan::pluck('prosentase')->first();
        $transaksi      = Transaksi::find($request->id_transaksi);

        $total_pinjaman         = $transaksi->total_pinjaman;
        $total_angsuran_bulanan = $transaksi->jumlah_cicilan;    
        $pokok                  = $transaksi->angsuran_pokok;
        $laba                   = $transaksi->angsuran_bagihasil;
        $jumlah_pinjaman_laba   = $transaksi->jumlah_pinjaman_laba;
        $jumlah_pinjaman_pokok  = $transaksi->jumlah_pinjaman_pokok;

        $sum_cicilan_terbayar = DB::table('angsurans')
                                    ->select(DB::raw('SUM(angsuran_pokok) as sum_angsuran_terbayar'))
                                    ->where('id_transaksi', $request->id_transaksi)
                                    ->pluck('sum_angsuran_terbayar');

        $sum_cicilan_laba_terbayar = DB::table('angsurans')
                                    ->select(DB::raw('SUM(angsuran_laba) as sum_angsuran_laba_terbayar'))
                                    ->where('id_transaksi', $request->id_transaksi)
                                    ->pluck('sum_angsuran_laba_terbayar');

        $total_cicilan_terbayar = $sum_cicilan_terbayar[0];
        $total_cicilan_laba_terbayar = $sum_cicilan_laba_terbayar[0];

        if (empty($total_cicilan_terbayar)) {
            $total_cicilan_terbayar = 0;
        }

        if (empty($total_cicilan_laba_terbayar)) {
            $total_cicilan_laba_terbayar = 0;
        }

        $sisa_pinjaman_pokok = ($jumlah_pinjaman_pokok-$total_cicilan_terbayar);
        $sisa_pinjaman_keuntungan = ($jumlah_pinjaman_laba-$total_cicilan_laba_terbayar);

        $total_sisa_keseluruhan = $sisa_pinjaman_pokok+$sisa_pinjaman_keuntungan;
        $total_pinjaman_keseluruhan =  $jumlah_pinjaman_pokok+$jumlah_pinjaman_laba;

        if ($total_cicilan_terbayar == 0) {
            $total_sisa_keseluruhan = $total_pinjaman_keseluruhan;
        }

        $mod = $jml_angsuran/$total_angsuran_bulanan;

        if ($mod <= 0) {
            if ($jml_angsuran <= $pokok) {
                $angsuran_laba = floor($mod)*$laba;
                $sisa_pinjaman_pokok = $jumlah_pinjaman_pokok-$total_cicilan_terbayar;
                $angsuran_pokok = $jml_angsuran - $angsuran_laba;

                if ($sisa_pinjaman_pokok < $angsuran_pokok) {
                    $angsuran_laba = $jml_angsuran - $sisa_pinjaman_pokok;
                    // $sisa_pinjaman = $sisa_pinjaman_pokok-$angsuran_pokok;
                    $sisa_pinjaman_laba = ($jumlah_pinjaman_laba-$total_cicilan_laba_terbayar) - $angsuran_laba;
                } else {
                    $sisa_pinjaman_laba = ($jumlah_pinjaman_laba-$total_cicilan_laba_terbayar) - $angsuran_laba;
                }
            } else {
                $angsuran_laba = $jml_angsuran - $pokok;
                $sisa_pinjaman_laba = ($jumlah_pinjaman_laba-$total_cicilan_laba_terbayar) - $angsuran_laba;
            }
        
        } else {
            if ($jml_angsuran < $total_sisa_keseluruhan) {

                $angsuran_laba = floor($mod)*$laba;
                $sisa_pinjaman_pokok = $jumlah_pinjaman_pokok-$total_cicilan_terbayar;
                $angsuran_pokok = $jml_angsuran - $angsuran_laba;

                if ($sisa_pinjaman_pokok < $angsuran_pokok) {
                    $angsuran_laba = $jml_angsuran - $sisa_pinjaman_pokok;
                    // $sisa_pinjaman = $sisa_pinjaman_pokok-$angsuran_pokok;
                    $sisa_pinjaman_laba = ($jumlah_pinjaman_laba-$total_cicilan_laba_terbayar) - $angsuran_laba;
                } else {
                    $sisa_pinjaman_laba = ($jumlah_pinjaman_laba-$total_cicilan_laba_terbayar) - $angsuran_laba;
                }

            } else {
                $angsuran_laba = $sisa_pinjaman_keuntungan;
                $sisa_pinjaman_laba = 0;
            }
        }

        if ($jml_angsuran <= $total_sisa_keseluruhan) {
            $sisa_pinjaman_pokok = $jumlah_pinjaman_pokok-$total_cicilan_terbayar;
            $angsuran_pokok = $jml_angsuran - $angsuran_laba;

            if ($sisa_pinjaman_pokok < $angsuran_pokok) {
                $angsuran_pokok = $sisa_pinjaman_pokok;
                $sisa_pinjaman = $sisa_pinjaman_pokok-$angsuran_pokok;
            } else {
                $sisa_pinjaman =  $sisa_pinjaman_pokok-$angsuran_pokok;
            }

        } else {
            $angsuran_pokok = $sisa_pinjaman_pokok;
            $sisa_pinjaman = 0;
            $jml_angsuran = $total_sisa_keseluruhan;
        }

        $angsura_ke = DB::table('angsurans')->where('id_transaksi', $request->id_transaksi)->latest()->pluck('cicilan_ke');

        if ($angsura_ke->isNotEmpty()) {
            $angsuran_ke = $angsura_ke[0]+1;
        } else {
            $angsuran_ke = 1;
        }

        DB::table('angsurans')->insert([
            'id_transaksi' => $request->id_transaksi,
            'cicilan_ke' => $angsuran_ke,
            'jml_angsuran' => $jml_angsuran,
            'sisa_pinjaman' => $sisa_pinjaman,
            'tanggal' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'angsuran_pokok' => $angsuran_pokok,
            'angsuran_laba' => $angsuran_laba,
            'sisa_laba' => $sisa_pinjaman_laba
        ]);

        if ($sisa_pinjaman == 0 && $sisa_pinjaman_laba == 0) {
            DB::table('transaksis')->where('id', $request->id_transaksi)->update(['status' => 1]);
            DB::table('angsurans')->where('id_transaksi', $request->id_transaksi)->update(['status' => 1]);
        }

        return redirect('angsuran/mobile-view/'.$request->id_nasabah);

    }

}