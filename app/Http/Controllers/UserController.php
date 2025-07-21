<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Provider;
use App\Models\State;
use App\Models\RechargePlan;
use App\Models\DTHplan;
use App\Models\Language;
use App\Models\District;

class UserController extends Controller
{

    public function Signin(){
         return view('retailer.login');
    }

    public function Signup(){
        $state = State::where('status', '1')->get();
        return view('retailer.signup', compact('state'));
    }

    public function getDistricts($state_id)
    {
        $districts = District::where('state_id', $state_id)->get();
        return response()->json($districts);
    }

    // public function RetailerSignup(Request $request)
    // {
    //     // Validate input data with custom messages
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'phone_number' => [
    //             'required', 
    //             'string', 
    //             'max:15', 
    //             'unique:users',
    //             'regex:/^[6-9]\d{9}$/', 
    //         ],
    //         'aadhar_number' => [
    //             'required', 
    //             'string', 
    //             'size:12', 
    //             'unique:users',
    //             'regex:/^\d{12}$/', 
    //         ],
    //         'pan_number' => [
    //             'required', 
    //             'string', 
    //             'size:10', 
    //             'unique:users',
    //             'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
    //         ],
    //         'address' => 'required|string|max:255',
    //         //'city' => 'nullable|string|max:100',
    //         'state' => 'nullable|string|max:100',
    //         'shop_name' => 'required|string|max:255',
    //         'password' => 'required|string|min:6|confirmed', 
    //         'password_confirmation' => 'required|string|min:6',
    //         'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
    //     ], [
    //         'email.unique' => 'This email is already registered.',
    //         'phone_number.unique' => 'This phone number is already in use.',
    //         'phone_number.regex' => 'Phone number must start with 6-9 and be 10 digits long.',
    //         'aadhar_number.unique' => 'Aadhar number is already registered.',
    //         'aadhar_number.regex' => 'Aadhar number must be exactly 12 digits.',
    //         'pan_number.unique' => 'PAN number is already registered.',
    //         'pan_number.regex' => 'PAN number must be in the format: ABCDE1234F.',
    //         'password.confirmed' => 'Password confirmation does not match.',
    //         'profile_image.image' => 'Profile image must be a valid image file.',
    //         'profile_image.mimes' => 'Profile image must be in jpeg, png, jpg, or gif format.',
    //         'profile_image.max' => 'Profile image size should not exceed 2MB.',
    //     ]);
    
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validation Error',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }
    
    //     // Handle profile image upload using ImageManager and WebpEncoder
    //     $profileImagePath = null;
    //     if ($request->hasFile('profile_image')) {
    //         $manager = new ImageManager(new Driver());
    //         $path = 'assets/images/profile/'; // Define the storage path
    
    //         // Create the directory if it does not exist
    //         if (!is_dir($path)) {
    //             mkdir($path, 0755, true);
    //         }
    
    //         $uploadedImage = $request->file('profile_image');
    //         $image = $manager->read($uploadedImage);
    //         $image->resize(1080, 1080); // Resize to 1080x1080
    //         $image->encode(new WebpEncoder(quality: 65)); // Encode as WebP with 65% quality
    //         $filename = uniqid() . '.webp'; // Unique filename
    //         $image->save($path . $filename); // Save the image
    
    //         $profileImagePath = $path . $filename; // Save path to store in the database
    //     }
    
    //     // Save user to database
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'phone_number' => $request->phone_number,
    //         'aadhar_number' => $request->aadhar_number,
    //         'pan_number' => $request->pan_number,
    //         'address' => $request->address,
    //         'pincode' => $request->pincode,
    //         'state' => $request->state,
    //         'shop_name' => $request->shop_name,
    //         'password' => Hash::make($request->password), 
    //         'profile_image' => $profileImagePath, 
    //     ]);
    
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Retailer registered successfully.',
    //         'data' => $user,
    //     ]);
    // }

    public function RetailerSignup(Request $request)
    {
        // Define validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => [
                'required', 
                'string', 
                'regex:/^[6-9]\d{9}$/', 
                'unique:users'
            ],
            'aadhar_number' => [
                'required', 
                'string', 
                'size:12', 
                'regex:/^\d{12}$/', 
                'unique:users'
            ],
            'pan_number' => [
                'required', 
                'string', 
                'size:10', 
                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                'unique:users'
            ],
            'pincode' => 'required|digits:6',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'tehsil' => 'required|string|max:255',
            'shop_name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,web|max:500',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'address' => 'nullable|string|max:500',
        ];

