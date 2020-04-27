<?php

namespace App\Http\Controllers;

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
        $data = Transaksi::all();

        return datatables()->of($data)
            ->addColumn('status', function ($data) {
                return ($data->status == 1) ? 'Aktif' : 'Tidak Aktif';
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
    public function roleIndex($query)
    {
        $data = DB::table('admin')
            ->select('admin.*', 'roles.name as role_name')
            ->leftJoin('roles', 'roles.id', 'admin.id_role')
            ->whereNull('admin.deleted_at')
            ->where('id_role', $query)
            ->get();
        return datatables()->of($data)
            ->addColumn('action', function ($data) {
                //m
                if ($data->role_name == 'SUPERADMIN' || $data->role_name == 'PRINCIPLE') {
                    $button = '<a href=' . route("edit-admin", $data->id) . ' style="display:none;" class="btn btn-xs btn-primary " type="button"><span class="btn-label"><i class="fa fa-file"></i></span></a>' . '&nbsp';
                    $button .= '<button class="btn btn-xs btn-danger" style="display:none;" data-record-id="' . $data->id . '" data-record-title="The first one" data-toggle="modal" data-target="#confirm-delete"><span class="btn-label"><i class="fa fa-trash"></i></span></button>';
                    $button .= "&nbsp";
                    $button .= '<a href=' . route("show-admin", $data->id) . ' class="btn btn-xs btn-warning " type="button"><span class="btn-label"><i class="fa fa-eye"></i></span></a>';
                    $button .= "&nbsp";
                } else {
                    $button = '<a href=' . route("edit-admin", $data->id) . ' class="btn btn-xs btn-primary " type="button"><span class="btn-label"><i class="fa fa-file"></i></span></a>' . '&nbsp';
                    $button .= '<button class="btn btn-xs btn-danger" data-record-id="' . $data->id . '" data-record-title="The first one" data-toggle="modal" data-target="#confirm-delete"><span class="btn-label"><i class="fa fa-trash"></i></span></button>';
                    $button .= "&nbsp";
                    $button .= '<a href=' . route("show-admin", $data->id) . ' class="btn btn-xs btn-warning " type="button"><span class="btn-label"><i class="fa fa-eye"></i></span></a>';
                    $button .= "&nbsp";
                }

                return $button;
            })
            ->rawColumns(['action'])->make(true);
    }

    public function add()
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

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

        $data['harga_produk']   = intval(preg_replace('/\D/', '', $data['harga_produk']));
        $data['total_pinjaman'] = intval(preg_replace('/\D/', '', $data['total_pinjaman']));
        $data['jumlah_cicilan'] = intval(preg_replace('/\D/', '', $data['jumlah_cicilan']));
        $data['sisa_pinjaman']  = intval(preg_replace('/\D/', '', $data['sisa_pinjaman']));
        $data['status']         = intval($data['status']);

        Transaksi::create($data);
        // admin_logs::addLogs("ADD-001", "Administrator");
        return redirect()->route('list-admin');
    }

    public function detail($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['transaksi']          = Transaksi::find($id);
        $data['transaksi']->status  = ($data['transaksi']->status == 1) ? 'Aktif' : 'Tidak Aktif';
        $data['transaksi']->tanggal = date('d-m-Y', strtotime($data['transaksi']->tanggal));
        // admin_logs::addLogs("DTL-004", "Administrator");

        return view('transaksi.detail', $data);
    }

    public function edit($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['admin'] = Transaksi::find($id);

        return view('admin.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama'    => 'required',
            'no_hp'   => 'required',
            'email'   => 'required',
            // 'password' => 'required|confirmed',
            'id_role' => 'required',
        ]);

        Administrator::find($id)->update($data);
        // admin_logs::addLogs("UPD-002", "Administrator");

        return redirect()->route('list-admin');
    }

    public function destroy($id)
    {
        $admin = Administrator::find($id);
        $admin->delete();
        // admin_logs::addLogs("DEL-003", "Administrator");

        return redirect()->route('list-admin');
    }
}
