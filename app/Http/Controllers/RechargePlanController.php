<?php

namespace App\Http\Controllers;

use App\Models\RechargePlan;
use App\Models\Operator;
use App\Models\Provider;
use App\Models\PlanCategory;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class RechargePlanController extends Controller
{

           // $response = Http::post('https://mrspay.in/api/application/v1/get-provider', [
        //     'api_token' => 'fSBNkN8BkCL5TdwuCpuuywiJvl65GlKrAmDDM2wB6aHNdkkU0Uiz4qN3VAbE'
        // ]);
        
        // if ($response->successful()) {
        //     $data = $response->json();
        //     if ($data['status'] === 'success' && isset($data['providers'])) {
        //         $filteredProviders = array_filter($data['providers'], function ($provider) {
        //             return $provider['service_id'] == 1;
        //         });
                
        //         $title ="Plan Category";
        //         $state = State::where('is_active', '1')->get();
        //         return view('admin.rechargeplan.add-rechargeplan', compact('title', 'filteredProviders', 'state'));
        //     }
        // }

    public function Operators()
    {
        $title = "Operator";
        $operators = Operator::all(); 
        return view('admin.rechargeplan.operator', compact('title', 'operators'));
    }

    public function AddOperator(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:operators,name',
        ]);

        $operator = Operator::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Operator added successfully!',
            'operator' => $operator
        ]);
    }
    
    public function getOperators()
    {
        $operators = Operator::orderBy('id', 'desc')->get();
        return response()->json($operators);
    }

    public function AddRechargePlan(Request $request, $id = null)
    {
        $filteredProviders = Provider::where(['status' => '1', 'service_id' => 1])->get();
        $state = State::where('status', '1')->get();
        $title ="Plan Category";
        
        $rechargePlan = null;
        if ($id) {
            $rechargePlan = RechargePlan::find($id);
            if (!$rechargePlan) {
                return redirect()->back()->with('error', 'Recharge plan not found!');
            }
            $rechargePlan->states = json_decode($rechargePlan->states, true);
            $rechargePlan->plan_category = json_decode($rechargePlan->plan_category, true);
        }
        return view('admin.rechargeplan.add-rechargeplan', compact('title', 'filteredProviders', 'state', 'rechargePlan'));
    }
    
    public function getPlanCategories($provider_id)
    {
        $categories = PlanCategory::where('provider_id', $provider_id)->select('id', 'name')->get();
    
        if ($categories->isNotEmpty()) {
            return response()->json(['success' => true, 'categories' => $categories]);
        } else {
            return response()->json(['success' => false, 'categories' => []]);
        }
    }
    
    
    // public function InsertRechargePlan(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'service_id' => 'required|integer',
    //         'provider_id' => 'required|integer',
    //         'states' => 'required|array',
    //         'amount' => 'required|numeric',
    //         'plan_name' => 'required|string|max:255',
    //         'validity' => 'required|integer',
    //         'time_duration' => 'required|in:days,months',
    //         'calling_options' => 'required|in:Unlimited,Minutes',
    //         'data' => 'nullable|string',
    //         'data_renewal' => 'required|in:per day,per plan',
    //         'unlimited_5g' => 'required|in:yes,no',
    //         'sms_count' => 'nullable|integer',
    //         'sms_renewal' => 'required|in:per day,per plan',
    //         'plan_category' => 'required|array',
    //         'additional_benefits' => 'nullable|string',
    //     ]);
    
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }
    
    //     $validatedData = $validator->validated();
    //     $validatedData['states'] = json_encode($request->states);
    //     $validatedData['plan_category'] = json_encode($request->plan_category);
    
    //     RechargePlan::create($validatedData);
    
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Recharge plan added successfully!'
    //     ]);
    // }

    public function InsertRechargePlan(Request $request, $id = null) 
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|integer',
            'provider_id' => 'required|integer',
            'states' => 'required|array',
            // 'amount' => 'required|numeric',
            'amount' => [
                'required',
                'numeric',
                Rule::unique('recharge_plans')->where(function ($query) use ($request, $id) {
                    return $query->where('provider_id', $request->provider_id);
                })->ignore($id) 
            ],
            'plan_name' => 'nullable|string|max:255',
            'validity' => 'nullable|string|max:255',
            'time_duration' => 'nullable|in:days,months,other',
            'other_duration' => 'nullable|string|max:255',
            'calling_options' => 'nullable|string|max:255',
            'data' => 'nullable|string',
            'data_renewal' => 'nullable|string',
            'other_data_renewal' => 'nullable|string|max:255',
            'unlimited_5g' => 'nullable|in:yes,no',
            'sms_count' => 'nullable|string',
            'sms_renewal' => 'nullable|string',
            'plan_category' => 'nullable|array',
            'additional_benefits' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $validatedData = $validator->validated();
        $validatedData['states'] = json_encode($request->states);  
        $validatedData['plan_category'] = json_encode($request->plan_category);
    
        if ($id) {
            // Update existing plan
            $plan = RechargePlan::find($id);
            if (!$plan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Recharge plan not found!'
                ], 404);
            }
            $plan->update($validatedData);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Recharge plan updated successfully!',
                'data' => $plan
            ]);
        } else {
            // Insert new plan
            $plan = RechargePlan::create($validatedData);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Recharge plan added successfully!',
                'data' => $plan
            ]);
        }
    }
    



    public function RechargePlanList(){
        $title = "Recharge Plan List";
        return view('admin.rechargeplan.rechargeplan-list', compact('title'));
    }

    // public function getRechargePlan()
    // {
    //     $plans = RechargePlan::with('provider')->where('status', '1')->orderBy('id', 'desc')->get();

    //     if ($plans->isEmpty()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'No active recharge plans found.'
    //         ], 404);
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Recharge plans retrieved successfully.',
    //         'data' => $plans
    //     ], 200);
    // }

 public function getRechargePlan(Request $request)
{
    $perPage = $request->input('per_page', 10);

    $query = RechargePlan::with('provider')->orderBy('id', 'desc');

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('amount', 'like', "%$search%")
              ->orWhere('calling_options', 'like', "%$search%")
              ->orWhere('sms_count', 'like', "%$search%")
              ->orWhereHas('provider', function ($p) use ($search) {
                  $p->where('provider_name', 'like', "%$search%");
              });
        });
    }

    if ($request->filled('date_range')) {
        $range = explode(' to ', $request->date_range);
        if (count($range) == 2) {
            $from = \Carbon\Carbon::createFromFormat('d M, Y', trim($range[0]))->startOfDay();
            $to = \Carbon\Carbon::createFromFormat('d M, Y', trim($range[1]))->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    $plans = $query->paginate($perPage);

    return response()->json([
        'status' => true,
        'message' => 'Recharge plans retrieved successfully.',
        'data' => $plans->items(),
        'pagination' => [
            'current_page' => $plans->currentPage(),
            'last_page' => $plans->lastPage(),
            'per_page' => $plans->perPage(),
            'total' => $plans->total()
        ]
    ]);
}



    public function updateRechargePlanStatus(Request $request)
    {
        $recharge = RechargePlan::find($request->id);
        if ($recharge) {
            $recharge->status = $request->status;
            $recharge->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }


    public function deleteRechargePlan($id)
    {
        $plan = RechargePlan::find($id);
        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Recharge not found']);
        }
        $plan->delete();
        return response()->json(['success' => true, 'message' => 'Recharge deleted successfully']);
    }

}
          