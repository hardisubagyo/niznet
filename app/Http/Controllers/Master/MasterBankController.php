<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Bank;
use File;
use Auth;
use DB;
use Session;
use DataTables;
use Validator; 

class MasterBankController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$title = 'Master Bank';

        return view('admin.bank.index', compact('title'));
    }

    public function scopedata(){
        $data = Bank::all();

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
            'nama' => $request->nama,
            'status' => $request->status
        );

        $insert = Bank::updateOrCreate(
            [
                'id' => $request->id
            ],
            $data
        );

        return response()->json(['status' => $data]);

    }

    public function edit($id)
    {
        $data = Bank::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {   
        Bank::whereId($id)->delete();
        return response()->json(['success'=>'Deleted successfully.']);
    }
}
