<?php

namespace App\Http\Controllers;

use App\Nasabah;
use App\Transaksi;
use App\MarginKeuntungan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class TransaksiController extends Controller
{
    public $prosentase;

    public function __construct()
    {
        $this->middleware('auth:administrator');

        $this->prosentase = MarginKeuntungan::pluck('prosentase')->first();
    }

    function list() {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        // $data['transaksis'] = Transaksi::with('nasabah')->get();
        $data['transaksis'] = DB::table('transaksis')
                                                ->leftJoin('nasabahs', 'id_nasabah', 'nasabahs.id')
                                                ->leftJoin('kelompoks', 'id_kelompok', 'kelompoks.id')
                                                ->select('transaksis.*', 'nama', 'nama_kelompok')
                                                ->whereNull('transaksis.deleted_at')
                                                ->paginate(10);

        foreach($data['transaksis'] as $row) {
            $row->tanggal = \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y');
            $row->tanggal_jatuh_tempo = \Carbon\Carbon::parse($row->tanggal_jatuh_tempo)->format('d/m/Y');
        }

        // dd($data['transaksis']);

        return view('transaksi.index', $data);
    }

    public function get_list()
    {


        return datatables()->of($data)
            ->addColumn('status', function ($data) {
                return ($data->status == 1) ? 'Lunas' : 'Belum Lunas';
            })
            ->addColumn('nama_nasabah', function ($data) {
                return $data->nasabah['nama'];
            })
            ->addColumn('action', function ($data) {

                // $button = '<a href=' . route("edit-transaksi", $data->id) . ' class="btn btn-xs btn-primary " type="button"><span class="btn-label"><i class="fa fa-file"></i></span></a>' . '&nbsp';
                $button = '';
                $button .= "&nbsp";
                $button .= '';
                $button .= "&nbsp";

                return $button;
            })
            ->rawColumns(['action'])->make(true);
    }

    public function add()
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['nasabah'] = DB::table('nasabahs')
                                    ->whereNull('nasabahs.deleted_at')
                                    ->get();

        // dd($data['nasabah']);

        return view('transaksi.add', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_produk'           => 'required',
            'harga_produk'          => 'required',
            'id_nasabah'            => 'required',
        ]);


        $data['harga_produk']            = intval(preg_replace('/\D/', '', $data['harga_produk']));

        $jumlah_pinjaman_pokok = $data['harga_produk'];
        $dp = 0;
        if ($data['harga_produk'] >= 10000000) {
            $dp = $data['harga_produk'] - 10000000;
            $jumlah_pinjaman_pokok = 10000000;
        }

        $tanggal = new Carbon($request->tanggal);

        $tanggal_transaksi = \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d');
        
        $jatuh_tempo = $tanggal->addYears(2); 

        $data['total_pinjaman']          = intval(preg_replace('/\D/', '', $jumlah_pinjaman_pokok));
        $data['sisa_pinjaman']           = $data['total_pinjaman'];
        $data['status']                  = 0;
        $data['angsuran_pokok']          = intval($data['total_pinjaman'] / 24);
        $data['angsuran_bagihasil']      = intval((($data['total_pinjaman'] * $this->prosentase) / 100) / 24);
        $data['jumlah_cicilan']          = $data['angsuran_pokok'] + $data['angsuran_bagihasil'];
        $data['tanggal_jatuh_tempo']     = $jatuh_tempo;
        $data['tanggal']                 = $tanggal_transaksi;
        $data['jumlah_pinjaman_pokok']   = $jumlah_pinjaman_pokok;
        $data['dp']                      = $dp;
        $data['jumlah_pinjaman_laba']    = $jumlah_pinjaman_pokok * $this->prosentase / 100;

        Transaksi::create($data);
        
        return redirect()->route('list-transaksi');
    }

    public function detail($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['transaksi']                 = Transaksi::with('nasabah')->find($id);
        $data['transaksi']->status         = ($data['transaksi']->status == 1) ? 'Lunas' : 'Belum Lunas';
        $data['transaksi']->tanggal        = date('d-m-Y', strtotime($data['transaksi']->tanggal));
        $data['transaksi']->harga_produk   = 'Rp. ' . number_format($data['transaksi']->harga_produk, 0, ",", ".");
        $data['transaksi']->total_pinjaman = 'Rp. ' . number_format($data['transaksi']->total_pinjaman, 0, ",", ".");
        $data['transaksi']->jumlah_cicilan = 'Rp. ' . number_format($data['transaksi']->jumlah_cicilan, 0, ",", ".");
        $data['transaksi']->sisa_pinjaman  = 'Rp. ' . number_format($data['transaksi']->sisa_pinjaman, 0, ",", ".");
        $data['transaksi']->jumlah_pinjaman   = 'Rp. ' . number_format($data['transaksi']->jumlah_pinjaman_pokok+$data['transaksi']->jumlah_pinjaman_laba, 0, ",", ".");
        $data['transaksi']->dp             = 'Rp. ' . number_format($data['transaksi']->dp, 0, ",", ".");

        return view('transaksi.detail', $data);
    }

    public function edit($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['transaksi']                 = Transaksi::find($id);
        $data['transaksi']->tanggal        = date('d-m-Y', strtotime($data['transaksi']->tanggal));
        $data['transaksi']->harga_produk   = 'Rp. ' . number_format($data['transaksi']->harga_produk, 0, ",", ".");
        $data['transaksi']->total_pinjaman = 'Rp. ' . number_format($data['transaksi']->total_pinjaman, 0, ",", ".");
        $data['transaksi']->jumlah_cicilan = 'Rp. ' . number_format($data['transaksi']->jumlah_cicilan, 0, ",", ".");
        $data['transaksi']->sisa_pinjaman  = 'Rp. ' . number_format($data['transaksi']->sisa_pinjaman, 0, ",", ".");
        $data['nasabah']                   = Nasabah::where('id', '!=', $data['transaksi']->nasabah->id)->get();
        // dd($data['nasabah']);

        return view('transaksi.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_produk'    => 'required',
            'harga_produk'   => 'required',
            'id_nasabah'     => 'required',
            'tanggal'        => 'required',
        ]);

        $data['harga_produk']       = intval(preg_replace('/\D/', '', $data['harga_produk']));
        $data['total_pinjaman']     = ( $data['harga_produk'] * $this->prosentase ) / 100;
        $data['sisa_pinjaman']      = $data['total_pinjaman'];
        $data['angsuran_pokok']     = intval($data['harga_produk'] / 24);
        $data['angsuran_bagihasil'] = intval((($data['harga_produk'] * 30) / 100) / 24);
        $data['jumlah_cicilan']     = $data['angsuran_pokok'] + $data['angsuran_bagihasil'];

        Transaksi::find($id)->update($data);
        // admin_logs::addLogs("UPD-002", "Administrator");

        return redirect()->route('list-transaksi');
    }

    public function delete($id)
    {
        Transaksi::destroy($id);
    }
}