<?php

namespace App\Http\Controllers;

use App\Aktivitas_Logs;
use App\Area;
use App\City;
use App\Departement;
use App\TipeUser;
use App\Kelompok;
use App\MarginKeuntungan;
use App\Traits\admin_logs;
use Datatables;
use DB;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:administrator');
    }

    public function listKelompok(){
        $controller    = new Controller;
        $data['menus'] = $controller->menus();
    
        return view('master/kelompok/listkelompok', $data);
    }

    public function listKelompokGet(){
        $datalist = Kelompok::whereNull('deleted_at')->get();
        // dd($datalist);
        $data = datatables()->of($datalist)
            ->addColumn('action',function ($data){ //m
                $button ='<a href="/master/kelompok/edit/'.$data->id.'">
                <button class="btn btn-xs btn-primary " type="button">
                <span class="btn-label"><i class="fa fa-file"></i></span>
                </button>
                </a>';
                $button.='<button class="btn btn-xs btn-danger" data-record-id="'.$data->id.'" data-record-title="The first one" data-toggle="modal" data-target="#confirm-delete"><span class="btn-label"><i class="fa fa-trash"></i></span></button>';
                $button.='&nbsp';
                return $button;
            })->rawColumns(['action'])->make(true);

        return $data;
    }

    public function addKelompok(){

        $controller    = new Controller;
        $data['menus'] = $controller->menus();
    
        return view('master/kelompok/addkelompok', $data);
    }

    public function storeKelompok(Request $request){
        $request->validate([
            'nama_kelompok' => 'required',
        ]);

        $kelompok                   = new Kelompok();
        $kelompok->nama_kelompok = $request->nama_kelompok;
        $kelompok->save();

        // admin_logs::addLogs("ADD-001", "Kelompok");
        return redirect()->route('list-kelompok');
    }

    public function editKelompok($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['data'] = Kelompok::find($id);

        return view('master/kelompok/editkelompok', $data);
    }

    public function updateKelompok(Request $request)
    {
        $request->validate([
            'id'        => 'required',
            'nama_kelompok'   => 'required',
        ]);

        DB::table('kelompoks')
        ->where('id', $request->id)
        ->update(
           [ 
            'nama_kelompok'=>$request->nama_kelompok,
            'updated_at'=>date('Y-m-d H:m:s')
           ]
       );

        // admin_logs::addLogs("UPD-002", "Departemen");
        return redirect()->route('list-kelompok');
    }

    public function deleteKelompok($id)
    {
        $data = Kelompok::find($id);
        $data->delete();

        // admin_logs::addLogs("DEL-003", "Departemen");
        return redirect()->route('list-kelompok');
    }


    // margin keuntungan

    public function listMarginkeuntungan(){
        $controller    = new Controller;
        $data['menus'] = $controller->menus();
    
        return view('master/marginkeuntungan/listmarginkeuntungan', $data);
    }

    public function listMarginkeuntunganGet(){
        $datalist = MarginKeuntungan::all();
        // dd($datalist);
        $data = datatables()->of($datalist)
            ->addColumn('action',function ($data){ //m
                $button ='<a href="/master/marginkeuntungan/edit/'.$data->id.'">
                <button class="btn btn-xs btn-primary " type="button">
                <span class="btn-label"><i class="fa fa-file"></i></span>
                </button>
                </a>';
                $button.='<button class="btn btn-xs btn-danger" data-record-id="'.$data->id.'" data-record-title="The first one" data-toggle="modal" data-target="#confirm-delete"><span class="btn-label"><i class="fa fa-trash"></i></span></button>';
                $button.='&nbsp';
                return $button;
            })->rawColumns(['action'])->make(true);

        return $data;
    }


    public function addMarginkeuntungan(){

        $controller    = new Controller;
        $data['menus'] = $controller->menus();
    
        return view('master/marginkeuntungan/addmarginkeuntungan', $data);
    }

    public function storeMarginkeuntungan(Request $request){
        $request->validate([
            'prosentase' => 'required',
        ]);

        $kelompok  = new MarginKeuntungan();
        $kelompok->prosentase = $request->prosentase;
        $kelompok->save();

        return redirect()->route('list-marginkeuntungan');
    }

    public function deleteMarginkeuntungan($id){

        $data = MarginKeuntungan::find($id);
        $data->delete();

        return redirect()->route('list-marginkeuntungan');

    }

    public function editMarginkeuntungan($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['data'] = MarginKeuntungan::find($id);

        return view('master/marginkeuntungan/editmarginkeuntungan', $data);
    }

    public function updateMarginkeuntungan(Request $request)
    {
        $request->validate([
            'id'        => 'required',
            'prosentase'   => 'required',
        ]);

        DB::table('margin_keuntungans')
        ->where('id', $request->id)
        ->update(
           [ 
            'prosentase'=>$request->prosentase,
            'updated_at'=>date('Y-m-d H:m:s')
           ]
       );

        // admin_logs::addLogs("UPD-002", "Departemen");
        return redirect()->route('list-marginkeuntungan');
    }
    


    // Department
    public function indexDepartment()
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();
    
        $data['data'] = Departement::all();
        return view('master/departement', $data);
    }

    public function addDepartment()
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        return view('master/adddepartement', $data);
    }

    public function insertdepartement(Request $request)
    {
        $request->validate([
            'nama_departement' => 'required',
            'kode_departement' => 'required',
            'urutan'           => 'required',
            'deskripsi'        => 'required',
        ]);

        $departement                   = new Departement();
        $departement->nama_departement = $request->nama_departement;
        $departement->kode_departement = $request->kode_departement;
        $departement->deskripsi        = $request->deskripsi;
        $departement->urutan           = $request->urutan;
        $departement->save();

        admin_logs::addLogs("ADD-001", "Departemen");
        return redirect()->route('departement');
    }

    public function editdepartement($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['data'] = Departement::find($id);

        return view('master/editDepartement', $data);
    }

    public function updateDepartement($id, Request $request)
    {
        $request->validate([
            'nama_departement' => 'required',
            'kode_departement' => 'required',
            'urutan'           => 'required',
            'deskripsi'        => 'required',
        ]);

        $departement                   = new Departement();
        $departement                   = Departement::find($id);
        $departement->nama_departement = $request->nama_departement;
        $departement->kode_departement = $request->kode_departement;
        $departement->deskripsi        = $request->deskripsi;
        $departement->urutan           = $request->urutan;
        $departement->save();

        admin_logs::addLogs("UPD-002", "Departemen");
        return redirect()->route('departement');
    }

    public function deletedepartement($id)
    {
        $data = Departement::find($id);
        $data->delete();

        admin_logs::addLogs("DEL-003", "Departemen");
        return redirect()->route('departement');
    }


    public function indexAktivitasLog()
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['aktifity_logs'] = DB::table('aktifity_logs')
            ->get();

        return view('master.aktifity_logs', $data);
    }

    public function indexListAktivitasLog()
    {
        $aktifity_logs = Aktivitas_Logs::all();
        return datatables()->of(Aktivitas_Logs::latest()->get())
            ->addColumn('action', function ($data) {
                //m
                $button = '<a href="edit-aktifitaslog/' . $data->id . '">
             <button class="btn btn-xs btn-primary " type="button">
             <span class="btn-label"><i class="fa fa-file"></i></span>
             </button>
             </a>';
                $button .= '<button class="btn btn-xs btn-danger" data-record-id="' . $data->id . '" data-record-title="The first one" data-toggle="modal" data-target="#confirm-delete"><span class="btn-label"><i class="fa fa-trash"></i></span></button>';
                $button .= "&nbsp";
                return $button;
            })->rawColumns(['action'])->make(true);
    }

    public function addAktivitasLog()
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        return view('master.add-aktifitaslog', $data);
    }

    public function storeAktifitasLog(Request $request)
    {
        $request->validate([
            'nama_aktifitas' => 'required',
            'kode_aktifitas' => 'required',
        ]);

        $aktifity_logs                 = new Aktivitas_Logs;
        $aktifity_logs->nama_aktifitas = $request->nama_aktifitas;
        $aktifity_logs->kode_aktifitas = $request->kode_aktifitas;
        $aktifity_logs->created_at     = date('Y-m-d H:m:s');
        $aktifity_logs->updated_at     = date('Y-m-d H:m:s');
        $aktifity_logs->save();

        admin_logs::addLogs("ADD-001", "Aktivitas Log");
        return redirect()->route('aktifitas_logs');
    }

    public function editAktifitasLog($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['aktifity_logs'] = Aktivitas_Logs::find($id);

        return view('master.edit-aktifitaslog', $data);
    }

    public function deleteAktifitasLog($id)
    {
        $data = Aktivitas_Logs::find($id);
        $data->delete();

        admin_logs::addLogs("DEL-003", "Aktivitas Log");
        return redirect()->route('aktifitas_logs');
    }

 
    public function updateAktifitasLog(Request $request)
    {
        $aktifity_logs                 = Aktivitas_Logs::find($request->id);
        $aktifity_logs->nama_aktifitas = $request->nama_aktifitas;
        $aktifity_logs->kode_aktifitas = $request->kode_aktifitas;
        $aktifity_logs->save();

        admin_logs::addLogs("UPD-002", "Aktivitas Log");
        return redirect()->route('aktifitas_logs');
    }

    // public function deleteAktifitasLog($id)
    // {
    //     $data=Aktivitas_Logs::find($id);
    //     $data->delete();
    //     return redirect()->route('aktifitas_logs');
    // }

    

  

}
