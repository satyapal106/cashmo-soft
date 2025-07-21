<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Service;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    
    public function providers(){
       $title = "Provider";
       $nav   = "provider";
       $service = Service::where('status', '1')->get();
       return view('admin.tools.providers', compact('title', 'nav', 'service'));
    }


    public function addProvider(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_name' => 'required|string|max:255|unique:providers',
            'provider_code' => 'required|string|max:255|unique:providers',
            'service_id'    => 'required|exists:services,id',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:200', 
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        $data = [
            'service_id'    => $request->service_id,
            'provider_name' => $request->provider_name,
            'provider_code' => $request->provider_code,
        ];

        if($request->hasFile('logo')) {
            $manager = new ImageManager(new Driver());
            $path = 'assets/images/providers/';
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            $uploadedImage = $request->file('logo');
            $image = $manager->read($uploadedImage);
            $image->resize(60, 60);
            $image->encode(new WebpEncoder(quality: 65));
            $filename = uniqid() . '.' .'webp';
            $image->save($path.$filename);
            $data['logo'] = $path.$filename;
        }
    
        $provider = Provider::create($data);
    
        return response()->json([
            'status'  => true,
            'message' => 'Provider added successfully!', 
            'data'    => $provider
        ], 201);
    }
    
    

    public function getProviders()
    {
        $providers =Provider::with('service')->orderBy('id', 'desc')->get();
        return response()->json($providers);
    }

    public function updateProvider(Request $request, $id)
    {
        $request->validate([
            'provider_name' => 'required|string|max:255',
            'provider_code' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:200',
        ]);

       $provider = Provider::findOrFail($id);

       if ($request->hasFile('logo')) {
        $manager = new ImageManager(new Driver());
        $path = 'assets/images/providers/';
        
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $uploadedImage = $request->file('logo');
        $image = $manager->read($uploadedImage);
        $image->resize(60, 60);
        $image->encode(new WebpEncoder(), 65);
        $filename = uniqid() . '.webp';
        $image->save($path . $filename);

        // Delete the old logo if it exists
        if ($provider->logo && file_exists($provider->logo)) {
            unlink($provider->logo);
        }
        $provider->logo = $path . $filename;
    }
        $provider->provider_name = $request->provider_name;
        $provider->provider_code = $request->provider_code;
        $provider->service_id = $request->service_id;
        $provider->save();

        return response()->json(['success' => true, 'message' => 'Provider updated successfully']);
    }

    public function updateProviderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $provider = Provider::findOrFail($id);
        $provider->status = $request->status;
        $provider->save();
        return response()->json(['success' => true, 'message' => 'Provider status updated successfully']);
    }

    public function deleteProvider($id)
    {
        $provider = Provider::find($id);
        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'Provider not found']);
        }

        $provider->delete();

        return response()->json(['success' => true, 'message' => 'Provider deleted successfully']);
    }

}
