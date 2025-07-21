<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    public function languages(){
        $title = "Language";
        $nav   = "language";
        return view('admin.tools.languages', compact('title', 'nav'));
     }
 
 
     public function addLanguage(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'name' => 'required|string|max:255|unique:languages',
             'code' => 'nullable|string|max:255|unique:languages',
        ]);
     
         if ($validator->fails()) {
             return response()->json([
                 'status' => false, 
                 'errors' => $validator->errors()->all(),
             ], 422);
         }
     
         $language = Language::create([
             'name' => $request->name,
             'code' => $request->code,
         ]);
     
         return response()->json([
             'status'  => true,
             'message' => 'Language added successfully!', 
             'data'    => $language
         ], 201);
     }
     
     
 
     public function getLanguages()
     {
         $languages =Language::orderBy('id', 'desc')->get();
         return response()->json($languages);
     }
 
     public function updateLanguage(Request $request, $id)
     {
         $request->validate([
             'name' => 'required|string|max:255',
             'code' => 'nullable|string|max:255',
        ]);
 
         $language = Language::findOrFail($id);
         $language->name = $request->name;
         $language->code = $request->code;
         $language->save();
         return response()->json(['success' => true, 'message' => 'language updated successfully']);
     }
 
     public function updateLanguageStatus(Request $request, $id)
     {
         $request->validate([
             'status' => 'required|boolean',
         ]);
 
         $language = Language::findOrFail($id);
         $language->status = $request->status;
         $language->save();
         return response()->json(['success' => true, 'message' => 'language status updated successfully']);
     }
 
     public function deletelanguage($id)
     {
         $language = Language::find($id);
         if (!$language) {
             return response()->json(['success' => false, 'message' => 'language not found']);
         }
 
         $language->delete();
 
         return response()->json(['success' => true, 'message' => 'language deleted successfully']);
     }
}
