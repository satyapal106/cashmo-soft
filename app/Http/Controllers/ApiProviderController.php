<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApiProvider;
use App\Models\Provider;
use Illuminate\Support\Facades\Validator;

class ApiProviderController extends Controller
{
    public function index()
    {
        $title = "API Providers";
        $nav   = "api-providers";
        return view('admin.tools.api-providers', compact('title', 'nav'));
    }

    public function getApiProviders()
    {
        $providers = ApiProvider::orderBy('id', 'desc')->get();
        return response()->json($providers);
    }

    public function addApiProvider(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:api_providers,name',
            'api_token' => 'nullable|string|max:255|unique:api_providers,api_token',
            'base_url' => 'nullable|string|max:255',
            'status' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $provider = ApiProvider::create([
            'name' => $request->name,
            'api_token' => $request->api_token,
            'base_url' => $request->base_url,
            'status' => 1,
        ]);

        return response()->json(['status' => true, 'message' => 'Provider added successfully!', 'data' => $provider], 201);
    }

    public function updateApiprovider(Request $request, $id)
    {
        $provider = ApiProvider::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:api_providers,name,' . $id,
            'api_token' => 'nullable|string|max:255|unique:api_providers,api_token,' . $id,
            'base_url' => 'nullable|string|max:255',
            'status' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $provider->update([
            'name' => $request->name,
            'api_token' => $request->api_token,
            'base_url' => $request->base_url,
            'status' => 1,
        ]);

        return response()->json(['success' => true, 'message' => 'Provider updated successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $provider = ApiProvider::findOrFail($id);
        $provider->status = $request->status;
        $provider->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function destroy($id)
    {
        $provider = ApiProvider::find($id);
        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'Provider not found']);
        }

        $provider->delete();
        return response()->json(['success' => true, 'message' => 'Provider deleted successfully']);
    }

}
