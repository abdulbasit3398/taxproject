<?php

namespace App\Http\Controllers;

use App\ExpenseType;
use Illuminate\Http\Request;

class ExpenseTypeController extends Controller
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
      $expense_type = ExpenseType::all();

      return view('expense_type.index')->with('expense_type', $expense_type);
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

      return view('expense_type.create');
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
         'expense' => 'required',
       ]
     );

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->back()->with('error', $messages->first());
      }
      $prev_expense = ExpenseType::where('expense',$request->expense)->first();
      if($prev_expense)
      {
        return redirect()->back()->with('error', 'Expense Type is already exists.');
      }
      $expense = new ExpenseType;
      $expense->expense = $request->expense;
      $expense->save();

      return redirect()->route('expense_type.index')->with('success', __('Expense Type successfully created.'));
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
      $expense_type = ExpenseType::find($id);
      return view('expense_type.edit', compact('expense_type'));
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
         'expense' => 'required',
       ]
     );

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->back()->with('error', $messages->first());
      }

      $prev_expense = ExpenseType::where('expense',$request->expense)->first();
      if($prev_expense && $prev_expense->id != $id)
      {
        return redirect()->back()->with('error', 'Expense Type is already exists.');
      }
      
      $expense = ExpenseType::find($id);
      $expense->expense = $request->expense;
      $expense->save();

      return redirect()->back()->with('success', __('Expense Type successfully updated.'));
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
      ExpenseType::find($id)->delete();
      return redirect()->back()->with('success', __('Expense Type successfully deleted.'));
    }
    else{
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }
}
