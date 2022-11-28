<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RefBondedWarehouse;
use App\Exports\SetupExport;
use App\Imports\SetupImport;
use DataTables;
use Excel;

class SetupBondedWarehousesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
          $query = RefBondedWarehouse::query();

          return DataTables::eloquent($query)
                          ->addIndexColumn()                          
                          ->toJson();
        }

        $items = collect([
          'id' => 'id',
          'company_name' => 'Company Name',
          'tps_code' => 'TPS Code',
          'warehouse_code' => 'Warehouse Code',
          'address' => 'Address',
        ]);

        return view('pages.setup.indexall', compact(['items']));
    }

    public function download()
    {
        $model = '\App\Models\RefBondedWarehouse';
        return Excel::download(new SetupExport($model), 'warehouse.xlsx');
    }

    public function upload(Request $request)
    {
        $model = '\App\Models\RefBondedWarehouse';
        Excel::import(new SetupImport($model), $request->upload);
          
        return redirect('/setup/bonded-warehouses')->with('sukses', 'Upload Success.');
    }

    public function select2(Request $request)
    {
        $data = [];

        if($request->has('q') && $request->q != ''){
            $search = $request->q;
            $data = RefBondedWarehouse::where(function($query) use($search){
                                        $query->where('tps_code','LIKE',"%$search%")
                                              ->orWhere('company_name','LIKE',"%$search%")
                                              ->orWhere('warehouse_code','LIKE',"%$search%");
                                      })
                                      ->get();
        }

        return response()->json($data);
    }

}
