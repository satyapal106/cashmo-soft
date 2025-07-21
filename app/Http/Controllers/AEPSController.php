<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AEPSController extends Controller
{
    
    public function CashWithdrawal(){
        $title = "Cash Withdrawal";
        return view('retailer.AEPS.cashwithdrawal', compact('title'));
    }
    public function BalanceEnquiry(){
        $title = "Balance Enquiry";
        return view('retailer.AEPS.balance-enquiry', compact('title'));
    }
    public function MiniStatement(){
        $title = "Mini Statement";
        return view('retailer.AEPS.mini-statement', compact('title'));
    }

    public function OnboardingWeb(){
        $title = "Onboarding Web";
        return view('retailer.AEPS.onboarding', compact('title'));
    }

    public function OnboardingTransaction(){
        $title = "Onboarding Transaction";
        return view('retailer.AEPS.onboarding-transaction', compact('title'));
    }

    public function OnboardStatus(){
        $title = "Onboard Status";
        return view('retailer.AEPS.onboard-status', compact('title'));
    }


       public function Enquiry(){
        $title = "Enquiry";
        return view('retailer.AEPS.enquiry', compact('title'));
    }
    public function Withdrawl(){
        $title = "Withdrawl";
        return view('retailer.AEPS.withdrawl', compact('title'));
    }
    public function MiniStatement1(){
        $title = "Mini Statement1";
        return view('retailer.AEPS.mini-statement1', compact('title'));
    }
    public function CashWithdraw(){
        $title = "CashWithdraw";
        return view('retailer.AEPS.cash-withdraw', compact('title'));
    }
    public function AadharPay(){
        $title = "Aadhar Pay";
        return view('retailer.AEPS.aadhar-pay', compact('title'));
    }
    public function GenerateOnboarding(){
        $title = "Generate Onboarding";
        return view('retailer.AEPS.generate-onboarding', compact('title'));
    }
    public function OnboardingStatusCheck(){
        $title = "Onboarding Status Check";
        return view('retailer.AEPS.onboard-status-check', compact('title'));
    }

    public function Bank2Registration(){
        $title = "Bank2 Registraion";
        return view('retailer.AEPS.bank2-registration', compact('title'));
    }
    public function Bank2Authenticate(){
        $title = "Bank2 Authenticate";
        return view('retailer.AEPS.bank2-authenticate', compact('title'));
    }
}
