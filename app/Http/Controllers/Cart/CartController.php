<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use Auth;
use DB;
use Session;
use DataTables;
use Validator; 

class CartController extends Controller
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
        $title = 'Data Keranjang';

        return view('admin.cart.index', compact('title'));
    }

    public function scopedata(){
        $data = DB::table('tr_cart')
                    ->select(
                        'users.id',
                        'users.name',
                        'users.email',
                        'users.address',
                        'users.telp'
                    )
                    ->join('users', 'users.id','=','tr_cart.id_user')
                    ->groupBy('users.id','users.name','users.email','users.address','users.telp')
                    ->orderBy('users.name','asc')
                    ->get();

        return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '
                        <button type="button" class="btn btn-info btn-xs View" data-toggle="tooltip" data-id="'.$row->id.'"><i class="fas fa-info-circle"></i></button>
                        ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
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
        $data = DB::table('tr_cart')
                ->select(
                    'tr_cart.quantity',
                    'users.name',
                    'master_produk.product_name',
                    'master_produk.price',
                    DB::raw('tr_cart.quantity * master_produk.price AS total')
                )
                ->join('master_produk','master_produk.id','=','tr_cart.id_product')
                ->join('users','users.id','=','tr_cart.id_user')
                ->where('users.id',$id)
                ->get();


        $total = DB::table('tr_cart')
                ->select(
                    DB::raw('SUM(tr_cart.quantity * master_produk.price) AS total')
                )
                ->join('master_produk','master_produk.id','=','tr_cart.id_product')
                ->join('users','users.id','=','tr_cart.id_user')
                ->where('users.id',$id)
                ->get();



        return response()->json(['list' => $data, 'total' => $total]);
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
}
