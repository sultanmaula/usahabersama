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
        $transaksi->hargaPlusLaba      = 'Rp. ' . number_format($transaksi->laba + $transaksi->harga_produk, 0, ",", ".");
        $transaksi->laba               = 'Rp. ' . number_format($transaksi->laba, 0, ",", ".");
        $transaksi->harga_produk       = 'Rp. ' . number_format($transaksi->harga_produk, 0, ",", ".");
        $transaksi->angsuran_pokok     = 'Rp. ' . number_format($transaksi->angsuran_pokok, 0, ",", ".");
        $transaksi->angsuran_bagihasil = 'Rp. ' . number_format($transaksi->angsuran_bagihasil, 0, ",", ".");
        $transaksi->jumlah_cicilan     = 'Rp. ' . number_format($transaksi->jumlah_cicilan, 0, ",", ".");
        $transaksi->status         = ($transaksi->status == 1) ? 'LUNAS' : 'BELUM LUNAS';

        return $transaksi;
    }

    public function get_list()
    {
        $data = Angsuran::all();

        return datatables()->of($data)
            ->addColumn('status', function ($data) {
                return ($data->status == 1) ? 'Lunas' : 'Belum Lunas';
            })
            ->addColumn('transaksi', function ($data) {
                $trans     = self::list_transaksi($data->id_transaksi);
                $transaksi = $trans->nama . ' - ' . $trans->nama_kelompok . ' - ' . $trans->nama_produk;
                return $transaksi;
            })
            ->addColumn('action', function ($data) {

                $button = '<a href=' . route("edit-angsuran", $data->id) . ' class="btn btn-xs btn-primary " type="button"><span class="btn-label"><i class="fa fa-file"></i></span></a>' . '&nbsp';
                $button .= '<button class="btn btn-xs btn-danger" data-record-id="' . $data->id . '" data-record-title="The first one" data-toggle="modal" data-target="#confirm-delete"><span class="btn-label"><i class="fa fa-trash"></i></span></button>';
                $button .= "&nbsp";
                $button .= '<a href=' . route("detail-angsuran", $data->id) . ' class="btn btn-xs btn-warning " type="button"><span class="btn-label"><i class="fa fa-eye"></i></span></a>';
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
            ->get();

        return view('angsuran.add', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_transaksi'  => 'required',
            'cicilan_ke'    => 'required',
            'jml_angsuran'  => 'required',
            // 'sisa_pinjaman' => 'required',
            'tanggal'       => 'required',
            'keterangan'    => 'required',
            // 'status'        => 'required',
        ]);

        $data['jml_angsuran']  = intval(preg_replace('/\D/', '', $data['jml_angsuran']));
        // $data['sisa_pinjaman'] = intval(preg_replace('/\D/', '', $data['sisa_pinjaman']));
        // $data['status']        = intval($data['status']);

        Angsuran::create($data);
        // admin_logs::addLogs("ADD-001", "Administrator");
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
        $data['angsuran']->sisa_pinjaman = 'Rp. ' . number_format($data['angsuran']->sisa_pinjaman, 0, ",", ".");
        // admin_logs::addLogs("DTL-004", "Administrator");

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
        // dd($data['nasabah']);

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
        // admin_logs::addLogs("UPD-002", "Administrator");

        return redirect()->route('list-angsuran');
    }

    public function delete($id)
    {
        Angsuran::destroy($id);
    }
}
