<?php

namespace App\Http\Controllers\Pesanan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Session;
use DataTables;
use Mail;

class PesananController extends Controller
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
        $title = 'Data Pesanan';

        return view('admin.pesanan.index', compact('title'));
    }

    public function scopedata(){
        $getidToko = DB::table('master_toko')->select('id')->where('id_user',Auth::user()->id)->first();
        if(count((array)$getidToko) > 0){
            $idtoko = $getidToko->id;
        }else{
            $idtoko = 99999999999;
        }

        if(Auth::user()->id_level == 1){
            $data = DB::table('master_trx')
                    ->select(
                        'master_trx.*',
                        'users.name',
                        'master_payment_method.nama as name_payment'
                    )
                    ->leftjoin('users','users.id','=','master_trx.users_id')
                    ->leftjoin('master_payment_method','master_payment_method.id','=','master_trx.id_payment_method')
                    ->orderBy('master_trx.created_at','desc')
                    ->get();
        }else{
            $data = DB::table('master_trx')
                    ->select(
                        'master_trx.*',
                        'users.name',
                        'master_payment_method.nama as name_payment'
                    )
                    ->leftjoin('users','users.id','=','master_trx.users_id')
                    ->leftjoin('master_payment_method','master_payment_method.id','=','master_trx.id_payment_method')
                    ->where('master_trx.id_toko',$idtoko)
                    ->orderBy('master_trx.created_at','desc')
                    ->get();
        }

        return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if(Auth::user()->id_level == 5){

                        if($row->status == '1'){
                            $btn = '';
                        }else{
                            $btn = '
                            <button type="button" class="btn btn-info btn-xs View" data-toggle="tooltip" data-id="'.$row->checkout_code.'"><i class="fas fa-info-circle"></i></button>
                            ';
                        }

                    }else{
                        $btn = '';
                    }
                    
                    return $btn;
                })
                ->addColumn('pemesan', function($row){
                    $get = DB::table('users')->where('id',$row->users_id)->first();
                    $pemesan = $get->name;
                    return $pemesan;
                })
                ->addColumn('toko', function($row){
                    $gets = DB::table('users')
                                ->select('users.name')
                                ->leftjoin('master_toko','master_toko.id_user','=','users.id')
                                ->where('master_toko.id',$row->id_toko)->first();
                    if(!empty($gets)){
                        $toko = $gets->name;
                    }else{
                        $toko = '';
                    }
                    
                    return $toko;
                })
                ->rawColumns(['action','pemesan','toko'])
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
        $data = DB::table('tr_checkout as c')
                    ->select(
                        'p.product_name',
                        'p.price',
                        'c.quantity',
                        DB::raw('c.price AS total'),
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
                        DB::raw('SUM(c.price) AS total')
                    )
                    ->where('c.checkout_code',$id)
                    ->get();

        $status = DB::table('master_trx as m')
                    ->select('m.*','u.name','u.telp')
                    ->where('m.checkout_code',$id)
                    ->leftjoin('users as u','u.id','=','m.users_id')
                    ->first();

        $output = array(
            'status' => $status,
            'list' => $data,
            'total' => $total
        );

        return response()->json($output);
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

    public function verifikasiPesanan(Request $request)
    {
        $data = array(
            'status'  => 1
        );

        $update = DB::table('master_trx')->where('checkout_code',$request->checkout_code)->update($data);

        return response()->json(['success'=>'Update successfully.','checkout_code' => $request->checkout_code]);
    }

    public function pilihKurir()
    {
        $kurir = DB::table('master_kurir')    
                    ->select(
                        'users.name',
                        'users.telp',
                        'users.id'
                    )
                    ->join('users','users.id','=','master_kurir.id_users')
                    ->where('master_kurir.status',0)
                    ->where('master_kurir.notif',0)
                    ->orderBy('users.name','asc')
                    ->get();

        return response()->json($kurir);
    }

    public function teruskanPesanan(Request $request){
        
        try{

            $getnoreference = DB::table('master_trx')
                                    ->select('*')
                                    ->where('no_reference', $request->no_reference)
                                    ->get();

            if(count($getnoreference) == 0){
                if($request->id_payment_method == '2'){
                    $data = array(
                        'status'  => 3,
                        'no_reference' => $request->no_reference
                    );
                }else{
                    $data = array(
                        'status'  => 3
                    );
                }

                $update = DB::table('master_trx')->where('checkout_code',$request->checkout_code)->update($data);
                
                $getemail = DB::table('master_trx')
                            ->select('users.email')
                            ->leftjoin('users','users.id','=','master_trx.users_id')
                            ->where('checkout_code',$request->checkout_code)
                            ->first();

                if(!empty($getemail->email)){

                    try{
                        Mail::send('isiemail', array('pesan' => 'Transaksi anda telah selesai, terimakasih telah menggunakan Niznet') , function($pesan) use($getemail){
                            $pesan->to($getemail->email,'Verifikasi')->subject('Notifikasi Pembelian');
                            $pesan->from(env('MAIL_USERNAME','happysellingorder@gmail.com'),'Niznet');
                        });
                    }
                    catch(\Exception $e){
                        return response()->json(['state' => 0,'success'=>'Update successfully.','checkout_code' => $request->checkout_code]);
                    }
                    
                }

                return response()->json(['state' => 0,'success'=>'Update successfully.','checkout_code' => $request->checkout_code]);
            }else{
                return response()->json(['state' => 1,'success'=>'Update successfully.','checkout_code' => $request->checkout_code]);
            }

        }catch (Exception $e){

            return response (['success' => $e->getMessage()]);

        }
    }

    public function viewstruk($id){
        $data = DB::table('master_trx')
                    ->select('*')
                    ->where('checkout_code',$id)
                    ->first();
        return response()->json($data);
    }
}
