<?php

namespace App\Http\Controllers;

use App\AtcList;
use App\FrequentlyUsedAtc;
use App\FrequentlyUsedCoa;
use App\ChartofAccountsOld;
use Illuminate\Http\Request;

class FrequentlyUsedItemsController extends Controller
{
	public function index()
	{
			$fui_atc = FrequentlyUsedAtc::where('user_id', '=', \Auth::user()->creatorId())->get();
			$fui_coa = FrequentlyUsedCoa::where('user_id', '=', \Auth::user()->creatorId())->get();

			$atc_lists = AtcList::all();
			$chartofaccounts = ChartofAccountsOld::all();
			return view('fui.index',compact('atc_lists','chartofaccounts','fui_coa'))->with('fui_atc', $fui_atc);
	}

	public function add_atc(Request $request)
	{
			$validator = \Validator::make(
				$request->all(), [
					'atc_id' => 'required',
				]
			);

			FrequentlyUsedAtc::where('user_id',\Auth::user()->id)->delete();

			if($request->atc_id != null)
			{
				for($i = 0; $i < count($request->atc_id); $i++)
				{
					$FrequentlyUsedAtc = new FrequentlyUsedAtc;
					$FrequentlyUsedAtc->user_id = \Auth::user()->id;
					$FrequentlyUsedAtc->atc_list_id = $request->atc_id[$i];
					$FrequentlyUsedAtc->save();
				}
			}
			return redirect()->back()->with('success', __('ATC successfully updated.'));
	}

	public function add_coa(Request $request)
	{
			$validator = \Validator::make(
				$request->all(), [
					'coa_id' => 'required',
				]
			);

			FrequentlyUsedCoa::where('user_id',\Auth::user()->id)->delete();
			
			if($request->coa_id != null)
			{
				for($i = 0; $i < count($request->coa_id); $i++)
				{
					$FrequentlyUsedAtc = new FrequentlyUsedCoa;
					$FrequentlyUsedAtc->user_id = \Auth::user()->id;
					$FrequentlyUsedAtc->chart_of_accounts_id = $request->coa_id[$i];
					$FrequentlyUsedAtc->save();
				}
			}
			return redirect()->back()->with('success', __('COA successfully updated.'));
	}
}
