<?php

namespace App\Http\Controllers;

use App\Imports\BanksImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Bank;


class BankController extends Controller
{
        
    public function importBanks(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new BanksImport, $request->file('file'));

        return back()->with('success', 'Banks imported successfully.');
    }


    public function BankList(){
        $title = "Bank";
        return view('admin.tools.bank-list', compact('title'));
    }

    public function getBankList()
    {
        $bank = Bank::orderBy('id', 'asc')->paginate(20);
        return response()->json($bank);
    }
}
