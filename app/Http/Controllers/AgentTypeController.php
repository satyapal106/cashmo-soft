<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentType;
use Illuminate\Support\Facades\Validator;

class AgentTypeController extends Controller
{
    public function AgentType(){
        $title = "Agent Type";
        $nav   = "agent-type";
        return view('admin.tools.agent-type', compact('title', 'nav'));
    }

    public function addAgentType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:agent_types,name'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors() 
            ], 422);
        }
    
        $agent = AgentType::create([
            'name' => $request->name,
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Agent Type added successfully!',
            'data' => $agent
        ], 201);
    }
    

    public function getAgentType()
    {
        $agentType = AgentType::orderBy('id', 'desc')->get();
        return response()->json($agentType);
    }



    public function updateAgentType(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:agent_types'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors() 
            ], 422);
        }
    
        $agent = AgentType::find($id);
        if (!$agent) {
            return response()->json(['error' => 'Agent Type not found!'], 404);
        }
    
        // Use $agent here instead of $agentType
        $agent->update([
            'name' => $request->name,
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Agent Type updated successfully!', 
            'data' => $agent  // Return the updated agent object
        ], 201);
    }
    


    public function updateAgentTypeStatus(Request $request)
    {
        $agent = AgentType::find($request->id);
        if ($agent) {
            $agent->status = $request->status;
            $agent->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }


    public function deleteAgentType($id)
    {
        $agent = AgentType::find($id);
        if (!$agent) {
            return response()->json(['status' => false, 'message' => 'Agent Type not found']);
        }

        $agent->delete();

        return response()->json(['status' => true, 'message' => 'Agent Type deleted successfully']);
    }
}
