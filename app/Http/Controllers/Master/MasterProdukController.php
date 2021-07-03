<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use App\Models\Master\Brand;
use App\Models\Master\Kategori;
use File;
use Auth;
use DB;
use Session;
use DataTables;
use Validator; 

class MasterProdukController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $keyword = "";
        $title = 'Master Produk';
        $kategori = Kategori::orderby('category_name','asc')->get();
        $brand = Brand::orderby('brand_name','asc')->get();
        $data = DB::table('master_produk')
                    ->select(
                        'master_produk.id',
                        'master_produk.product_name',
                        'master_produk.product_code',
                        'master_produk.price',
                        'master_produk.stock',
                        'master_produk.variasi',
                        'master_produk.image1',
                        'master_produk.status'
                    )
                    /*->leftjoin('master_category','master_category.id','=','master_produk.category_code')
                    ->leftjoin('master_brand','master_brand.id','=','master_produk.brand_code')*/
                    ->orderby('master_produk.product_name')
                    ->where('isdelete',0)
                    ->paginate(12);

        return view('admin.produk.index', compact('title','kategori','brand','data','keyword'));
    }

    public function scopedata(Request $request){
        $keyword = $request->keyword;
        $title = 'Pencarian Master Produk';
        $kategori = Kategori::orderby('category_name','asc')->get();
        $brand = Brand::orderby('brand_name','asc')->get();
        $data = DB::table('master_produk')
                    ->select(
                        'master_produk.id',
                        'master_produk.product_name',
                        'master_produk.product_code',
                        'master_produk.price',
                        'master_produk.stock',
                        'master_produk.variasi',
                        'master_produk.image1',
                        'master_produk.status'
                    )
                    /*->leftjoin('master_category','master_category.id','=','master_produk.category_code')
                    ->leftjoin('master_brand','master_brand.id','=','master_produk.brand_code')*/
                    ->where('master_produk.product_name','LIKE','%'.$request->keyword.'%')
                    ->orwhere('master_produk.product_code','LIKE','%'.$request->keyword.'%')
                    ->orwhere('master_produk.price','LIKE','%'.$request->keyword.'%')
                    ->orwhere('master_produk.stock','LIKE','%'.$request->keyword.'%')
                    ->orderby('master_produk.product_name')
                    ->where('isdelete',0)
                    ->get();

        return view('admin.produk.search', compact('title','kategori','brand','data','keyword'));
    }

    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $data = array(
            'product_code' => $request->product_code,
            'product_name' => $request->product_name,
            'brand_code' => $request->brand_code,
            'category_code' => $request->category_code,
            'desc' => $request->desc,
            'price' => $request->price,
            'stock' => $request->stock,
            'variasi' => $request->variasi,
            'status' => $request->status
        );

        if($request->id != ""){
            if($request->obj_image1 != 0){
                if ($request->file('image1') != null) {
                    request()->validate([
                        'image1' => 'mimes:png,jpg,jpeg'
                    ]);

                    $getdata = Produk::find($request->id);
                    File::delete('service/public/uploads/produk/'.$getdata->image1);

                    $filedata1 = $request->file('image1')->getClientOriginalName();
                    $extension1 = $request->file('image1')->getClientOriginalExtension();
                    $newfiledata1 = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata1, PATHINFO_FILENAME)) . '.' . $extension1;
                    $request->file('image1')->move('service/public/uploads/produk/', $newfiledata1);
                    $data['image1'] = $newfiledata1;
                }
            }

            if($request->obj_image2 != 0){
                if ($request->file('image2') != null) {
                    request()->validate([
                        'image2' => 'mimes:png,jpg,jpeg'
                    ]);

                    $getdata = Produk::find($request->id);
                    File::delete('service/public/uploads/produk/'.$getdata->image2);

                    $filedata2 = $request->file('image2')->getClientOriginalName();
                    $extension2 = $request->file('image2')->getClientOriginalExtension();
                    $newfiledata2 = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata2, PATHINFO_FILENAME)) . '.' . $extension2;
                    $request->file('image2')->move('service/public/uploads/produk/', $newfiledata2);
                    $data['image2'] = $newfiledata2;
                }
            }

            if($request->obj_image3 != 0){
                if ($request->file('image3') != null) {
                    request()->validate([
                        'image3' => 'mimes:png,jpg,jpeg'
                    ]);

                    $getdata = Produk::find($request->id);
                    File::delete('service/public/uploads/produk/'.$getdata->image3);

                    $filedata3 = $request->file('image3')->getClientOriginalName();
                    $extension3 = $request->file('image3')->getClientOriginalExtension();
                    $newfiledata3 = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata3, PATHINFO_FILENAME)) . '.' . $extension3;
                    $request->file('image3')->move('service/public/uploads/produk/', $newfiledata3);
                    $data['image3'] = $newfiledata3;
                }
            }

            if($request->obj_image4 != 0){
                if ($request->file('image4') != null) {
                    request()->validate([
                        'image4' => 'mimes:png,jpg,jpeg'
                    ]);

                    $getdata = Produk::find($request->id);
                    File::delete('service/public/uploads/produk/'.$getdata->image4);

                    $filedata4 = $request->file('image4')->getClientOriginalName();
                    $extension4 = $request->file('image4')->getClientOriginalExtension();
                    $newfiledata4 = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata4, PATHINFO_FILENAME)) . '.' . $extension4;
                    $request->file('image4')->move('service/public/uploads/produk/', $newfiledata4);
                    $data['image4'] = $newfiledata4;
                }
            }

            if($request->obj_image5 != 0){
                if ($request->file('image5') != null) {
                    request()->validate([
                        'image5' => 'mimes:png,jpg,jpeg'
                    ]);

                    $getdata = Produk::find($request->id);
                    File::delete('service/public/uploads/produk/'.$getdata->image5);

                    $filedata5 = $request->file('image5')->getClientOriginalName();
                    $extension5 = $request->file('image5')->getClientOriginalExtension();
                    $newfiledata5 = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata5, PATHINFO_FILENAME)) . '.' . $extension5;
                    $request->file('image5')->move('service/public/uploads/produk/', $newfiledata5);
                    $data['image5'] = $newfiledata5;
                }
            }
        }else{
            if($request->obj_image1 != 0){
                if ($request->file('image1') != null) {
                    request()->validate([
                        'image1' => 'mimes:png,jpg,jpeg'
                    ]);

                    $filedata1 = $request->file('image1')->getClientOriginalName();
                    $extension1 = $request->file('image1')->getClientOriginalExtension();
                    $newfiledata1 = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata1, PATHINFO_FILENAME)) . '.' . $extension1;
                    $request->file('image1')->move('service/public/uploads/produk/', $newfiledata1);
                    $data['image1'] = $newfiledata1;
                }
            }

            if($request->obj_image2 != 0){
                if ($request->file('image2') != null) {
                    request()->validate([
                        'image2' => 'mimes:png,jpg,jpeg'
                    ]);

                    $filedata2 = $request->file('image2')->getClientOriginalName();
                    $extension2 = $request->file('image2')->getClientOriginalExtension();
                    $newfiledata2 = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata2, PATHINFO_FILENAME)) . '.' . $extension2;
                    $request->file('image2')->move('service/public/uploads/produk/', $newfiledata2);
                    $data['image2'] = $newfiledata2;
                }
            }

            if($request->obj_image3 != 0){
                if ($request->file('image3') != null) {
                    request()->validate([
                        'image3' => 'mimes:png,jpg,jpeg'
                    ]);

                    $filedata3 = $request->file('image3')->getClientOriginalName();
                    $extension3 = $request->file('image3')->getClientOriginalExtension();
                    $newfiledata3 = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata3, PATHINFO_FILENAME)) . '.' . $extension3;
                    $request->file('image3')->move('service/public/uploads/produk/', $newfiledata3);
                    $data['image3'] = $newfiledata3;
                }
            }

            if($request->obj_image4 != 0){
                if ($request->file('image4') != null) {
                    request()->validate([
                        'image4' => 'mimes:png,jpg,jpeg'
                    ]);

                    $filedata4 = $request->file('image4')->getClientOriginalName();
                    $extension4 = $request->file('image4')->getClientOriginalExtension();
                    $newfiledata4 = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata4, PATHINFO_FILENAME)) . '.' . $extension4;
                    $request->file('image4')->move('service/public/uploads/produk/', $newfiledata4);
                    $data['image4'] = $newfiledata4;
                }
            }

            if($request->obj_image5 != 0){
                if ($request->file('image5') != null) {
                    request()->validate([
                        'image5' => 'mimes:png,jpg,jpeg'
                    ]);

                    $filedata5 = $request->file('image5')->getClientOriginalName();
                    $extension5 = $request->file('image5')->getClientOriginalExtension();
                    $newfiledata5 = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata5, PATHINFO_FILENAME)) . '.' . $extension5;
                    $request->file('image5')->move('service/public/uploads/produk/', $newfiledata5);
                    $data['image5'] = $newfiledata5;
                }
            }
        }

        $insert = Produk::updateOrCreate(
            [
                'id' => $request->id
            ],
            $data
        );

        return response()->json(['status' => $data]);

    }

    public function edit($id)
    {
        $data = Produk::find($id);
        return response()->json($data);
    }

    public function show($id){
        $data = DB::table('master_produk')
                    ->select(
                        'master_produk.*',
                        'master_category.category_name',
                        'master_brand.brand_name'
                    )
                    ->leftjoin('master_category','master_category.id','=','master_produk.category_code')
                    ->leftjoin('master_brand','master_brand.id','=','master_produk.brand_code')
                    ->where('master_produk.id',$id)
                    ->first();

        return response()->json($data);

    }

    public function destroy($id)
    {   
        /*$data = Produk::find($id);
        File::delete('service/public/uploads/produk/'.$data->image1);
        File::delete('service/public/uploads/produk/'.$data->image2);
        File::delete('service/public/uploads/produk/'.$data->image3);
        File::delete('service/public/uploads/produk/'.$data->image4);
        File::delete('service/public/uploads/produk/'.$data->image5);
        Produk::find($id)->delete();*/
        $data = array(
            'isdelete'  => 1
        );
        Produk::whereId($id)->update($data);
        return response()->json(['success'=>'Deleted successfully.']);
    }

    public function checkCodeProduct(Request $request){
        $data = Produk::where('product_code',$request->product_code)->get();
        return response()->json(count($data));
    }
}
