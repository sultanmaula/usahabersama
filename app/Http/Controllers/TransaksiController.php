<?php

namespace App\Http\Controllers;

use App\Nasabah;
use App\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:administrator');
    }
    function list() {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        return view('transaksi.index', $data);
    }

    public function get_list()
    {
        $data = Transaksi::with('nasabah')->get();

        return datatables()->of($data)
            ->addColumn('status', function ($data) {
                return ($data->status == 1) ? 'Aktif' : 'Tidak Aktif';
            })
            ->addColumn('nama_nasabah', function ($data) {
                return $data->nasabah['nama'];
            })
            ->addColumn('action', function ($data) {

                $button = '<a href=' . route("edit-transaksi", $data->id) . ' class="btn btn-xs btn-primary " type="button"><span class="btn-label"><i class="fa fa-file"></i></span></a>' . '&nbsp';
                $button .= '<button class="btn btn-xs btn-danger" data-record-id="' . $data->id . '" data-record-title="The first one" data-toggle="modal" data-target="#confirm-delete"><span class="btn-label"><i class="fa fa-trash"></i></span></button>';
                $button .= "&nbsp";
                $button .= '<a href=' . route("detail-transaksi", $data->id) . ' class="btn btn-xs btn-warning " type="button"><span class="btn-label"><i class="fa fa-eye"></i></span></a>';
                $button .= "&nbsp";

                return $button;
            })
            ->rawColumns(['action'])->make(true);
    }

    public function add()
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['nasabah'] = Nasabah::all();
        return view('transaksi.add', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_produk'    => 'required',
            'harga_produk'   => 'required',
            'id_nasabah'     => 'required',
            'total_pinjaman' => 'required',
            'tanggal'        => 'required',
            'jumlah_cicilan' => 'required',
            'sisa_pinjaman'  => 'required',
            'status'         => 'required',
        ]);

        $data['harga_produk']       = intval(preg_replace('/\D/', '', $data['harga_produk']));
        $data['total_pinjaman']     = intval(preg_replace('/\D/', '', $data['total_pinjaman']));
        $data['sisa_pinjaman']      = intval(preg_replace('/\D/', '', $data['sisa_pinjaman']));
        $data['status']             = intval($data['status']);
        $data['angsuran_pokok']     = intval($data['harga_produk'] / 24);
        $data['angsuran_bagihasil'] = intval((($data['harga_produk'] * 30) / 100) / 24);
        $data['jumlah_cicilan']     = intval($data['angsuran_pokok'] + $data['angsuran_bagihasil']);

        Transaksi::create($data);
        // admin_logs::addLogs("ADD-001", "Administrator");
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
        // admin_logs::addLogs("DTL-004", "Administrator");

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
            'total_pinjaman' => 'required',
            'tanggal'        => 'required',
            'jumlah_cicilan' => 'required',
            'sisa_pinjaman'  => 'required',
            'status'         => 'required',
        ]);

        $data['harga_produk']       = intval(preg_replace('/\D/', '', $data['harga_produk']));
        $data['total_pinjaman']     = intval(preg_replace('/\D/', '', $data['total_pinjaman']));
        $data['sisa_pinjaman']      = intval(preg_replace('/\D/', '', $data['sisa_pinjaman']));
        $data['status']             = intval($data['status']);
        $data['angsuran_pokok']     = intval($data['harga_produk'] / 24);
        $data['angsuran_bagihasil'] = intval((($data['harga_produk'] * 30) / 100) / 24);
        $data['jumlah_cicilan']     = intval($data['angsuran_pokok'] + $data['angsuran_bagihasil']);

        Transaksi::find($id)->update($data);
        // admin_logs::addLogs("UPD-002", "Administrator");

        return redirect()->route('list-transaksi');
    }

    public function delete($id)
    {
        Transaksi::destroy($id);
    }
}
