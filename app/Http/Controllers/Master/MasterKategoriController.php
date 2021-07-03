<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Kategori;
use App\Models\Master\Variasi;
use File;
use Auth;
use DB;
use Session;
use DataTables;
use Validator; 

class MasterKategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$title = 'Master Kategori';

        return view('admin.kategori.index', compact('title'));
    }

    public function scopedata(){
        
        $data = DB::table('master_category as k')
                        ->select(
                            'k.*',
                            'v.name as namavariasi'
                        )
                        ->leftjoin('master_variasi as v','v.id','=','k.id_variasi')
                        ->where('k.isdelete',0)
                        ->get();


        return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('cekstatus', function($row){
                    if($row->status == '1'){
                        $stats = "Aktif";
                    }else{
                        $stats = "NonAktif";
                    }
                    
                    return $stats;
                })
                ->addColumn('action', function($row){
                    $btn = '
                        <button type="button" class="btn btn-warning btn-xs Edit" data-toggle="tooltip" data-id="'.$row->id.'"><i class="fa fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-xs Delete" data-toggle="tooltip" data-id="'.$row->id.'"><i class="fa fa-trash"></i></button>
                        ';
                    return $btn;
                })
                ->addColumn('icon', function($row){
                    $icon = '
                            <a href="service/public/uploads/category/'.$row->category_icon.'" data-toggle="lightbox" data-gallery="gallery">
                                <img src="service/public/uploads/category/'.$row->category_icon.'" style="max-width: 100px;">
                            </a>
                        ';

                    return $icon;
                })
                ->rawColumns(['action','icon','cekstatus'])
                ->make(true);
    }

    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $data = array(
            'category_name' => $request->category_name,
            'id_variasi' => $request->id_variasi,
            'status' => $request->status
        );

        if($request->id != ""){
            if($request->obj_category_icon != 0){

                if ($request->file('category_icon') != null) {
                    request()->validate([
                        'category_icon' => 'mimes:png,jpg,jpeg'
                    ]);

                    $getdata = DB::table('master_category')->where('id',$request->id)->first();
                    File::delete('service/public/uploads/category/'.$getdata->category_icon);

                    $filedata_ktp = $request->file('category_icon')->getClientOriginalName();
                    $extension_ktp = $request->file('category_icon')->getClientOriginalExtension();
                    $newfiledata_ktp = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata_ktp, PATHINFO_FILENAME)) . '.' . $extension_ktp;
                    $request->file('category_icon')->move('service/public/uploads/category/', $newfiledata_ktp);
                    $data['category_icon'] = $newfiledata_ktp;
                }

            }
        }else{
            if($request->obj_category_icon != 0){

                if ($request->file('category_icon') != null) {
                    request()->validate([
                        'category_icon' => 'mimes:png,jpg,jpeg'
                    ]);

                    $filedata_ktp = $request->file('category_icon')->getClientOriginalName();
                    $extension_ktp = $request->file('category_icon')->getClientOriginalExtension();
                    $newfiledata_ktp = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata_ktp, PATHINFO_FILENAME)) . '.' . $extension_ktp;
                    $request->file('category_icon')->move('service/public/uploads/category/', $newfiledata_ktp);
                    $data['category_icon'] = $newfiledata_ktp;
                }

            }
        }

        $insert = Kategori::updateOrCreate(
            [
                'id' => $request->id
            ],
            $data
        );

        return response()->json(['status' => $data]);

    }

    public function edit($id)
    {
        $data = Kategori::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {   
        /*$getdata = DB::table('master_category')->where('id',$id)->first();
        File::delete('service/public/uploads/category/'.$getdata->category_icon);
        Kategori::find($id)->delete();*/
        $data = array(
            'isdelete'  => 1
        );
        Kategori::whereId($id)->update($data);
        return response()->json(['success'=>'Deleted successfully.']);
    }

    public function getVariasi()
    {
        $data = Variasi::orderby('name','asc')->get();
        return response()->json($data);
    }
}
