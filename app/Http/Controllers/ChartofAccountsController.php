<?php

namespace App\Http\Controllers;

use App\ChartofAccountsOld as ChartofAccounts;
use Illuminate\Http\Request;

class ChartofAccountsController extends Controller
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
        $coas = ChartofAccounts::all();
        
        return view('chartOfAccount_old.index')->with('coas', $coas);
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
        $categories = ChartofAccounts::all()->groupBy('category');
        $debit_credit = ChartofAccounts::all()->groupBy('debit_credit');
        return view('chartOfAccount_old.create')->with('categories',$categories)->with('debit_credit',$debit_credit);
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
           'account_code' => 'required|max:20',
           'account_title' => 'required',
           'category' => 'required',
           'debit_credit' => 'required',
           ]
         );

        if($validator->fails())
        {
          $messages = $validator->getMessageBag();

          return redirect()->back()->with('error', $messages->first());
        }
        $prev_coa = ChartofAccounts::where('account_id',$request->account_code)->first();
        if($prev_coa)
        {
          return redirect()->back()->with('error', 'Account Id is already exists.');
        }
        $coa = new ChartofAccounts;
        $coa->account_id = $request->account_code;
        $coa->account_title = $request->account_title;
        $coa->category = $request->category;
        $coa->debit_credit = $request->debit_credit;
        $coa->save();

        return redirect()->route('coa.index')->with('success', __('COA successfully created.'));
      }
      else
      {
        return redirect()->back()->with('error', __('Permission denied.'));
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ChartofAccounts  $chartofAccounts
     * @return \Illuminate\Http\Response
     */
    public function show(ChartofAccounts $chartofAccounts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ChartofAccounts  $chartofAccounts
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      if(\Auth::user()->type == 'super admin')
      {
        $coa = ChartofAccounts::find($id);
        $categories = ChartofAccounts::all()->groupBy('category');
        $debit_credit = ChartofAccounts::all()->groupBy('debit_credit');
        return view('chartOfAccount_old.edit', compact('coa','categories','debit_credit'));
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
     * @param  \App\ChartofAccounts  $chartofAccounts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      if(\Auth::user()->type == 'super admin')
      {
        $validator = \Validator::make(
          $request->all(), [
           'account_code' => 'required|max:20',
           'account_title' => 'required',
           'category' => 'required',
           'debit_credit' => 'required',
          ]
        );

        if($validator->fails())
        {
          $messages = $validator->getMessageBag();

          return redirect()->back()->with('error', $messages->first());
        }

        $prev_coa = ChartofAccounts::where('account_id',$request->account_code)->first();
        if($prev_coa && $prev_coa->id != $id)
        {
          return redirect()->back()->with('error', 'Account Id is already exists.');
        }
        
        $coa = ChartofAccounts::find($id);
        $coa->account_id = $request->account_code;
        $coa->account_title = $request->account_title;
        $coa->category = $request->category;
        $coa->debit_credit = $request->debit_credit;
        $coa->save();

        return redirect()->back()->with('success', __('COA successfully updated.'));
      }
      else
      {
        return response()->json(['error' => __('Permission denied.')], 401);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ChartofAccounts  $chartofAccounts
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      if(\Auth::user()->type == 'super admin')
      {
        ChartofAccounts::find($id)->delete();
        return redirect()->back()->with('success', __('COA successfully deleted.'));
      }
      else{
        return redirect()->back()->with('error', __('Permission denied.'));
      }
    }
  }
