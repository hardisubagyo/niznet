<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Session;
use DataTables;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function PenjualanPerKategori()
    {
        $getidToko = DB::table('master_toko')->select('id')->where('id_user',Auth::user()->id)->first();
        if(count((array)$getidToko) > 0){
            $idtoko = $getidToko->id;
        }else{
            $idtoko = 99999999999;
        }

        if(Auth::user()->id_level == 1){
        	$get = DB::table('master_produk as p')
        			->select(
        				'k.category_name',
        				DB::raw('count(k.category_name) as total')
        			)
        			->join('master_category as k', 'k.id', 'p.category_code')
        			->join('tr_checkout as out','out.product_code','p.product_code')
        			->groupBy('k.category_name')
        			->orderBy('total','desc')
        			->limit(10)
        			->get();
        }else{
            $get = DB::table('master_produk as p')
                    ->select(
                        'k.category_name',
                        DB::raw('count(k.category_name) as total')
                    )
                    ->join('master_category as k', 'k.id', 'p.category_code')
                    ->join('tr_checkout as out','out.product_code','p.product_code')
                    ->join('master_trx','master_trx.checkout_code','=','out.checkout_code')
                    ->where('master_trx.id_toko',$idtoko)
                    ->groupBy('k.category_name')
                    ->orderBy('total','desc')
                    ->limit(10)
                    ->get();
        }

    	return response()->json($get);
    }

    public function PenjualanPerMerk()
    {

        $getidToko = DB::table('master_toko')->select('id')->where('id_user',Auth::user()->id)->first();
        if(count((array)$getidToko) > 0){
            $idtoko = $getidToko->id;
        }else{
            $idtoko = 99999999999;
        }

        if(Auth::user()->id_level == 1){
            $get = DB::table('master_produk as p')
    			->select(
    				'k.brand_name',
    				DB::raw('count(k.brand_name) as total')
    			)
    			->join('master_brand as k', 'k.id', 'p.brand_code')
    			->join('tr_checkout as out','out.product_code','p.product_code')
    			->groupBy('k.brand_name')
    			->orderBy('total','desc')
    			->limit(10)
    			->get();
        }else{
            $get = DB::table('master_produk as p')
                ->select(
                    'k.brand_name',
                    DB::raw('count(k.brand_name) as total')
                )
                ->join('master_brand as k', 'k.id', 'p.brand_code')
                ->join('tr_checkout as out','out.product_code','p.product_code')
                ->join('master_trx','master_trx.checkout_code','=','out.checkout_code')
                ->where('master_trx.id_toko',$idtoko)
                ->groupBy('k.brand_name')
                ->orderBy('total','desc')
                ->limit(10)
                ->get();
        }

    	return response()->json($get);
    }

    public function kurir()
    {
        $data = DB::table('master_kurir')
                ->select(
                    'users.id',
                    'users.name',
                    DB::raw('COUNT(master_trx.id_kurir) as total')
                )
                ->join('users','users.id','=','master_kurir.id_users')
                ->leftjoin('master_trx','master_trx.id_kurir','=','master_kurir.id_users')
                ->groupBy('users.name','users.id')
                ->orderBy('total','desc')
                ->get();

        return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '
                        <button type="button" class="btn btn-success btn-xs View" data-toggle="tooltip" data-id="'.$row->id.'"><i class="fas fa-info"></i></button>
                        ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function detailKurir($id)
    {
        $data = DB::table('master_trx')
                ->select(
                    'users.name',
                    'master_trx.checkout_code',
                    'master_trx.status',
                    'master_trx.created_at',
                    'master_trx.total_price'
                )
                ->join('users','users.id','=','master_trx.id_kurir')
                ->where('master_trx.id_kurir',$id)
                ->get();

        return response()->json($data);
    }

    public function DetailKurirPesanan($id)
    {
        $data = DB::table('tr_checkout as c')
                    ->select(
                        'p.product_name',
                        'c.price',
                        'c.quantity',
                        DB::raw('c.quantity * c.price AS total'),
                        'k.category_name',
                        'b.brand_name'
                    )
                    ->join('master_produk as p', 'p.product_code','=','c.product_code')
                    ->leftjoin('master_category as k', 'k.id','=','p.category_code')
                    ->leftjoin('master_brand as b', 'b.id','=','p.brand_code')
                    ->where('c.checkout_code',$id)
                    ->get();

        $total = DB::table('tr_checkout as c')
                    ->select(
                        DB::raw('SUM(c.quantity * c.price) AS total')
                    )
                    ->where('c.checkout_code',$id)
                    ->get();

        $output = array(
            'list' => $data,
            'total' => $total
        );

        return response()->json($output);
    }
}
