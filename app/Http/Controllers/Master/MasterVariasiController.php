<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Variasi;
use File;
use Auth;
use DB;
use Session;
use DataTables;
use Validator; 

class MasterVariasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$title = 'Master Variasi';

        return view('admin.variasi.index', compact('title'));
    }

    public function scopedata(){
        $data = Variasi::all();

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
                ->rawColumns(['action','cekstatus'])
                ->make(true);
    }

    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $data = array(
            'name' => $request->name,
            'status' => $request->status
        );

        $insert = Variasi::updateOrCreate(
            [
                'id' => $request->id
            ],
            $data
        );

        return response()->json(['status' => $data]);

    }

    public function edit($id)
    {
        $data = Variasi::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {   
        Variasi::whereId($id)->delete();
        return response()->json(['success'=>'Deleted successfully.']);
    }
}