        // Define custom validation messages
        $customMessages = [
            'email.unique' => 'This email is already registered.',
            'phone_number.unique' => 'This phone number is already in use.',
            'phone_number.regex' => 'Phone number must start with 6-9 and be 10 digits long.',
            'aadhar_number.unique' => 'Aadhaar number is already registered.',
            'aadhar_number.regex' => 'Aadhaar number must be exactly 12 digits.',
            'pan_number.unique' => 'PAN number is already registered.',
            'pan_number.regex' => 'PAN number must be in the format: ABCDE1234F.',
            'pincode.digits' => 'Pincode must be exactly 6 digits.',
            'state_id.required' => 'Please select a state.',
            'state_id.exists' => 'Invalid state selected.',
            'district_id.required' => 'Please select a district.',
            'district_id.exists' => 'Invalid district selected.',
            'password.confirmed' => 'Password confirmation does not match.',
            'profile_image.image' => 'Profile image must be a valid image file.',
            'profile_image.mimes' => 'Profile image must be in jpeg, png, jpg, or web format.',
            'profile_image.max' => 'Profile image size should not exceed 500KB.',
        ];

        // Validate input data
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle profile image upload using ImageManager and WebpEncoder
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $manager = new ImageManager(new Driver());
            $path = 'assets/images/profile/';

            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }

            $uploadedImage = $request->file('profile_image');
            $image = $manager->read($uploadedImage);
            $image->resize(100, 100);
            $image->encode(new WebpEncoder(quality: 65));
            $filename = uniqid() . '.webp';
            $image->save($path . $filename);

            $profileImagePath = $path . $filename;
        }

        // Save user to the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'aadhar_number' => $request->aadhar_number,
            'pan_number' => $request->pan_number,
            'pincode' => $request->pincode,
            'state_id' => $request->state_id,
            'district_id' => $request->district_id,
            'tehsil' => $request->tehsil,
            'shop_name' => $request->shop_name,
            'password' => Hash::make($request->password),
            'profile_image' => $profileImagePath,
            'address' => $request->address,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Retailer registered successfully.',
            'data' => $user,
        ]);
    }



    public function postSignin(Request $request)
    {
        // Validation rules
        $rules = [
            'email_or_phone' => 'required|string',
            'password' => 'required|string|min:6', 
        ];
        
        $customMessages = [
            'email_or_phone.required' => 'Please enter your email or phone number.',
            'password.required' => 'Password is required to login.',
            'password.min' => 'Password must be at least 6 characters long.', 
        ];
        
        // Validate request
        $validator = Validator::make($request->all(), $rules, $customMessages);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        $emailOrPhone = trim($request->email_or_phone);

        // Find user by email or phone number and check if approved
        $user = User::where(function ($query) use ($emailOrPhone) {
                    $query->where('email', $emailOrPhone)
                        ->orWhere('phone_number', $emailOrPhone);
                })
                ->where('status', 'approve')
                ->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials. Please try again.',
            ], 401); 
        }

        Auth::guard('retailer')->login($user);

        return response()->json([
            'status' => true,
            'message' => 'Login successful!',
        ], 200);
    }


    public function dashboard(){
        return view('retailer.dashboard');
    }

    public function recharge(){
        $title = "Recharge";
        $operator = Provider::where(['status' => '1', 'service_id' => 1])->get();
        $state = State::where('status', '1')->get(); 
        return view('retailer.mobile-recharge', compact('title','operator','state'));
    }

    public function DTHrecharge(){
        $title = "DTH Recharge";
        $operator = Provider::where(['status' => '1', 'service_id' => 2])->get();
        $lang = Language::where('status', '1')->get(); 
        return view('retailer.dth-recharge', compact('title','operator','lang'));
    }
    

    public function getRechargePlans($operator_id)
    {
        $plans = RechargePlan::where('status', '1')
                    ->where('provider_id', $operator_id)
                    ->get();

        $groupedPlans = [];

        foreach ($plans as $plan) {
            $categories = json_decode($plan->plan_category, true) ?? [];

            if (!empty($categories)) {
                foreach ($categories as $category) {
                    $groupedPlans[$category][] = $plan;
                }
            } else {
                $groupedPlans['Uncategorized'][] = $plan;
            }
        }

        return response()->json($groupedPlans);
    }


    public function getDthPlans($operator_id)
    {
        $plans = DTHplan::where(['status' => '1' ,'provider_id' => $operator_id])->get();
        return response()->json($plans);
    }

    public function Profile(){
        $title = "Profile";
        $state = State::where('status', '1')->get();
        return view('retailer.profile', compact('title','state')); 
    }

    public function UpdateProfile(){
        $title = "Update Profile";
        $state = State::where('status', '1')->get();
        return view('retailer.update-profile', compact('title','state')); 
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $retailer = Auth::guard('retailer')->user();

        if (!Hash::check($request->old_password, $retailer->password)) {
            return back()->with('error', 'Old password is incorrect.');
        }

        $retailer->password = Hash::make($request->new_password);
        $retailer->save();

        return back()->with('success', 'Password changed successfully!');
    }

    public function logout()
    {
        Auth::guard('retailer')->logout();
        return redirect('/');
    }
}
