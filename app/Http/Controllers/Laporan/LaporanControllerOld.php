<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;
use Auth;
use DB;
use Session;
use DataTables;
use Validator; 

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Laporan';
        $toko = DB::table('master_toko')
                    ->select(
                        'users.name',
                        'master_toko.*'
                    )
                    ->leftjoin('users','users.id','=','master_toko.id_user')
                    ->get();

        return view('admin.laporan.index', compact('title','toko'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function periode(Request $request)
    {
        $date = explode(" - ", $request->periode);

        if(Auth::user()->id_level == 1){

            if($request->toko == '0'){
                $get = DB::table('tr_checkout as c')
                    ->select(
                        'p.product_name',
                        'p.price',
                        'c.quantity',
                        DB::raw('c.quantity * p.price AS total'),
                        'k.category_name',
                        'b.brand_name',
                        'u.name',
                        'c.created_at',
                        'master_trx.status',
                        'tk.name as namatoko'
                    )
                    ->join('master_produk as p', 'p.product_code','=','c.product_code')
                    ->leftjoin('master_category as k', 'k.id','=','p.category_code')
                    ->leftjoin('master_brand as b', 'b.id','=','p.brand_code')
                    ->leftjoin('users as u', 'u.id','=','c.users_id')
                    ->join('master_trx','master_trx.checkout_code','=','c.checkout_code')
                    ->leftjoin('master_toko as mt', 'mt.id','=','master_trx.id_toko')
                    ->leftjoin('users as tk', 'tk.id','=','mt.id_user')
                    ->whereRaw('(c.created_at >= ? AND c.created_at <= ?)',[$date[0]." 00:00:00", $date[1]." 23:59:59"])
                    ->orderby('c.created_at','asc')
                    ->get();

                $total = DB::table('tr_checkout as c')
                    ->select(
                        DB::raw('SUM(c.price) AS total')
                    )
                    ->join('master_trx','master_trx.checkout_code','=','c.checkout_code')
                    ->whereRaw('(c.created_at >= ? AND c.created_at <= ?)',[$date[0]." 00:00:00", $date[1]." 23:59:59"])
                    ->first();
            }else{
                $get = DB::table('tr_checkout as c')
                    ->select(
                        'p.product_name',
                        'p.price',
                        'c.quantity',
                        DB::raw('c.quantity * p.price AS total'),
                        'k.category_name',
                        'b.brand_name',
                        'u.name',
                        'c.created_at',
                        'master_trx.status',
                        'tk.name as namatoko'
                    )
                    ->join('master_produk as p', 'p.product_code','=','c.product_code')
                    ->leftjoin('master_category as k', 'k.id','=','p.category_code')
                    ->leftjoin('master_brand as b', 'b.id','=','p.brand_code')
                    ->leftjoin('users as u', 'u.id','=','c.users_id')
                    ->join('master_trx','master_trx.checkout_code','=','c.checkout_code')
                    ->leftjoin('master_toko as mt', 'mt.id','=','master_trx.id_toko')
                    ->leftjoin('users as tk', 'tk.id','=','mt.id_user')
                    ->whereRaw('(c.created_at >= ? AND c.created_at <= ?)',[$date[0]." 00:00:00", $date[1]." 23:59:59"])
                    ->where('master_trx.id_toko',$request->toko)
                    ->orderby('c.created_at','asc')
                    ->get();

                $total = DB::table('tr_checkout as c')
                    ->select(
                        DB::raw('SUM(c.price) AS total')
                    )
                    ->join('master_trx','master_trx.checkout_code','=','c.checkout_code')
                    ->whereRaw('(c.created_at >= ? AND c.created_at <= ?)',[$date[0]." 00:00:00", $date[1]." 23:59:59"])
                    ->where('master_trx.id_toko',$request->toko)
                    ->first();
            }

        }else{
            $getidToko = DB::table('master_toko')->select('id')->where('id_user',Auth::user()->id)->first();
            if(count((array)$getidToko) > 0){
                $idtoko = $getidToko->id;
            }else{
                $idtoko = 99999999999;
            }

            $get = DB::table('tr_checkout as c')
                    ->select(
                        'p.product_name',
                        'p.price',
                        'c.quantity',
                        DB::raw('c.quantity * p.price AS total'),
                        'k.category_name',
                        'b.brand_name',
                        'u.name',
                        'c.created_at',
                        'master_trx.status',
                        'tk.name as namatoko'
                    )
                    ->join('master_produk as p', 'p.product_code','=','c.product_code')
                    ->leftjoin('master_category as k', 'k.id','=','p.category_code')
                    ->leftjoin('master_brand as b', 'b.id','=','p.brand_code')
                    ->leftjoin('users as u', 'u.id','=','c.users_id')
                    ->join('master_trx','master_trx.checkout_code','=','c.checkout_code')
                    ->leftjoin('master_toko as mt', 'mt.id','=','master_trx.id_toko')
                    ->leftjoin('users as tk', 'tk.id','=','mt.id_user')
                    ->whereRaw('(c.created_at >= ? AND c.created_at <= ?)',[$date[0]." 00:00:00", $date[1]." 23:59:59"])
                    ->where('master_trx.id_toko',$idtoko)
                    ->orderby('c.created_at','asc')
                    ->get();

                $total = DB::table('tr_checkout as c')
                    ->select(
                        DB::raw('SUM(c.price) AS total')
                    )
                    ->join('master_trx','master_trx.checkout_code','=','c.checkout_code')
                    ->whereRaw('(c.created_at >= ? AND c.created_at <= ?)',[$date[0]." 00:00:00", $date[1]." 23:59:59"])
                    ->where('master_trx.id_toko',$idtoko)
                    ->first();
        }

        $output = array(
            "data" => $get,
            "total" => $total
        );

        return response()->json($output);
    }

    public function excel(Request $request)
    {
        $date = explode(" - ", $request->periode);
        $get = DB::table('tr_checkout as c')
                    ->select(
                        'p.product_name',
                        'c.price',
                        'c.quantity',
                        DB::raw('c.quantity * c.price AS total'),
                        'k.category_name',
                        'b.brand_name',
                        'u.name',
                        'c.created_at',
                        'master_trx.status'
                    )
                    ->join('master_produk as p', 'p.product_code','=','c.product_code')
                    ->leftjoin('master_category as k', 'k.id','=','p.category_code')
                    ->leftjoin('master_brand as b', 'b.id','=','p.brand_code')
                    ->leftjoin('users as u', 'u.id','=','c.users_id')
                    ->join('master_trx','master_trx.checkout_code','=','c.checkout_code')
                    ->whereRaw('(c.created_at >= ? AND c.created_at <= ?)',[$date[0]." 00:00:00", $date[1]." 23:59:59"])
                    ->orderby('c.created_at','asc')
                    ->get();

        $total = DB::table('tr_checkout as c')
                    ->select(
                        DB::raw('SUM(c.quantity * c.price) AS total')
                    )
                    ->whereRaw('(c.created_at >= ? AND c.created_at <= ?)',[$date[0]." 00:00:00", $date[1]." 23:59:59"])
                    ->first();

        return view('admin.laporan.periode', compact('get','total'));
    }
}
