<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use App\Models\Master\ProdukToko;
use App\Models\Master\Brand;
use App\Models\Master\Kategori;
use File;
use Auth;
use DB;
use Session;
use DataTables;
use Validator; 

class MasterProdukTokoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $keyword = "";
        $title = 'Master Produk '. Auth::user()->name;
        $produk = Produk::orderby('product_name','ASC')->get();
        $data = DB::table('master_produk_toko')
                    ->select(
                        'master_produk.id',
                        'master_produk.product_name',
                        'master_produk.product_code',
                        'master_produk.price',
                        'master_produk.stock',
                        'master_produk.variasi',
                        'master_produk.image1',
                        'master_produk_toko.status as status_produk_toko',
                        'master_produk_toko.stock as stock_produk_toko'
                    )
                    ->leftjoin('master_produk','master_produk.id','=','master_produk_toko.id_master_produk')
                    ->orderby('master_produk.product_name')
                    ->where('master_produk_toko.isdelete',0)
                    ->where('master_produk_toko.id_toko',Auth::user()->id)
                    ->paginate(12);

        return view('admin.produktoko.index', compact('title','data','keyword','produk'));
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
            'stock' => $request->stock,
            'status' => $request->status,
            'id_toko' => Auth::user()->id,
            'id_master_produk' => $request->produk
        );

        $insert = ProdukToko::updateOrCreate(
            [
                'id' => $request->id
            ],
            $data
        );

        return response()->json(['status' => $data]);

    }

    public function edit($id)
    {
        $data = ProdukToko::find($id);
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
        ProdukToko::whereId($id)->update($data);
        return response()->json(['success'=>'Deleted successfully.']);
    }

    public function checkCodeProduct(Request $request){
        $data = ProdukToko::where('product_code',$request->product_code)->get();
        return response()->json(count($data));
    }
}
