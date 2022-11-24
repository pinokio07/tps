<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GlbCompany;
use Str;
use DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {     
      $company = GlbCompany::first();

      return view('admin.index', compact(['company']));
    }

    public function update(Request $request)
    {
      $data = $request->validate([
        'GC_Name' => 'required',
        'GC_Address1' => 'required',
        'GC_RN_NKCountryCode' => 'required',
        'GC_City' => 'required',
        'GC_PostCode' => 'required',
        'GC_Phone' => 'required',
        'GC_RX_NKLocalCurrency' => 'required'
      ]);

      if($data){
        DB::beginTransaction();

        try {
          $company = GlbCompany::updateOrCreate($request->except(['_token', '_method']));
          
          DB::commit();
        } catch (\Throwable $th) {
          throw $th;
        }             

        if($request->hasFile('GC_Logo')){
          $ext = $request->file('GC_Logo')->getClientOriginalExtension();
  
          if(in_array(Str::lower($ext), getRestrictedExt())){
            return abort(403);
          }
  
          $name = Str::slug($company->GC_Code).'_'.round(microtime(true)).'.'.$ext;
          $request->file('GC_Logo')->move('img/companies/', $name);
          $company->GC_Logo = $name;
        }
        $company->save();
  
        return redirect('/administrator')->with('sukse', 'Update Company Success.');
      }
    }
}
