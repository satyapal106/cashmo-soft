<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function documents()
    {
        $title = "Documents";
        $nav = "documents";
        return view('admin.tools.documents', compact('title', 'nav'));
    }

    public function addDocumentType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:documents,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $document = Document::create([
            'name' => $request->name,
            'status' => 1,
        ]);

        return response()->json(['status' => true, 'message' => 'Document added successfully!', 'data' => $document]);
    }

    public function getDocumentType()
    {
        return response()->json(Document::orderBy('id', 'desc')->get());
    }

    public function updateDocumentType(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:documents,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $document = Document::find($id);
        if (!$document) {
            return response()->json(['status' => false, 'message' => 'Document not found!'], 404);
        }

        $document->update(['name' => $request->name]);

        return response()->json(['status' => true, 'message' => 'Document updated successfully!', 'data' => $document]);
    }

    public function updateDocumentTypeStatus(Request $request)
    {
        $doc = Document::find($request->id);
        if ($doc) {
            $doc->status = $request->status;
            $doc->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }


    public function deleteDocumentType($id)
    {
        $doc = Document::find($id);
        if (!$doc) {
            return response()->json(['status' => false, 'message' => 'Document Type not found']);
        }

        $doc->delete();

        return response()->json(['status' => true, 'message' => 'Document Type deleted successfully']);
    }
}
