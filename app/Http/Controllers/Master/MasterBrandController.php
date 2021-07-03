<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Brand;
use File;
use Auth;
use DB;
use Session;
use DataTables;
use Validator; 

class MasterBrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$title = 'Master Merk';

        return view('admin.merk.index', compact('title'));
    }

    public function scopedata(){
        $data = Brand::where('isdelete',0)->get();

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
                            <a href="service/public/uploads/brand/'.$row->brand_icon.'" data-toggle="lightbox" data-gallery="gallery">
                                <img src="service/public/uploads/brand/'.$row->brand_icon.'" style="max-width: 100px;">
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
            'brand_name' => $request->brand_name,
            'status' => $request->status
        );

        if($request->id != ""){
            if($request->obj_brand_icon != 0){

                if ($request->file('brand_icon') != null) {
                    request()->validate([
                        'brand_icon' => 'mimes:png,jpg,jpeg'
                    ]);

                    $getdata = DB::table('master_brand')->where('id',$request->id)->first();
                    File::delete('service/public/uploads/brand/'.$getdata->brand_icon);

                    $filedata_ktp = $request->file('brand_icon')->getClientOriginalName();
                    $extension_ktp = $request->file('brand_icon')->getClientOriginalExtension();
                    $newfiledata_ktp = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata_ktp, PATHINFO_FILENAME)) . '.' . $extension_ktp;
                    $request->file('brand_icon')->move('service/public/uploads/brand/', $newfiledata_ktp);
                    $data['brand_icon'] = $newfiledata_ktp;
                }

            }
        }else{
            if($request->obj_brand_icon != 0){

                if ($request->file('brand_icon') != null) {
                    request()->validate([
                        'brand_icon' => 'mimes:png,jpg,jpeg'
                    ]);

                    $filedata_ktp = $request->file('brand_icon')->getClientOriginalName();
                    $extension_ktp = $request->file('brand_icon')->getClientOriginalExtension();
                    $newfiledata_ktp = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata_ktp, PATHINFO_FILENAME)) . '.' . $extension_ktp;
                    $request->file('brand_icon')->move('service/public/uploads/brand/', $newfiledata_ktp);
                    $data['brand_icon'] = $newfiledata_ktp;
                }

            }
        }

        $insert = Brand::updateOrCreate(
            [
                'id' => $request->id
            ],
            $data
        );

        return response()->json(['status' => $data]);

    }

    public function edit($id)
    {
        $data = Brand::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        /*$getdata = DB::table('master_brand')->where('id',$id)->first();
        File::delete('service/public/uploads/brand/'.$getdata->brand_icon);
        Brand::find($id)->delete();*/

        $data = array(
            'isdelete'  => 1
        );
        Brand::whereId($id)->update($data);

        return response()->json(['success'=>'Deleted successfully.']);
    }
}
