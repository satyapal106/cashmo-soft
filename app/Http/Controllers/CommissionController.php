<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commission;
use App\Models\Package;
use App\Models\Service;
use App\Models\Provider;
use App\Models\Slab;
use Illuminate\Support\Str;

class CommissionController extends Controller
{
       
    // public function editCommission($service)
    // {
    //     $title = "Commission Setting";
    //     $service = Str::lower($service);
    
    //     // Get the service row
    //     $serviceData = Service::whereRaw('LOWER(service_name) = ?', [$service])->firstOrFail();
    
    //     // Fetch providers for the service
    //     $providers = Provider::with('service')
    //         ->where('service_id', $serviceData->id)
    //         ->orderBy('id', 'desc')
    //         ->get();
    
    //     // Show slabs only if service is NOT mobile or dth
    //     $showSlabs = !in_array($service, ['mobile', 'dth']);
    
    //     // If slabs are to be shown, fetch them
    //     $slabs = $showSlabs
    //         ? Slab::with('provider')->whereIn('provider_id', $providers->pluck('id'))->get()
    //         : collect(); // empty collection if not needed
    
    //     return view('admin.packages.commissions', compact('title', 'providers', 'slabs', 'showSlabs'));
    // }

    public function editCommission(Request $request, $service)
    {
        $title = "Commission Setting";
        $service = Str::lower($service);
    
        $package_id = $request->query('package_id');
        $serviceData = Service::whereRaw('LOWER(service_name) = ?', [$service])->firstOrFail();
    
        // Get all providers for this service
        $providers = Provider::with('service')
            ->where('service_id', $serviceData->id)
            ->orderBy('id', 'desc')
            ->get();
    
        // Get all slabs for those providers
        $slabs = Slab::with('provider')
            ->whereIn('provider_id', $providers->pluck('id'))
            ->get();
    
        // Map commissions by slab_id for easy access in Blade
        $commissions = Commission::where('package_id', $package_id)
            ->where('service_id', $serviceData->id)
            ->whereIn('slab_id', $slabs->pluck('id'))
            ->get()
            ->keyBy('slab_id');
    
        $showSlabs = true;
    
        return view('admin.packages.commissions', compact(
            'title',
            'providers',
            'package_id',
            'slabs',
            'commissions',
            'showSlabs'
        ));
    }



    public function updateCommission(Request $request)
    {
        $request->validate([
            'package_id'   => 'required|integer|exists:packages,id',
            'service_id'   => 'required|integer|exists:services,id',
            'provider_id'  => 'required|integer|exists:providers,id',
            'slab_id'      => 'required|integer|exists:slabs,id',
            'nature'       => 'required|in:cashback,charge',
            'type'         => 'required|in:%,flat',
            'value'        => 'required|numeric|min:0',
        ]);

        // Update or Create commission record
        $commission = Commission::updateOrCreate(
            [
                'package_id'  => $request->package_id,
                'service_id'  => $request->service_id,
                'provider_id' => $request->provider_id,
                'slab_id'     => $request->slab_id,
            ],
            [
                'nature' => $request->nature,
                'type'   => $request->type,
                'value'  => $request->value,
            ]
        );

        //return  $commission;
    
        return response()->json([
            'status'  => true,
            'message' => 'Commission updated successfully!',
            'commission' => $commission, // Send updated commission data
        ]);
    }
    
}