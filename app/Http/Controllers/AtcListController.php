<?php

namespace App\Http\Controllers;

use App\AtcList;
use Illuminate\Http\Request;

class AtcListController extends Controller
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
      $atc_list = AtcList::all();

      return view('atc_list.index')->with('atcs', $atc_list);
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

      return view('atc_list.create');
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
         'atc_code' => 'required|max:20',
         'rate' => 'required',
         'description' => 'required',
       ]
     );

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->back()->with('error', $messages->first());
      }
      $prev_atc = AtcList::where('atc',$request->atc_code)->first();
      if($prev_atc)
      {
        return redirect()->back()->with('error', 'ATC Code is already exists.');
      }
      $atc = new AtcList;
      $atc->atc = $request->atc_code;
      $atc->description = $request->description;
      $atc->rate = $request->rate;
      $atc->save();

      return redirect()->route('atc.index')->with('success', __('ATC successfully created.'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\AtcList  $atcList
   * @return \Illuminate\Http\Response
   */
  public function show(AtcList $atcList)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\AtcList  $atcList
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    if(\Auth::user()->type == 'super admin')
    {
      $atc = AtcList::find($id);
      return view('atc_list.edit', compact('atc'));
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
   * @param  \App\AtcList  $atcList
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    if(\Auth::user()->type == 'super admin')
    {
      $validator = \Validator::make(
        $request->all(), [
         'atc_code' => 'required|max:20',
         'rate' => 'required',
         'description' => 'required',
       ]
     );

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->back()->with('error', $messages->first());
      }

      $prev_atc = AtcList::where('atc',$request->atc_code)->first();
      if($prev_atc && $prev_atc->id != $id)
      {
        return redirect()->back()->with('error', 'ATC Code is already exists.');
      }
      
      $atc = AtcList::find($id);
      $atc->atc = $request->atc_code;
      $atc->description = $request->description;
      $atc->rate = $request->rate;
      $atc->save();

      return redirect()->back()->with('success', __('ATC successfully updated.'));
    }
    else
    {
      return response()->json(['error' => __('Permission denied.')], 401);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\AtcList  $atcList
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    if(\Auth::user()->type == 'super admin')
    {
      AtcList::find($id)->delete();
      return redirect()->back()->with('success', __('ATC successfully deleted.'));
    }
    else{
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }
}
