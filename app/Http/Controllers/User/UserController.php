<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\MasterKurir;
use File;
use Auth;
use DB;
use Session;
use DataTables;
use Validator; 

class UserController extends Controller
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
        $title = 'Pengguna';

        return view('admin.home.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function scopedata(){
        $data = DB::table('users')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.status',
                    'users.telp',
                    'users.id_level',
                    'users.address',
                    'users.photo',
                    'users.ktp',
                    'users.updated_at',
                    'users.created_at',
                    'master_level.level'
                )
                ->leftjoin('master_level','master_level.id','=','users.id_level')
                ->where('users.isdelete',0)
                ->get();
        return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '
                        <button type="button" class="btn btn-warning btn-xs Edit" data-toggle="tooltip" data-id="'.$row->id.'"><i class="fa fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-xs Delete" data-toggle="tooltip" data-id="'.$row->id.'"><i class="fa fa-trash"></i></button>
                        ';
                    return $btn;
                })
                ->addColumn('status', function($row){
                    if($row->status == 0){
                        $sts = '<small class="badge badge-danger">Nonaktif</small>';
                    }else{
                        $sts = '<small class="badge badge-success">Aktif</small>';
                    }
                    return $sts;
                })
                ->rawColumns(['action','status'])
                ->make(true);

    }

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
        date_default_timezone_set("Asia/Jakarta");

        if($request->id == ""){

            $data = array(
                'name' => $request->name,
                'email' => $request->email,
                'id_level' => intval($request->level),
                'address' => $request->address,
                'status' => intval($request->status),
                'telp' => $request->telp,
                'password' => $request->password,
                'password_confirmation' => $request->password
            );

            if($request->id != ""){
                if($request->obj_ktp != 0){
                    if ($request->file('ktp') != null) {
                        request()->validate([
                            'ktp' => 'mimes:png,jpg,jpeg'
                        ]);

                        $getdata = DB::table('users')->where('id',$request->id)->first();
                        File::delete('service/public/uploads/ktp/'.$getdata->ktp);

                        $filedata_ktp = $request->file('ktp')->getClientOriginalName();
                        $extension_ktp = $request->file('ktp')->getClientOriginalExtension();
                        $newfiledata_ktp = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata_ktp, PATHINFO_FILENAME)) . '.' . $extension_ktp;
                        $request->file('ktp')->move('service/public/uploads/ktp/', $newfiledata_ktp);
                        $data['ktp'] = $newfiledata_ktp;
                    }
                }

                if($request->obj_photo != 0){
                    if ($request->file('photo') != null) {
                        request()->validate([
                            'photo' => 'mimes:png,jpg,jpeg'
                        ]);

                        $getdata = DB::table('users')->where('id',$request->id)->first();
                        File::delete('service/public/uploads/photo/'.$getdata->photo);

                        $filedata = $request->file('photo')->getClientOriginalName();
                        $extension = $request->file('photo')->getClientOriginalExtension();
                        $newfiledata = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata, PATHINFO_FILENAME)) . '.' . $extension;
                        $request->file('photo')->move('service/public/uploads/photo/', $newfiledata);
                        $data['photo'] = $newfiledata;
                    }
                }
            }else{
                if($request->obj_ktp != 0){
                    if ($request->file('ktp') != null) {
                        request()->validate([
                            'ktp' => 'mimes:png,jpg,jpeg'
                        ]);

                        $filedata_ktp = $request->file('ktp')->getClientOriginalName();
                        $extension_ktp = $request->file('ktp')->getClientOriginalExtension();
                        $newfiledata_ktp = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata_ktp, PATHINFO_FILENAME)) . '.' . $extension_ktp;
                        $request->file('ktp')->move('service/public/uploads/ktp/', $newfiledata_ktp);
                        $data['ktp'] = $newfiledata_ktp;
                    }
                }

                if($request->obj_photo != 0){
                    if ($request->file('photo') != null) {
                        request()->validate([
                            'photo' => 'mimes:png,jpg,jpeg'
                        ]);

                        $filedata = $request->file('photo')->getClientOriginalName();
                        $extension = $request->file('photo')->getClientOriginalExtension();
                        $newfiledata = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata, PATHINFO_FILENAME)) . '.' . $extension;
                        $request->file('photo')->move('service/public/uploads/photo/', $newfiledata);
                        $data['photo'] = $newfiledata;
                    }
                }
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://www.service.happyselling.id/public/api/register',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            $get = json_decode(json_encode($response), true);
            $data = json_decode($get);

            return json_encode($data);
                
        }else{

            $data = array(
                'name' => $request->name,
                'email' => $request->email,
                'id_level' => intval($request->level),
                'address' => $request->address,
                'status' => intval($request->status),
                'telp' => $request->telp
            );

            if($request->id != ""){
                if($request->obj_ktp != 0){
                    if ($request->file('ktp') != null) {
                        request()->validate([
                            'ktp' => 'mimes:png,jpg,jpeg'
                        ]);

                        $getdata = DB::table('users')->where('id',$request->id)->first();
                        File::delete('service/public/uploads/ktp/'.$getdata->ktp);

                        $filedata_ktp = $request->file('ktp')->getClientOriginalName();
                        $extension_ktp = $request->file('ktp')->getClientOriginalExtension();
                        $newfiledata_ktp = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata_ktp, PATHINFO_FILENAME)) . '.' . $extension_ktp;
                        $request->file('ktp')->move('service/public/uploads/ktp/', $newfiledata_ktp);
                        $data['ktp'] = $newfiledata_ktp;
                    }
                }

                if($request->obj_photo != 0){
                    if ($request->file('photo') != null) {
                        request()->validate([
                            'photo' => 'mimes:png,jpg,jpeg'
                        ]);

                        $getdata = DB::table('users')->where('id',$request->id)->first();
                        File::delete('service/public/uploads/photo/'.$getdata->photo);

                        $filedata = $request->file('photo')->getClientOriginalName();
                        $extension = $request->file('photo')->getClientOriginalExtension();
                        $newfiledata = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata, PATHINFO_FILENAME)) . '.' . $extension;
                        $request->file('photo')->move('service/public/uploads/photo/', $newfiledata);
                        $data['photo'] = $newfiledata;
                    }
                }
            }else{
                if($request->obj_ktp != 0){
                    if ($request->file('ktp') != null) {
                        request()->validate([
                            'ktp' => 'mimes:png,jpg,jpeg'
                        ]);

                        $filedata_ktp = $request->file('ktp')->getClientOriginalName();
                        $extension_ktp = $request->file('ktp')->getClientOriginalExtension();
                        $newfiledata_ktp = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata_ktp, PATHINFO_FILENAME)) . '.' . $extension_ktp;
                        $request->file('ktp')->move('service/public/uploads/ktp/', $newfiledata_ktp);
                        $data['ktp'] = $newfiledata_ktp;
                    }
                }

                if($request->obj_photo != 0){
                    if ($request->file('photo') != null) {
                        request()->validate([
                            'photo' => 'mimes:png,jpg,jpeg'
                        ]);

                        $filedata = $request->file('photo')->getClientOriginalName();
                        $extension = $request->file('photo')->getClientOriginalExtension();
                        $newfiledata = date("YmdHis").'-'.str_replace(" ","_", pathinfo($filedata, PATHINFO_FILENAME)) . '.' . $extension;
                        $request->file('photo')->move('service/public/uploads/photo/', $newfiledata);
                        $data['photo'] = $newfiledata;
                    }
                }
            }

            if (!empty($request->password)) {
                $data['password'] = Hash::make($request->password);
            }            

            $insert = User::updateOrCreate(
                [
                    'id' => $request->id
                ],
                $data
            );

            return response()->json(['status' => $data]);
        }
        

        /*if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        if($request->level == 3){

            if(!empty($request->id)){
                $insert = User::updateOrCreate(
                    [
                        'id' => $request->id
                    ],
                    $data
                );

                return response()->json(['status' => $insert]);
            }else{
                $id = DB::table('users')->insertGetId($data);

                $kurir = MasterKurir::updateOrCreate(
                    [
                        'id' => $request->id
                    ],
                    [
                        'id_users' => $id
                    ]
                );

                return response()->json(['status' => $kurir]);

            }

        }else{
            $insert = User::updateOrCreate(
                [
                    'id' => $request->id
                ],
                $data
            );

            return response()->json(['status' => $insert]);
        }*/

        /*$data = array(
            'name' => $request->name,
            'email' => $request->email,
            'id_level' => $request->level,
            'password' => $request->password,
            'password_confirmation' => $request->password,
            'address' => $request->address,
            'status' => $request->status,
            'telp' => $request->telp,
            'ktp' => '123',
            'photo' => '123'
        );*/

        


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
        $data = DB::table('users')->where('id',$id)->first();
        return response()->json($data);
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
        /*$data = DB::table('users')->where('id',$id)->first();
        File::delete('service/public/uploads/photo/'.$data->photo);
        File::delete('service/public/uploads/ktp/'.$data->ktp);

        if($data->id_level == 3){
            DB::table('master_kurir')->where('id_users',$id)->delete();            
        }

        $delete = DB::table('users')->where('id',$id)->delete();*/

        $data = array(
            'isdelete'  => 1
        );
        User::whereId($id)->update($data);
        
        return response()->json(['success'=>'Deleted successfully.']);
    }

    public function cekEmail(Request $request)
    {
        $data = DB::table('users')->where('email',$request->email)->where('isdelete',0)->get();
        return response()->json(count($data));
    }
}
