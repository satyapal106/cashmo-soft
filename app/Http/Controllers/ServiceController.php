<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\ServiceList;
use App\Models\Service;

class ServiceController extends Controller
{
    public function fetchAndStoreProviders()
    {
        $response = Http::post('https://mrspay.in/api/application/v1/get-provider', [
            'api_token' => 'fSBNkN8BkCL5TdwuCpuuywiJvl65GlKrAmDDM2wB6aHNdkkU0Uiz4qN3VAbE'
        ]);
        
        // if ($response->successful()) {
        //     return response()->json($response->json());
        // }
        
        if ($response->successful()) {
            $data = $response->json();
            
            //return $data;

            if ($data['status'] === 'success' && isset($data['providers'])) {
                $filteredProviders = array_filter($data['providers'], function ($provider) {
                    return $provider['service_id'] == 1;
                });

                return response()->json([
                    'status' => 'success',
                    'providers' => array_values($filteredProviders) // Array Index Reset
                ]);
            }
        }

        // if ($response->successful()) {
        //     $data = $response->json();

        //     if ($data['status'] === 'success' && isset($data['providers'])) {
        //         foreach ($data['providers'] as $provider) {
        //             ServiceList::updateOrCreate(
        //                 ['provider_id' => $provider['provider_id']],
        //                 [
        //                     'provider_name' => $provider['provider_name'],
        //                     'service_id'    => $provider['service_id'],
        //                     'service_name'  => $provider['service_name'],
        //                     'provider_icon' => $provider['provider_icon'] ?? null,
        //                 ]
        //             );
        //         }

        //         return response()->json(['message' => 'Providers saved successfully']);
        //     }
        // }

        return response()->json(['message' => 'Failed to fetch providers'], 500);
    }


    public function Services(){
        $title = "service";
        $nav   = "service";
        return view('admin.tools.service', compact('title', 'nav'));
    }

    public function addService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:255|unique:services,service_name'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors() 
            ], 422);
        }
    
        $service_name = $request->service_name;
        $service_code = strtoupper(substr($service_name, 0, 2) . substr($service_name, -2));
    
        $service = Service::create([
            'service_name' => $service_name,
            'service_code' => $service_code,
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Service added successfully!',
            'data' => $service
        ], 201);
    }
    

    public function getServices()
    {
        $services = Service::orderBy('id', 'desc')->get();
        return response()->json($services);
    }



    public function updateService(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:255|unique:services'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors() 
            ], 422);
        }

        $service = Service::find($id);
        if (!$service) {
            return response()->json(['error' => 'Service not found!'], 404);
        }

        // Update Service Code (First 2 + Last 2 letters, Uppercase)
        $service_name = $request->service_name;
        $service_code = strtoupper(substr($service_name, 0, 2) . substr($service_name, -2));

        $service->update([
            'service_name' => $service_name,
            'service_code' => $service_code,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Service updated successfully!', 
            'data' => $service
        ], 201);
    }


    public function updateServiceStatus(Request $request)
    {
        $service = Service::find($request->id);
        if ($service) {
            $service->status = $request->status;
            $service->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }


    public function deleteService($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['status' => false, 'message' => 'Service not found']);
        }

        $service->delete();

        return response()->json(['status' => true, 'message' => 'Service deleted successfully']);
    }

}


?>