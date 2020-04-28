<?php

namespace App\Http\Controllers;

use App\Angsuran;
use App\Transaksi;
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

    public function get_list()
    {
        $data = Angsuran::all();

        return datatables()->of($data)
            ->addColumn('status', function ($data) {
                return ($data->status == 1) ? 'Aktif' : 'Tidak Aktif';
            })
            // ->addColumn('nama_nasabah', function ($data) {
            //     return $data->nasabah['nama'];
            // })
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

        $data['transaksi'] = Transaksi::all();
        return view('angsuran.add', $data);
    }

    public function store(Request $request)
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

        $data['jml_angsuran'] = intval(preg_replace('/\D/', '', $data['jml_angsuran']));
        $data['sisa_pinjaman']  = intval(preg_replace('/\D/', '', $data['sisa_pinjaman']));
        $data['status']         = intval($data['status']);

        Angsuran::create($data);
        // admin_logs::addLogs("ADD-001", "Administrator");
        return redirect()->route('list-angsuran');
    }

    public function detail($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['angsuran']                 = Angsuran::find($id);
        $data['angsuran']->status         = ($data['angsuran']->status == 1) ? 'Aktif' : 'Tidak Aktif';
        $data['angsuran']->tanggal        = date('d-m-Y', strtotime($data['angsuran']->tanggal));
        $data['angsuran']->jml_angsuran = 'Rp. ' . number_format($data['angsuran']->jml_angsuran, 0, ",", ".");
        $data['angsuran']->sisa_pinjaman  = 'Rp. ' . number_format($data['angsuran']->sisa_pinjaman, 0, ",", ".");
        // admin_logs::addLogs("DTL-004", "Administrator");

        return view('angsuran.detail', $data);
    }

    public function edit($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['angsuran']                 = Angsuran::find($id);
        $data['angsuran']->tanggal        = date('d-m-Y', strtotime($data['angsuran']->tanggal));
        $data['angsuran']->jml_angsuran = 'Rp. ' . number_format($data['angsuran']->jml_angsuran, 0, ",", ".");
        $data['angsuran']->sisa_pinjaman  = 'Rp. ' . number_format($data['angsuran']->sisa_pinjaman, 0, ",", ".");
        $data['transaksi']                  = Transaksi::where('id', '!=', $data['angsuran']->transaksi->id)->get();
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

        $data['jml_angsuran'] = intval(preg_replace('/\D/', '', $data['jml_angsuran']));
        $data['sisa_pinjaman']  = intval(preg_replace('/\D/', '', $data['sisa_pinjaman']));
        $data['status']         = intval($data['status']);

        Angsuran::find($id)->update($data);
        // admin_logs::addLogs("UPD-002", "Administrator");

        return redirect()->route('list-angsuran');
    }

    public function delete($id)
    {
        Angsuran::destroy($id);
    }
}
