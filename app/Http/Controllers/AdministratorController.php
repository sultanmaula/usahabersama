<?php

namespace App\Http\Controllers;

use App\Administrator;
use App\Role;
use App\Traits\admin_logs;
use DB;
use Illuminate\Http\Request;

class AdministratorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:administrator');
    }
    public function index()
    {
        $controller    = new Controller;
        $data['roles'] = Role::all();
        $data['menus'] = $controller->menus();

        $data['admins'] = DB::table('admin')
            ->select('admin.*', 'roles.name as role_name')
            ->leftJoin('roles', 'roles.id', 'admin.id_role')
            ->whereNull('admin.deleted_at')
            ->paginate(10);

        return view('admin.index', $data);
    }
    public function getIndex()
    {

        return datatables()->of($data)
            ->addColumn('action', function ($data) {
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


    public function create()
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['roles'] = Role::where('status', 1)->get();

        return view('admin.add', $data);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'     => 'required',
            'no_hp'    => 'required',
            'email'    => 'required',
            'password' => 'required|confirmed',
            'id_role'  => 'required',
        ]);

        $data['password'] = bcrypt($data['password']);
        Administrator::create($data);
        // admin_logs::addLogs("ADD-001", "Administrator");

        return redirect()->route('list-admin');
    }


    public function show($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['admin'] = Administrator::with('roles')->find($id);
        // dd($data['admin']);
        // admin_logs::addLogs("DTL-004", "Administrator");

        return view('admin.show', $data);
    }


    public function edit($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['roles'] = Role::all();
        $data['admin'] = Administrator::find($id);

        return view('admin.edit', $data);
    }


    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama'     => 'required',
            'no_hp'    => 'required',
            'email'    => 'required',
            // 'password' => 'required|confirmed',
            'id_role'  => 'required',
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
