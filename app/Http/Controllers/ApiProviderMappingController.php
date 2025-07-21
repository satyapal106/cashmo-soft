<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use App\Models\ApiProvider;
use App\Models\ApiProviderMapping;

class ApiProviderMappingController extends Controller
{

    public function ApiProviderMapping($service_id)
    {
        $title = "API Providers";
        $nav   = "api-providers";

        $api_providers = ApiProvider::where('status', '1')
            ->orderBy('id', 'desc')
            ->get();

        $providers = Provider::with('apiMappings')
            ->where('status', '1')
            ->where('service_id', $service_id)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($provider) use ($api_providers) {
                $data = $provider->toArray(); // Convert to array to add custom property
                $data['api_data'] = [];

                foreach ($api_providers as $apiProvider) {
                    $mapping = $provider->apiMappings->firstWhere('api_provider_id', $apiProvider->id);
                    $data['api_data'][$apiProvider->id] = $mapping->api_id ?? '';
                }

                return (object)$data; // Cast to object so it behaves like original model
            });

        return view('admin.tools.api-providers-id', compact('title', 'nav', 'providers', 'api_providers'));
    }

    public function UpdateProviderMapping(Request $request)
    {
        $request->validate([
            'provider_id' => 'required|integer',
            'values' => 'required|array',
        ]);

        foreach ($request->values as $apiProviderId => $apiId) {
            if ($apiId !== null && $apiId !== '') {
                ApiProviderMapping::updateOrCreate(
                    [
                        'provider_id' => $request->provider_id,
                        'api_provider_id' => $apiProviderId
                    ],
                    [
                        'api_id' => $apiId
                    ]
                );
            }
        }

        return response()->json(['message' => 'API provider mapping updated successfully.']);
    }

}
