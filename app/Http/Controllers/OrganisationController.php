<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class OrganisationController extends Controller
{
    // Method to list all organisations
    public function index()
    {
        $organisations = Organisation::all();

        return response()->json([
            'status' => 'success',
            'data' => $organisations
        ], 200);
    }

    // Method to show a single organisation
    public function show($id)
    {
        // Validate the id to ensure it's an integer
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid ID format for organisation'
            ], 400);
        }

        // Fetch the organisation
        $organisation = Organisation::find($id);

        if (!$organisation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Organisation not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $organisation
        ], 200);
    }

    // Method to add a user to an organisation
    public function addUser(Request $request, $orgId)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $organisation = Organisation::findOrFail($orgId);
        $user = User::findOrFail($request->user_id);

        $organisation->users()->attach($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User added to organisation successfully',
        ], 200);
    }
}
