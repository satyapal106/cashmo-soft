<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StateController extends Controller
{
    
    public function States(){
        $title ="State";
        return view('admin.rechargeplan.states', compact('title'));
    }
     
    public function AddState(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);
    
            $circle = State::create([
                'name' => $request->name,
                'code' => $request->code,
            ]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'State added successfully!',
                'data' => $circle
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getStates(){
        $circle = State::where(['status' => '1'])->OrderBy('id', 'desc')->get();
        return response()->json($circle);
    } 

    public function updateState(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $state = State::findOrFail($id);
        $state->name = $request->name;
        $state->code = $request->code;
        $state->save();
        return response()->json(['success' => true, 'message' => 'State updated successfully']);
    }


    public function updateStateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $state = State::findOrFail($id);
        $state->status = $request->status;
        $state->save();
        return response()->json(['success' => true, 'message' => 'State status updated successfully']);
    }

    public function deleteState($id)
    {
        $state = State::find($id);
        if (!$state) {
            return response()->json(['status' => false, 'message' => 'State not found']);
        }
        $state->delete();
        return response()->json(['status' => true, 'message' => 'State deleted successfully']);
    }
    
}