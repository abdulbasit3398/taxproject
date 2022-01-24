<?php

namespace App\Http\Controllers;

use App\Reference;
use Illuminate\Http\Request;

class ReferenceController extends Controller
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
      $reference = Reference::all();

      return view('reference.index')->with('references', $reference);
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

      return view('reference.create');
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
         'reference_code' => 'required|max:20',
         'reference_title' => 'required',
       ]
     );

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->back()->with('error', $messages->first());
      }
      $prev_reference = Reference::where('reference_code',$request->reference_code)->first();
      if($prev_reference)
      {
        return redirect()->back()->with('error', 'Reference Code is already exists.');
      }
      $reference = new Reference;
      $reference->reference_code = $request->reference_code;
      $reference->reference_title = $request->reference_title;
      $reference->save();

      return redirect()->route('reference.index')->with('success', __('Reference successfully created.'));
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
      $reference = Reference::find($id);
      return view('reference.edit', compact('reference'));
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
         'reference_code' => 'required|max:20',
         'reference_title' => 'required',
       ]
     );

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->back()->with('error', $messages->first());
      }

      $prev_reference = Reference::where('reference_code',$request->reference_code)->first();
      if($prev_reference && $prev_reference->id != $id)
      {
        return redirect()->back()->with('error', 'Reference Code is already exists.');
      }
      
      $reference = Reference::find($id);
      $reference->reference_code = $request->reference_code;
      $reference->reference_title = $request->reference_title;
      $reference->save();

      return redirect()->back()->with('success', __('Reference successfully updated.'));
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
      Reference::find($id)->delete();
      return redirect()->back()->with('success', __('Reference successfully deleted.'));
    }
    else{
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }
}
