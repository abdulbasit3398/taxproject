<?php

namespace App\Http\Controllers;

use App\OrganisationType;
use Illuminate\Http\Request;

class OrganisationTypeController extends Controller
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
      $organisation_type = OrganisationType::all();

      return view('organisation_type.index')->with('organisation_type', $organisation_type);
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

      return view('organisation_type.create');
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
         'name' => 'required',
       ]
     );

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->back()->with('error', $messages->first());
      }
      $prev_organisation = OrganisationType::where('name',$request->name)->first();
      if($prev_organisation)
      {
        return redirect()->back()->with('error', 'Organisation Type is already exists.');
      }
      $organisation = new OrganisationType;
      $organisation->name = $request->name;
      $organisation->save();

      return redirect()->route('organisation-type.index')->with('success', __('Organisation Type successfully created.'));
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
      $organisation_type = OrganisationType::find($id);
      return view('organisation_type.edit', compact('organisation_type'));
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
         'name' => 'required',
       ]
     );

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->back()->with('error', $messages->first());
      }

      $prev_organisation = OrganisationType::where('name',$request->name)->first();
      if($prev_organisation && $prev_organisation->id != $id)
      {
        return redirect()->back()->with('error', 'Organisation Type is already exists.');
      }
      
      $organisation = OrganisationType::find($id);
      $organisation->name = $request->name;
      $organisation->save();

      return redirect()->back()->with('success', __('Organisation Type successfully updated.'));
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
      OrganisationType::find($id)->delete();
      return redirect()->back()->with('success', __('OrganisationType Type successfully deleted.'));
    }
    else{
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }
}
