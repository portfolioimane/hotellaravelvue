<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AmenitiesController extends Controller
{
    public function index()
    {
        // Fetch all amenities
        $amenities = Amenity::all();
        Log::info('Fetched all amenities', ['amenities' => $amenities]);
        return response()->json($amenities);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create the new amenity
        $amenity = Amenity::create($request->all());

        Log::info('Amenity created', ['amenity' => $amenity]);
        return response()->json($amenity, 201);
    }

    public function show($id)
    {
        // Fetch a single amenity by ID
        $amenity = Amenity::findOrFail($id);
        Log::info('Fetched amenity by ID', ['amenity' => $amenity]);
        return response()->json($amenity);
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'icon' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed on update', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the amenity
        $amenity = Amenity::findOrFail($id);
        $amenity->update($request->all());

        Log::info('Amenity updated', ['amenity' => $amenity]);
        return response()->json($amenity);
    }

    public function destroy($id)
    {
        // Find the amenity
        $amenity = Amenity::findOrFail($id);

        // Delete the amenity
        $amenity->delete();

        Log::info('Amenity deleted', ['amenity_id' => $id]);
        return response()->json(null, 204);
    }
}
