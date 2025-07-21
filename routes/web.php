<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RetailerController;
use App\Http\Controllers\RechargePlanController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AgentTypeController;
use App\Http\Controllers\PlanCategoryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DTHplanController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\SlabController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BBPSController;
use App\Http\Controllers\AEPSController;
use App\Http\Controllers\DMTController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\MRSPayController;
use App\Http\Controllers\PaysprintRechargeController;
use App\Http\Controllers\ApiProviderController;
use App\Http\Controllers\ApiProviderMappingController;



Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cleared!";
});

Route::get('/signup', [UserController::class, 'Signup']);
Route::get('/', [UserController::class, 'Signin']);
Route::post('/retailer-signin', [UserController::class, 'postSignin']);
Route::post('/retailer-signup', [UserController::class, 'RetailerSignup']);
Route::get('/fetch-providers', [ServiceController::class, 'fetchAndStoreProviders']);
Route::get('/get-districts/{state_id}', [UserController::class, 'getDistricts']);

Route::group(['prefix' => 'retailer', 'middleware' => ['retailerAuth']], function () {
    Route::get('/dashboard', [UserController::class, 'dashboard']);
    Route::get('/logout', [UserController::class, "Logout"]);
    Route::get('/profile', [UserController::class, "Profile"]);
    //Wallet
    Route::get('/user-wallet', [WalletController::class, "UserWallet"]);
    Route::post('/add-balance', [WalletController::class, "addBalance"]);

    //use profile
    Route::get('/update-profile', [UserController::class, "UpdateProfile"]);
    Route::post('/change-password', [UserController::class, "changePassword"]);
    Route::get('/recharge', [UserController::class, 'recharge']);
    Route::post('/mobile-recharge-payment', [MRSPayController::class, 'MobileRechargePayment']);
    Route::get('/dth-recharge', [UserController::class, 'DTHrecharge']);
    Route::get('/get-recharge-plans/{operator_id}', [UserController::class, 'getRechargePlans']);
    Route::get('/get-dth-plans/{operator_id}', [UserController::class, 'getDthPlans']);
    Route::get('/electricity-bill-payment', [BBPSController::class, 'Electricity']);
    Route::get('/cylinder-gas-recharge', [BBPSController::class, 'CylinderGasRecharge']);
    Route::get('/insurance-premium-payment', [BBPSController::class, 'InsurancePremiumPayment']);

    //APES
    Route::get('/onboarding-web', [AEPSController::class, 'OnboardingWeb']);
    Route::get('/onboarding-transaction', [AEPSController::class, 'OnboardingTransaction']);
    Route::get('/onboard-status', [AEPSController::class, 'OnboardStatus']);
    Route::get('/bank2-registration', [AEPSController::class, 'Bank2Registration']);
    Route::get('/bank2-authenticate', [AEPSController::class, 'Bank2Authenticate']);
    Route::get('/enquiry', [AEPSController::class, 'Enquiry']);
    Route::get('/withdrawl', [AEPSController::class, 'Withdrawl']);
    Route::get('/mini-statement1', [AEPSController::class, 'MiniStatement1']);
    Route::get('/cash-withdraw', [AEPSController::class, 'CashWithdraw']);
    Route::get('/aadhar-pay', [AEPSController::class, 'AadharPay']);
    Route::get('/generate-onboarding', [AEPSController::class, 'GenerateOnboarding']);
    Route::get('/onboard-status-check', [AEPSController::class, 'OnboardingStatusCheck']);
    Route::get('/cash-withdrawal', [AEPSController::class, 'CashWithdrawal']);
    Route::get('/balance-enquiry', [AEPSController::class, 'BalanceEnquiry']);
    Route::get('/mini-statement', [AEPSController::class, 'MiniStatement']);
    Route::get('/remitter-kyc', [DMTController::class, 'RemitterKYC']);
    Route::get('/register-beneficiary', [DMTController::class, 'RegisterBeneficiary']);
       //paysprint Api
    Route::post('/onboard-merchant', [PaysprintRechargeController::class, 'onboardMerchant']);
});


Route::get('admin/login', [AdminController::class, 'login']);
Route::post('admin/login', [AdminController::class, 'loginPost']);

