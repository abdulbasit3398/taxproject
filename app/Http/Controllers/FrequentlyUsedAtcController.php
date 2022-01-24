<?php

namespace App\Http\Controllers;

use App\AtcList;
use App\FrequentlyUsedAtc;
use Illuminate\Http\Request;

class FrequentlyUsedAtcController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    if(\Auth::user()->can('manage fui atc'))
    {
      $fui_atc = FrequentlyUsedAtc::where('user_id', '=', \Auth::user()->creatorId())->get();
      $atc_lists = AtcList::all();
      return view('fui_atc.index',compact('atc_lists'))->with('fui_atc', $fui_atc);
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
    if(\Auth::user()->can('manage fui atc'))
    {
      $validator = \Validator::make(
        $request->all(), [
         'atc_id' => 'required',
       ]
      );

      FrequentlyUsedAtc::where('user_id',\Auth::user()->id)->delete();
      
      for($i = 0; $i < count($request->atc_id); $i++)
      {
        $FrequentlyUsedAtc = new FrequentlyUsedAtc;
        $FrequentlyUsedAtc->user_id = \Auth::user()->id;
        $FrequentlyUsedAtc->atc_list_id = $request->atc_id[$i];
        $FrequentlyUsedAtc->save();
      }

      return redirect()->back()->with('success', __('ATC successfully added.'));
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
      //
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
      //
  }
}
