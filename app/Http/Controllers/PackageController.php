<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function AddPackage(){
        $title ="Package Settings";
        $services = Service::where('status', '1')->get();
        $packages = Package::where('status', '1')->get();
        //return $packages;
        return view('admin.packages.add-package', compact('title', 'services', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $admin = Auth::guard('admin')->user(); 
    
        $package = new Package();
        $package->name = $request->name;
        $package->added_by = $admin ? $admin->name : 'Unknown'; 
        $package->save();
        return response()->json(['status' => 'success', 'message' => 'Package added successfully']);
    }

    public function PackageUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $package = Package::findOrFail($id);
        $package->name = $request->name;
        $package->save();

        return response()->json(['status' => 'success', 'message' => 'Package updated successfully']);
    }
    
}
