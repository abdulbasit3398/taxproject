<?php

namespace App\Http\Controllers;

use Auth;
use App\LineofBusiness;
use Illuminate\Http\Request;

class LineofBusinessController extends Controller
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
      $lob = LineofBusiness::all();

      return view('lob.index')->with('lobs', $lob);
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

      return view('lob.create');
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
         'business_code' => 'required|max:20',
         'business_title' => 'required',
       ]
     );

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->back()->with('error', $messages->first());
      }
      $prev_lob = LineofBusiness::where('business_code',$request->business_code)->first();
      if($prev_lob)
      {
        return redirect()->back()->with('error', 'Business Code is already exists.');
      }
      $lob = new LineofBusiness;
      $lob->business_code = $request->business_code;
      $lob->business_title = $request->business_title;
      $lob->save();

      return redirect()->route('lob.index')->with('success', __('LOB successfully created.'));
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
  public function edit($id)
  {
    if(\Auth::user()->type == 'super admin')
    {
      $lob = LineofBusiness::find($id);
      return view('lob.edit', compact('lob'));
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
         'business_code' => 'required|max:20',
         'business_title' => 'required',
       ]
     );

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->back()->with('error', $messages->first());
      }

      $prev_lob = LineofBusiness::where('business_code',$request->business_code)->first();
      if($prev_lob && $prev_lob->id != $id)
      {
        return redirect()->back()->with('error', 'Business Code is already exists.');
      }
      
      $lob = LineofBusiness::find($id);
      $lob->business_code = $request->business_code;
      $lob->business_title = $request->business_title;
      $lob->save();

      return redirect()->back()->with('success', __('LOB successfully updated.'));
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
      LineofBusiness::find($id)->delete();
      return redirect()->back()->with('success', __('LOB successfully deleted.'));
    }
    else{
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }
}
