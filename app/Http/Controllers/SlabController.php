<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slab;
use App\Models\Service;
use App\Models\Provider;
use Illuminate\Support\Facades\Validator;

class SlabController extends Controller
{
    public function Slabs(){
        $title = "Slabs";
        $nav   = "slabs";
        $service = Service::where('status', '1')->get();
        return view('admin.tools.slabs', compact('title', 'nav', 'service'));
     }


     public function getProviderByService($service_id)
     {
         $providers = Provider::where('service_id', $service_id)->select('id', 'provider_name')->get();
     
         if ($providers->isNotEmpty()) {
             return response()->json(['success' => true, 'providers' => $providers]);
         } else {
             return response()->json(['success' => false, 'providers' => []]);
         }
     }

    public function addSlabs(Request $request)
    {
         $validator = Validator::make($request->all(), [
             'service_id'   => 'required|exists:services,id',
             'provider_id'  => 'required|exists:providers,id',
             'min_amount'   => 'required|numeric|min:0',
             'max_amount'   => 'required|numeric|gte:min_amount',
         ]);
     
         if ($validator->fails()) {
             return response()->json([
                 'status' => false,
                 'errors' => $validator->errors()->all(),
             ], 422);
         }
     
         $slab = Slab::create([
             'service_id'  => $request->service_id,
             'provider_id' => $request->provider_id,
             'min_amount'  => $request->min_amount,
             'max_amount'  => $request->max_amount,
         ]);
     
         // Load relationships for better response
         $slab->load(['service', 'provider']);
     
         return response()->json([
             'status'  => true,
             'message' => 'Slab added successfully!',
             'data'    => $slab,
         ], 201);
    }
     
    public function getSlabs()
    {
        $slabs = Slab::with(['service:id,service_name', 'provider:id,provider_name'])
            ->orderBy('id', 'desc')
            ->get();
    
        return response()->json($slabs);
    }

    // 3. Update an existing slab
    public function UpdateSlab(Request $request, $id)
    {
        $request->validate([
            'service_id'   => 'required|exists:services,id',
            'provider_id'  => 'required|exists:providers,id',
            'min_amount'   => 'required|numeric|min:1',
            'max_amount'   => 'required|numeric|gt:min_amount',
        ]);

        $slab = Slab::findOrFail($id);
        $slab->update([
            'service_id'  => $request->service_id,
            'provider_id' => $request->provider_id,
            'min_amount'  => $request->min_amount,
            'max_amount'  => $request->max_amount,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Slab updated successfully',
        ]);
    }
}
