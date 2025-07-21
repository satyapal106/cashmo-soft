<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DMTController extends Controller
{
    public function RemitterKYC(){
        $title ="Remitter KYC";
        return view('retailer.DMT.remitter- ekyc', compact('title'));
    }

    public function RegisterBeneficiary(){
          $title ="Register Beneficiary";
          return view('retailer.DMT.register-beneficiary', compact('title'));
    }
}
