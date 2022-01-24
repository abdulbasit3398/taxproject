<?php

namespace App\Http\Controllers;

use App\RdoTable;
use Auth;
use Illuminate\Http\Request;

class RdoController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {

    if(\Auth::user()->type =='super admin')
    {
      $rdos = RdoTable::all();

      return view('rdo.index')->with('rdos', $rdos);
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    if(\Auth::user()->type =='super admin')
    {

      return view('rdo.create');
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if(\Auth::user()->type == 'super admin')
    {
      $validator = \Validator::make(
        $request->all(), [
         'rdo_code' => 'required|max:20',
         'rdo_location' => 'required',
       ]
     );

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->back()->with('error', $messages->first());
      }
      $prev_rdo = RdoTable::where('rdo_code',$request->rdo_code)->first();
      if($prev_rdo)
      {
        return redirect()->back()->with('error', 'RDO Code is already exists.');
      }
      $rdo_table = new RdoTable;
      $rdo_table->rdo_code = $request->rdo_code;
      $rdo_table->rdo_location = $request->rdo_location;
      $rdo_table->save();

      return redirect()->route('rdo.index')->with('success', __('RDO successfully created.'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
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
  public function edit($rdo_id)
  {
    if(\Auth::user()->type == 'super admin')
    {
      $rdo = RdoTable::find($rdo_id);
      return view('rdo.edit', compact('rdo'));
    }
    else
    {
      return response()->json(['error' => __('Permission denied.')], 401);
    }
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
    if(\Auth::user()->type == 'super admin')
    {
      $validator = \Validator::make(
        $request->all(), [
         'rdo_code' => 'required|max:20',
         'rdo_location' => 'required',
       ]
     );

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->back()->with('error', $messages->first());
      }

      $prev_rdo = RdoTable::where('rdo_code',$request->rdo_code)->first();
      if($prev_rdo && $prev_rdo->id != $id)
      {
        return redirect()->back()->with('error', 'RDO Code is already exists.');
      }
      
      $rdo = RdoTable::find($id);
      $rdo->rdo_code = $request->rdo_code;
      $rdo->rdo_location = $request->rdo_location;
      $rdo->save();

      return redirect()->back()->with('success', __('RDO successfully updated.'));
    }
    else
    {
      return response()->json(['error' => __('Permission denied.')], 401);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    if(\Auth::user()->type == 'super admin')
    {
      RdoTable::find($id)->delete();
      return redirect()->back()->with('success', __('RDO successfully deleted.'));
    }
    else{
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }
}
