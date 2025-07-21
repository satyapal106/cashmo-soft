<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BBPSController extends Controller
{
    public function Electricity(){
        $title = "Electricity Bill";
        return view('retailer.bbps.electricity-bill-payment', compact('title'));
    }

    public function CylinderGasRecharge(){
        $title = "Cylinder Gas Recharge";
        return view('retailer.bbps.cylinder-gas-recharge', compact('title'));
    }

    public function InsurancePremiumPayment(){
        $title = "Insurance Premium Payment";
        return view('retailer.bbps.insurance-premium', compact('title'));
    }
}
