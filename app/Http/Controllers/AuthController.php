<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Service;
use App\Models\Provider;
use App\Models\Slab;
use App\Models\PlanCategory;
use App\Models\State;
use App\Models\District;
use App\Models\Language;
use App\Models\Package;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:15|unique:users,phone_number|regex:/^[6-9]\d{9}$/',
            'aadhar_number' => 'required|string|size:12|unique:users,aadhar_number|regex:/^\d{12}$/',
            'pan_number' => 'nullable|string|size:10|unique:users,pan_number|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'address' => 'nullable|string|max:255',
            'pincode' => 'required|digits:6',
            'locality' => 'nullable|string|max:255',
            'state_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'tehsil' => 'nullable|string|max:255',
            'shop_name' => 'nullable|string|max:255',
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'password_confirmation' => 'required|string|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        $customMessages = [
            'phone_number.regex' => 'Phone number must be a valid 10-digit number starting with 6-9.',
            'aadhar_number.regex' => 'Aadhar number must be a valid 12-digit numeric value.',
            'pan_number.regex' => 'PAN number must follow the format: 5 uppercase letters, 4 digits, and 1 uppercase letter (e.g., ABCDE1234F).',
            'password.regex' => 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password.confirmed' => 'Password and confirm password must match.',
            'password_confirmation.required' => 'Confirm password field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        $profileImage = null;

        if ($request->hasFile('profile_image')) {
            $manager = new ImageManager(new Driver());
            $path = 'assets/images/profiles/';

            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }

            $uploadedImage = $request->file('profile_image');
            $image = $manager->read($uploadedImage);
            $image->resize(60, 60);
            $image->encode(new WebpEncoder(quality: 65));
            $filename = uniqid() . '.webp';
            $image->save($path . $filename);
            $profileImage = $path . $filename;
        }

        try {
            $user = User::create([
                'name' => trim($request->name),
                'email' => strtolower(trim($request->email)),
                'phone_number' => $request->phone_number,
                'aadhar_number' => $request->aadhar_number,
                'pan_number' => $request->pan_number,
                'address' => $request->address,
                'pincode' => $request->pincode,
                'locality' => $request->locality,
                'state_id' => $request->state_id,
                'district_id' => $request->district_id,
                'tehsil' => $request->tehsil,
                'shop_name' => $request->shop_name,
                'profile_image' => $profileImage,
                'password' => Hash::make($request->password),
                'status' => 'pending',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User registered successfully!',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong! Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function signIn(Request $request)
    {
        $rules = [
            'email_or_phone' => 'required|string',
            'password' => 'required|string|min:8',
        ];
        $customMessages = [
            'email_or_phone.required' => 'Please enter your email or phone number.',
            'password.required' => 'Password is required to login.',
            'password.min' => 'Password must be at least 8 characters long.',
        ];
        
        $validator = Validator::make($request->all(), $rules, $customMessages);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors()->all(),
            ], 422);
        }
    
       $emailOrPhone = trim($request->email_or_phone);
       
       $user = User::where(function ($query) use ($emailOrPhone) {
                $query->where('email', $emailOrPhone)
                      ->orWhere('phone_number', $emailOrPhone);
            })
            ->where('status', 'approve')
            ->first();
            
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials. Please try again.',
            ], 401); 
        }
    
        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials. Please try again.',
            ], 401);
        }
    
        // Generate secure authentication token
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'status' => true,
            'message' => 'Login successful!',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function Service()
    {
        try {
            $services = Service::where('status', '1')->get(['id','service_name', 'service_code']);

            return response()->json([
                'status' => true,
                'message' => 'Service list fetched successfully',
                'data' => $services
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch service list',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function getProvidersByServiceId($id)
    {
        if (!$id) {
            return response()->json([
                'status' => false,
                'message' => 'Service ID is required.'
            ], 400);
        }

        $providers = Provider::where('service_id', $id)
                            ->where('status', 1)
                            ->get(['id', 'service_id', 'provider_name', 'provider_code','logo']);

        if ($providers->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No providers found for this service ID.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Providers fetched successfully.',
            'data' => $providers
        ], 200);
    }
    public function getSlabsByProviderId($provider_id)
    {
        $slabs = Slab::where('provider_id', $provider_id)
                     ->where('status', 1)
                     ->get();

        if ($slabs->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No slabs found for this provider_id'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Slabs fetched successfully',
            'data' => $slabs
        ], 200);
    }


    public function getByProviderId($provider_id)
    {
        $categories = PlanCategory::where('provider_id', $provider_id)
                                  ->where('status', 1)
                                  ->get();

        if ($categories->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No plan categories found for this provider'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Plan categories fetched successfully',
            'data' => $categories
        ], 200);
    }

    public function State()
    {
        $states = State::where('status', 1)->get(['id','name','code']);

        if ($states->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No states found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'States fetched successfully',
            'data' => $states
        ], 200);
    }

    public function getByStateId($state_id)
    {
        $districts = District::where('state_id', $state_id)
                             ->where('status', 1)
                             ->get();

        if ($districts->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No districts found for this state'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Districts fetched successfully',
            'data' => $districts
        ], 200);
    }

    public function Language()
    {
        $languages = Language::where('status', 1)->get(['id','name', 'code']);

        if ($languages->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No active languages found.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Languages fetched successfully.',
            'data' => $languages
        ], 200);
    }
}
