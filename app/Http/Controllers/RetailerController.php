<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\State;
use App\Models\Package;
use App\Models\Service;
use App\Models\AgentType;
use App\Models\Document;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class RetailerController extends Controller
{

    public function AddRetailer(Request $request, $id = null)
    {
        if ($id == "") {
            $title = "Add Retailer";
            $user = new User();
            $message = "Retailer added successfully";
        } else {
            $title = "Update Retailer";
            $user = User::findOrFail($id);
            $message = "Retailer updated successfully";
        }
    
        if ($request->isMethod('post')) {
            $data = $request->all();
    
            $rules = [
                'name'             => 'required|string|max:255',
                'email'            => 'required|email|max:255|unique:users,email,' . $id,
                'aadhar_number'    => 'required|string|max:255|unique:users,aadhar_number,' . $id,
                'pan_number'       => 'nullable|string|max:255|unique:users,pan_number,' . $id,
                'pincode'          => 'required|string|max:255',
                'profile_image'    => $id ? 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048' : 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
                'aadhar_front_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
                'aadhar_back_image'  => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
                'pan_card_image'     => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
                'password'         => $id ? 'nullable|string|min:6' : 'required|string|min:6',
                'phone_number'     => 'nullable|string|max:255',
                'address'          => 'nullable|string|max:255',
                'locality'         => 'nullable|string|max:255',
                'state_id'         => 'nullable|integer',
                'district_id'      => 'nullable|integer',
                'tehsil'           => 'nullable|string|max:255',
                'shop_name'        => 'nullable|string|max:255',
                'member_type'      => 'nullable|string|max:255',
                'package_id'       => 'nullable|integer',
                'status'           => 'nullable|in:pending,approve,reject',
                'services'         => 'nullable|array',
                'services.*'       => 'exists:services,id',
            ];
    
            $validator = Validator::make($data, $rules);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            $imageFields = ['profile_image', 'aadhar_front_image', 'aadhar_back_image', 'pan_card_image'];
            $manager = new ImageManager(new Driver());
            $path = 'assets/images/retailers/';
            if (!is_dir($path)) mkdir($path, 0755, true);
    
            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    $image = $manager->read($request->file($field));
                    $image->resize(300, 300); // Optional resizing
                    $image->encode(new WebpEncoder(quality: 70));
                    $filename = uniqid($field . '_') . '.webp';
                    $image->save($path . $filename);
                    $data[$field] = $path . $filename;
                } elseif ($id) {
                    unset($data[$field]); // prevent overwrite on update if not uploaded
                }
            }
    
            // Handle password
            if ($id == "") {
                $data['password'] = Hash::make($data['password']);
                $newUser = User::create($data);
            } else {
                if (!empty($data['password'])) {
                    $data['password'] = Hash::make($data['password']);
                } else {
                    unset($data['password']);
                }
                $user->update($data);
                $newUser = $user;
            }
    
            // Sync services
            $newUser->services()->sync($data['services'] ?? []);
    
            return redirect()->back()->with('success', $message);
        }
    
        $state = State::where('status', '1')->get();
        $packages = Package::where('status', '1')->get();
        $services = Service::where('status', '1')->get();
        $agentType = AgentType::where('status', '1')->get();
        return view('admin.retailer.add-retailer', compact('title', 'user', 'state', 'packages', 'services', 'agentType'));
    }


    public function AllRetailer(){
        $title = "All Retailer List";
        $user  = User::select(['id','name', 'email' ,'phone_number', 'aadhar_number', 'pan_number', 'address', 'pincode', 'shop_name','status'])->get();
        //return $user;
        return view('admin.retailer.all-retailer', compact('title', 'user'));
    }
    
    public function updateRetailerStatus(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            $user->status = $request->status;
            $user->save();
    
            return response()->json(['success' => true, 'message' => 'Retailer status updated successfully.']);
        }
        
        return response()->json(['success' => false, 'message' => 'Retailer not found.']);
    }

    public function KycVerification($id)
    {
        $title = "KYC Verification";
        $user = User::where(['id' => $id])->first();
        $document = Document::where('status', 1)
             ->with(['userDocument'])   
             ->get();
        //$document = Document::where('status', '1')->get();
        //return $document;
        return view('admin.retailer.kyc-verification', compact('title', 'user', 'document'));
    }


    public function UploadDocuments(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'document_id' => 'required|exists:documents,id',
            'file' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $userId = $request->user_id;
        $documentId = $request->document_id;

        $manager = new ImageManager(new Driver());
        
        $path = 'assets/uploads/documents/';

        // Create directory if it doesn't exist
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $uploadedImage = $request->file('file');
        $image = $manager->read($uploadedImage);

        // Resize and encode to webp
        $image->resize(600, 600, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $image->encode(new WebpEncoder(), 75); // 75% quality

        $filename = uniqid('doc_') . '.webp';
        $imagePath = $path . $filename;

        // Get existing record or create new
        $document = UserDocument::firstOrNew([
            'user_id' => $userId,
            'document_id' => $documentId,
        ]);

        // Delete old file if exists
        if ($document->file && file_exists($document->file)) {
            unlink($document->file);
        }

        // Save new file path
        $image->save($imagePath);
        $document->file = $imagePath;
        $document->save();

        return redirect()->back()->with('success', 'Document uploaded successfully!');

    }
}
