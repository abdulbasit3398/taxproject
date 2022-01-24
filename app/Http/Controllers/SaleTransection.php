<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use App\SalesTransection;
use App\SaleTransectionProduct;
use App\Imports\SalesTransectionImport;
use Illuminate\Http\Request;

class SaleTransection extends Controller
{
  public function index()
  {
    $sales = SalesTransection::where('user_id',Auth::id())->get();
    return view('salestransection.index',compact('sales'));
  }
  public function import_sale_transections()
  {
    return view('salestransection.import');
    
  }
  public function save_import_sale_transections(Request $request)
  {
    Excel::import(new SalesTransectionImport, request()->file('select_file'));
    return redirect()->back();
  }
  public function sale_vat_relief()
  {
    return view('salestransection.sale_vat_relief');
  }
}
