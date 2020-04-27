<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nasabah;
use App\Kelompok;
use Datatables;
use DB;

class NasabahController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:administrator');
    }

    public function listNasabah(){
        $controller    = new Controller;
        $data['menus'] = $controller->menus();
    
        return view('nasabah/listnasabah', $data);
    }

    public function listNasabahGet(){
        $datalist = DB::table('nasabahs as n')->select('n.id','n.nama','n.alamat','k.nama_kelompok','n.no_hp','n.nik','n.foto')
        ->leftJoin('kelompoks as k', 'k.id', '=', 'n.id_kelompok')
        ->whereNull('n.deleted_at')
        ->whereNull('k.deleted_at')
        ->get();
        // dd($datalist);
        $data = datatables()->of($datalist)
            ->addColumn('action',function ($data){ //m
                $button ='<a href="/nasabah/edit/'.$data->id.'">
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

    public function addNasabah(){

        $controller    = new Controller;
        $data['menus'] = $controller->menus();
        $data['groups'] = Kelompok::all();
    
        return view('nasabah/addNasabah', $data);
    }

    public function storeNasabah(Request $request){
        $request->validate([
            'nama_nasabah' => 'required',
            'alamat' => 'required',
            'kelompok' => 'required',
            'no_hp' => 'required',
            'nik' => 'required',
            'foto' => 'required|file|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $file= $request->file('foto');
        $tujuan_upload = 'nasabah_image';
        $namaFile = time()."_".$file->getClientOriginalName();
        // dd($request);
        $file->move($tujuan_upload, $namaFile);

        $nasabah                   = new Nasabah();
        $nasabah->nama = $request->nama_nasabah;
        $nasabah->alamat = $request->alamat;
        $nasabah->id_kelompok = $request->kelompok;
        $nasabah->no_hp = $request->no_hp;
        $nasabah->nik = $request->nik;
        $nasabah->foto = $namaFile;
        $nasabah->save();

        // admin_logs::addLogs("ADD-001", "Kelompok");
        return redirect()->route('list-nasabah');
    }

    public function editNasabah($id)
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        $data['data'] = Nasabah::find($id);
        $data['kelompoks'] = Kelompok::all();

        return view('nasabah/editnasabah', $data);
    }

    public function updateNasabah(Request $request)
    {
        $request->validate([
            'nama_nasabah' => 'required',
            'alamat' => 'required',
            'kelompok' => 'required',
            'no_hp' => 'required',
            'nik' => 'required',
            'foto' => 'file|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        $nasabah = Nasabah::find($request->id);

        if (empty($request->file('foto'))){
            DB::table('nasabahs')
            ->where('id', $request->id)
            ->update(
                [ 
                    'nama'=>$request->nama_nasabah,
                    'alamat'=>$request->prosentase,
                    'id_kelompok'=>$request->kelompok,
                    'no_hp'=>$request->no_hp,
                    'nik'=>$request->nik,
                    'updated_at'=>date('Y-m-d H:m:s')
                    ]
            );
        }
        else{

            unlink('nasabah_image/'.$nasabah->foto); //menghapus file lama

            // insert new foto
            $file= $request->file('foto');
            $tujuan_upload = 'nasabah_image';
            $namaFile = time()."_".$file->getClientOriginalName();
            $file->move($tujuan_upload, $namaFile);
            // update data
            DB::table('nasabahs')
            ->where('id', $request->id)
            ->update(
                [ 
                    'nama'=>$request->nama_nasabah,
                    'alamat'=>$request->alamat,
                    'id_kelompok'=>$request->kelompok,
                    'no_hp'=>$request->no_hp,
                    'nik'=>$request->nik,
                    'foto'=>$namaFile,
                    'updated_at'=>date('Y-m-d H:m:s')
                    ]
            );
        }

        // admin_logs::addLogs("UPD-002", "Departemen");
        return redirect()->route('list-nasabah');
    }


    public function deleteNasabah($id)
    {

        $data = Nasabah::find($id);

        $file = 'nasabah_image/'.$data->foto;
        if (is_file($file)) {
          unlink($file);
        }
        // dd($data->foto);
        $data->delete();

        // admin_logs::addLogs("DEL-003", "Departemen");
        return redirect()->route('list-kelompok');
    }


}