Route::group(['prefix' => 'admin', 'middleware' => ['adminAuth']], function () {
    Route::get('dashboard', [AdminController::class, 'dashboard']);
    Route::get('profile', [AdminController::class, 'profile']);
    Route::get('/mrs-wallet', [MRSPayController::class, 'MrsWallet']);
    Route::get('/wallet/{adminId}', [AdminController::class, 'index']);
    Route::post('/add-wallet-balance', [AdminController::class, 'AddWalletAmount']);
    Route::get('/logout', [AdminController::class, "Logout"]);
    Route::match(['get', 'post'], '/add-retailer/{id?}', [RetailerController::class, 'AddRetailer']);
    Route::get('/all-retailer', [RetailerController::class, "AllRetailer"]);
    Route::post('/update-retailer-status', [RetailerController::class, 'updateRetailerStatus']);
    Route::get('/kyc-verification/{id}', [RetailerController::class, "KycVerification"]);
    Route::post('user-document', [RetailerController::class, "UploadDocuments"]);
    // Route::get('/add-plancategory/{id?}', [RechargePlanController::class, 'AddPlanCategory']);
    // manage recharge plans
    Route::get('/add-rechargeplan/{id?}', [RechargePlanController::class, 'AddRechargePlan']);
    Route::get('/get-plan-categories/{provider_id}', [RechargePlanController::class, 'getPlanCategories']);
    Route::post('/insert-rechargeplan/{id?}', [RechargePlanController::class, 'InsertRechargePlan']);
    Route::get('/recharge-plan-list', [RechargePlanController::class, 'RechargePlanList']);
    Route::get('/get-recharge-plan', [RechargePlanController::class, 'getRechargePlan']);
    Route::post('/update-recharge-plan-status', [RechargePlanController::class, 'updateRechargePlanStatus']);
    Route::delete('/delete-recharge-plan/{id}', [RechargePlanController::class, 'deleteRechargePlan']);
    // manage Dth recharge plans
    Route::get('/dth-plan/{id?}', [DTHplanController::class, 'DthPlan']);
    Route::get('/dth-plan-list', [DTHplanController::class, 'DTHPlanList']);
    Route::get('/get-dthplan-list', [DTHplanController::class, 'getDthPlan']);
    Route::post('/update-dthplan-status', [DTHplanController::class, 'updateDTHPlanStatus']);
    Route::post('/add-dthplan/{id?}', [DTHplanController::class, 'AddDTHPlan']);
    Route::delete('/delete-dth-plan/{id?}', [DTHplanController::class, 'deleteDTHPlan']);
    
    // Services 
    Route::get('/services', [ServiceController::class, 'services']);
    Route::get('/get-services', [ServiceController::class, 'getServices']);
    Route::post('/add-service', [ServiceController::class, 'AddService']);
    Route::post('/update-service/{id}', [ServiceController::class, 'updateService']);
    Route::post('/update-service-status', [ServiceController::class, 'updateServiceStatus']);
    Route::delete('/delete-service/{id}', [ServiceController::class, 'deleteService']);
    // Agent Type
    Route::get('/agent-type', [AgentTypeController::class, 'AgentType']);
    Route::get('/get-agent-type', [AgentTypeController::class, 'getAgentType']);
    Route::post('/add-agent-type', [AgentTypeController::class, 'addAgentType']);
    Route::post('/update-agent-type/{id}', [AgentTypeController::class, 'updateAgentType']);
    Route::post('/update-agent-type-status', [AgentTypeController::class, 'updateAgentTypeStatus']);
    Route::delete('/delete-agent-type/{id}', [AgentTypeController::class, 'deleteAgentType']);
    // Documents Type
    Route::get('/documents', [DocumentController::class, 'documents']);
    Route::get('/get-document-type', [DocumentController::class, 'getDocumentType']);
    Route::post('/add-document-type', [DocumentController::class, 'addDocumentType']);
    Route::post('/update-document-type/{id}', [DocumentController::class, 'updateDocumentType']);
    Route::post('/update-document-type-status', [DocumentController::class, 'updateDocumentTypeStatus']);
    Route::delete('/delete-document-type/{id}', [DocumentController::class, 'deleteDocumentType']);
   // Providers
    Route::get('/providers', [ProviderController::class, 'providers']);
    Route::get('/get-providers', [ProviderController::class, 'getProviders']);
    Route::post('/add-provider', [ProviderController::class, 'AddProvider']);
    Route::post('/update-provider/{id}', [ProviderController::class, 'updateProvider']);
    Route::post('/update-provider-status/{id}', [ProviderController::class, 'updateProviderStatus']);
    Route::delete('/delete-provider/{id}', [ProviderController::class, 'deleteProvider']);

    //API Providers
    Route::get('api-providers', [ApiProviderController::class, 'index']);
    Route::get('get-api-providers', [ApiProviderController::class, 'getApiProviders']);
    Route::post('add-api-provider', [ApiProviderController::class, 'addApiProvider']);
    Route::post('update-api-provider/{id}', [ApiProviderController::class, 'updateApiprovider']);
    Route::post('update-api-provider-status/{id}', [ApiProviderController::class, 'updateStatus']);
    Route::delete('delete-api-provider/{id}', [ApiProviderController::class, 'destroy']);

    //API Provider ID
    Route::get('api-provider-mapping/{service_id}', [ApiProviderMappingController::class, 'ApiProviderMapping']);
    Route::post('update-provider-mapping', [ApiProviderMappingController::class, 'UpdateProviderMapping']);
    

    // Operators
    Route::get('/operators', [RechargePlanController::class, 'Operators']);
    Route::get('/get-operators', [RechargePlanController::class, 'getOperators']);
    Route::post('/add-operator', [RechargePlanController::class, 'AddOperator']);
    
    // States
    Route::get('/states', [StateController::class, 'States']);
    Route::get('/get-states', [StateController::class, 'getStates']);
    Route::post('/add-state', [StateController::class, 'AddState']);
    Route::post('/update-state/{id}', [StateController::class, 'updateState']);
    Route::post('/update-state-status/{id}', [StateController::class, 'updateStateStatus']);
    Route::delete('/delete-state/{id}', [StateController::class, 'deleteState']);

    // Districts
    Route::get('/districts', [DistrictController::class, 'districts']);
    Route::get('/get-districts', [DistrictController::class, 'getDistricts']);
    Route::post('/add-district', [DistrictController::class, 'addDistrict']);
    Route::post('/update-district/{id}', [DistrictController::class, 'updateDistrict']);
    Route::post('/update-district-status/{id}', [DistrictController::class, 'updateDistrictStatus']);
    Route::delete('/delete-district/{id}', [DistrictController::class, 'deleteDistrict']);
    
    // Plan Category
    Route::get('/plan-category', [PlanCategoryController::class, 'PlanCategory']);
    Route::post('/add-plan-category', [PlanCategoryController::class, 'AddPlanCategory']);
    Route::get('/get-plan-category', [PlanCategoryController::class, 'getPlancategory']);
    Route::post('/update-plan-category/{id}', [PlanCategoryController::class, 'UpdatePlanCategory']);
    Route::post('/update-plan-category-status', [PlanCategoryController::class, 'updatePlanCategoryStatus']);
    Route::delete('/delete-plan-category/{id}', [PlanCategoryController::class, 'deletePlanCategory']);

    //Language controller
    Route::get('/languages', [LanguageController::class, 'languages']);
    Route::get('/get-languages', [LanguageController::class, 'getLanguages']);
    Route::post('/add-language', [LanguageController::class, 'AddLanguage']);
    Route::post('/update-language/{id}', [LanguageController::class, 'updateLanguage']);
    Route::post('/update-language-status/{id}', [LanguageController::class, 'updateLanguageStatus']);
    Route::delete('/delete-language/{id}', [LanguageController::class, 'deleteLanguage']);

    // slabs Route
    Route::get('/slabs', [SlabController::class, 'Slabs']);
    Route::get('/get-providers-by-service/{service_id}', [SlabController::class, 'getProviderByService']);
    Route::post('/add-slab', [SlabController::class, 'addSlabs']);
    Route::get('/get-slabs', [SlabController::class, 'getSlabs']);
    Route::post('/update-slab/{id}', [SlabController::class, 'UpdateSlab']);
    //Packages Route
    Route::get('/add-package', [PackageController::class, 'AddPackage']);
    Route::get('/all-package', [PackageController::class, 'AllPackage']);
    Route::post('/package-store', [PackageController::class, 'store']);
    Route::post('/package-update/{id}', [PackageController::class, 'PackageUpdate']);
    //Packages Route
    //Route::get('/edit-commission', [CommissionController::class, 'editCommission']);
    Route::get('/{service}-commission-settings', [CommissionController::class, 'editCommission']);
    Route::post('/update-commission', [CommissionController::class, 'updateCommission']);
    // Route::get('/all-package', [CommissionController::class, 'AllPackage']);
    // Route::post('/package-store', [CommissionController::class, 'store']);
    // Route::post('/package-update/{id}', [CommissionController::class, 'PackageUpdate']);
    Route::post('/import-banks', [BankController::class, 'importBanks']);
    Route::get('/bank-list', [BankController::class, 'BankList']);
    Route::get('/get-bank-list', [BankController::class, 'getBankList']);

});


