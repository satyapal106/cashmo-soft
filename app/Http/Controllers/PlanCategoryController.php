<?php

namespace App\Http\Controllers;

use App\Models\PlanCategory;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PlanCategoryController extends Controller
{
//    public function PlanCategory(Request $request){
       
//         $response = Http::post('https://mrspay.in/api/application/v1/get-provider', [
//             'api_token' => 'fSBNkN8BkCL5TdwuCpuuywiJvl65GlKrAmDDM2wB6aHNdkkU0Uiz4qN3VAbE'
//         ]);
        
//         if ($response->successful()) {
//             $data = $response->json();
//             if ($data['status'] === 'success' && isset($data['providers'])) {
//                 $filteredProviders = array_filter($data['providers'], function ($provider) {
//                     return $provider['service_id'] == 1;
//                 });
                
//                 $title ="Plan Category";
//                 return view('admin.rechargeplan.plan-category', compact('title', 'filteredProviders'));
//             }
//         }
//     }

   public function PlanCategory(){
      $title ="Plan Category";
      $nav   = "plan-category";
      $filteredProviders = Provider::where(['status' => '1', 'service_id' => 1])->get();
      return view('admin.rechargeplan.plan-category', compact('title', 'nav', 'filteredProviders'));
    }
    
    public function AddPlanCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 400);
        }
    
        $PlanCategory = PlanCategory::create([
            'service_id' => $request->service_id,
            'provider_id' => $request->provider_id,
            'name' => $request->name,
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Plan Category added successfully!',
            'data' => $PlanCategory
        ]);
    }
    
    public function getPlancategory(){
        $plancategory = PlanCategory::with('provider')->where(['status' => '1'])->OrderBy('id', 'desc')->paginate(10);
        return response()->json($plancategory);
    }


    public function UpdatePlanCategory(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        $planCategory = PlanCategory::find($id);

        if (!$planCategory) {
            return response()->json([
                'status' => false,
                'message' => 'Plan category not found!'
            ], 404);
        }

        $planCategory->update([
            'provider_id' => $request->provider_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Plan category updated successfully!',
            'data' => $planCategory
        ]);
    }


    public function updatePlanCategoryStatus(Request $request)
    {
        $planCategory = PlanCategory::find($request->id);
        if ($planCategory) {
            $planCategory->status = $request->status;
            $planCategory->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }


    public function deletePlanCategory($id)
    {
        $plancategory = PlanCategory::find($id);
        if (!$plancategory) {
            return response()->json(['status' => false, 'message' => 'Plan Category not found']);
        }
       $plancategory->delete();
       return response()->json(['status' => true, 'message' => 'Plan Category deleted successfully']);
    }
}