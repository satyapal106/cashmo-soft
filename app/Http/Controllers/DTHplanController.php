<?php

namespace App\Http\Controllers;

use App\Models\DTHplan;
use App\Models\Provider;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DTHplanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function DthPlan(Request $request, $id = null)
    {
       $filteredProviders = Provider::where(['status' => '1', 'service_id' => 2])->get();
       $title = "DTH Plans";
       $language = Language::where('status', '1')->get();
       $dthPlan = null;
        if ($id) {
            $dthPlan = DthPlan::find($id);
            if (!$dthPlan) {
                return redirect()->back()->with('error', 'dth plan not found!');
            }
            $dthPlan->languages = json_decode($dthPlan->languages, true);
        }
       return view('admin.dthplans.add-plan', compact('filteredProviders','title', 'language','dthPlan'));
    }

   
    public function AddDTHPlan(Request $request, $id = null) 
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|integer',
            'provider_id' => 'required|integer',
            'amount' => [
                'required',
                'numeric',
                Rule::unique('dth_plans')->where(function ($query) use ($request, $id) {
                    return $query->where('provider_id', $request->provider_id);
                })->ignore($id)
            ],
            'plan_name' => 'nullable|string|max:255',
            'validity' => 'nullable|string|max:255',
            'languages' => 'nullable|array',
            'channel_quality' => 'nullable|array',
            'benefits' => 'nullable|string',
            'channel_summary' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $validatedData = $validator->validated();
        $validatedData['languages'] = json_encode($request->languages ?? []);
        $validatedData['channel_quality'] = json_encode($request->channel_quality ?? []);
    
        if ($id) {
            // Update existing plan
            $plan = DTHplan::find($id);
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
            $plan = DTHplan::create($validatedData);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Recharge plan added successfully!',
                'data' => $plan
            ]);
        }
    }

    public function DTHPlanList(){
        $title = "DTH Plan List";
        return view('admin.dthplans.plans-list', compact('title'));
    }

    public function getDthPlan()
    {
        $plans = DTHplan::with('provider')->orderBy('id', 'desc')->get();

        if ($plans->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No active DTH plans found.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'DTH plans retrieved successfully.',
            'data' => $plans
        ], 200);
    }
    

    public function updateDTHPlanStatus(Request $request)
    {
        $recharge = DTHplan::find($request->id);
        if ($recharge) {
            $recharge->status = $request->status;
            $recharge->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }


    public function deleteDTHPlan($id)
    {
        $plan = DTHplan::find($id);
        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'DTH Plan not found']);
        }
        $plan->delete();
        return response()->json(['success' => true, 'message' => 'DTH plan deleted successfully']);
    }
}
