<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    public function districts(){
        $title = "Districts";
        $nav   = "districts";
        $state = State::select('id', 'name', 'status')->where('status', '1')->orderBy('id', 'asc')->get();
        //return $state;
        return view('admin.tools.districts', compact('title', 'nav', 'state'));
    }

    public function addDistrict(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:districts',
            'code' => 'nullable|string|max:255|unique:districts',
            'state_id'    => 'required|exists:states,id' 
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors()->all(),
            ], 422);
        }
    
        $district = District::create([
            'state_id' => $request->state_id,
            'name'  => $request->name,
            'code'  => $request->code,
        ]);
    
        return response()->json([
            'status'  => true,
            'message' => 'District added successfully!', 
            'data'    => $district
        ], 201);
    }

    public function getDistricts()
    {
        $districts =District::with('state')->orderBy('id', 'desc')->get();
        return response()->json($districts);
    }

    public function updateDistrict(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'state_id' => 'required|exists:states,id',
        ]);

        $district = District::findOrFail($id);
        $district->name = $request->name;
        $district->code = $request->code;
        $district->state_id = $request->state_id;
        $district->save();

        return response()->json(['success' => true, 'message' => 'District updated successfully']);
    }


    public function updateDistrictStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $district = District::findOrFail($id);
        $district->status = $request->status;
        $district->save();
        return response()->json(['success' => true, 'message' => 'District status updated successfully']);
    }

    public function deleteDistrict($id)
    {
        $district = District::find($id);
        if (!$district) {
            return response()->json(['success' => false, 'message' => 'District not found']);
        }

        $district->delete();

        return response()->json(['success' => true, 'message' => 'District deleted successfully']);
    }
}
