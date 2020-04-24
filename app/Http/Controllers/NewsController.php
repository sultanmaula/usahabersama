<?php

namespace App\Http\Controllers;

use App\Classes\upload;
use App\Kategori_news;
use App\News;
use DB;
use App\Traits\admin_logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:administrator');
    }

    public function index()
    {
        $controller = new Controller;
        $data['menus'] = $controller->menus();
        //$data['news'] = News::with('admins', 'categories')->get();
        // $data['news'] = News::leftJoin('administrators', 'administrators.id', 'news.created_by')
        //                     ->leftJoin('kategori_news', 'kategori_news.id', 'news.id_kategori_berita')->get();
        $data['news'] = DB::table('news')
        ->select('news.*','administrators.name as name','kategori_news.nama_kategori as nama_kategori')
        ->leftJoin('administrators', 'administrators.id', 'news.created_by')
        ->leftJoin('kategori_news', 'kategori_news.id', 'news.id_kategori_berita')
        ->get();

        return view('news.index', $data);
    }

    public function add()
    {
        $controller = new Controller;
        $data['menus'] = $controller->menus();

        $data['categories'] = Kategori_news::all();

        return view('news.add', $data);
    }

    public function store(Request $request)
    {
        // $id = News::select('id')->where('id', $request->id)->get();
        $data = $request->validate([
            'judul'              => 'required',
            'id_kategori_berita' => 'required',
            'deskripsi'          => 'required',
            'image'              => 'max:2048',
        ]);

        if ($request->file('image')) {
            $upload        = new upload();
            $data['image'] = $upload->img($request->file('image'));
        } else {
            $data['image'] = env('DEFAULT_IMAGE');
            // $data['image'] = '';
        }
        $data['tanggal']    = date('Y-m-d H:i:s');
        $data['created_by'] = Auth::user()->id;


        // News::create($data);

        $news = DB::table('news')->insertGetID([
            'judul'              => $data['judul'],
            'id_kategori_berita' => $data['id_kategori_berita'],
            'deskripsi'          => $data['deskripsi'],
            'image'              => $data['image'],
            'tanggal'            => $data['tanggal'],
            'created_by'         => $data['created_by']
        ]);
    
        DB::table('user_notifications')->insert(
                ['id_user' => null,
                'tipe_user' => null,
                'created_by'=> Auth::user()->id,
                'is_view' => 0,
                'id_detail' => $news,
                'tipe_page' => 2,
                'page' => 2,
                'created_at'=>date('Y-m-d H:m:s'),'updated_at'=>date('Y-m-d H:m:s')
                ]
        );

        // Header('Content-type: application/json');
        // echo json_encode($data);die;

        
        admin_logs::addLogs("ADD-001", "Berita");
        return redirect()->route('list-news');
    }

    public function show(News $news, $id)
    {
        $controller = new Controller;
        $data['menus'] = $controller->menus();

        $data['news'] = News::where('id', $id)
                            ->with('categories', 'admins')
                            ->first();

        return view('news.show', $data);
    }

    public function edit(News $news, $id)
    {
        $controller = new Controller;
        $data['menus'] = $controller->menus();

        $data['categories'] = Kategori_news::all();
        $data['news']       = News::where('id', $id)
                                ->with('categories')
                                ->first();

        return view('news.edit', $data);
    }

    public function update(Request $request, News $news, $id)
    {
        $data = $request->validate([
            'judul'              => 'required',
            'id_kategori_berita' => 'required',
            'deskripsi'          => 'required',
            'image'              => 'max:2048',
        ]);

        if ($request->file('image')) {
            $upload        = new upload();
            $data['image'] = $upload->img($request->file('image'));
        }

        News::where('id', $id)->update($data);
        admin_logs::addLogs("UPD-002", "Berita");
        return redirect()->route('list-news');
    }

    public function delete($id)
    {
        News::where('id', $id)->delete();
        admin_logs::addLogs("DEL-003", "Berita");
        return redirect()->route('list-news');
    }

    public function category()
    {
        $controller = new Controller;
        $data['menus'] = $controller->menus();

        $data['categories'] = Kategori_news::with('admins')->get();

        return view('news.category.index', $data);
    }

    public function add_category()
    {
        $controller = new Controller;
        $data['menus'] = $controller->menus();

        return view('news.category.add', $data);
    }

    public function store_category(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required',
            'image'         => 'max:2048',
        ]);

        if ($request->file('image')) {
            $upload        = new upload();
            $data['image'] = $upload->img($request->file('image'));
        } else {
            $data['image'] = env('DEFAULT_IMAGE');
        }

        $data['created_by'] = Auth::user()->id;

        Kategori_news::create($data);
        admin_logs::addLogs("ADD-001", "Kategori Berita");
        return redirect()->route('category_news');
    }

    public function edit_category($id)
    {
        $controller = new Controller;
        $data['menus'] = $controller->menus();

        $data['category'] = Kategori_news::where('id', $id)->first();

        return view('news.category.edit', $data);
    }

    public function update_category(Request $request, $id)
    {
        $data = $request->validate([
            'nama_kategori' => 'required',
            'image'         => 'max:2048',
        ]);

        if ($request->file('image')) {
            $upload        = new upload();
            $data['image'] = $upload->img($request->file('image'));
        }

        Kategori_news::where('id', $id)->update($data);
        admin_logs::addLogs("UPD-002", "Kategori Berita");
        return redirect()->route('category_news');
    }

    public function delete_category($id)
    {
        Kategori_news::destroy($id);
        admin_logs::addLogs("DEL-003", "Kategori Berita");
        return redirect()->route('category_news');
    }
}
