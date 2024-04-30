<?php

namespace App\Http\Controllers;
use App\Models\Tags;
use App\Models\Models;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModelController extends Controller
{
    public function index()
    {
        $models = Models::all();
        return response()->json($models);
    }
    public function show($id)
    {
        $model = Models::findOrFail($id);
        return response()->json($model);
    }
    public function store(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'tags' => 'required',
        ]);

        // Save the model with name, image, and tags
        $model = new Models();
        $model->name = $validatedData['name'];
        $model->tags = $validatedData['tags'];
        $model->status = "1";
        $imagePath = $request->file('image')->storeAs('public/images/models', $request->file('image')->hashName());
        $model->images = str_replace('public/', '', $imagePath); // Adjust the path for storage in the database

        $model->save();

        return response()->json(['message' => 'Model added successfully']);
    }
    public function search(Request $request)
    {
        $tags = $request->input('tags');

        if (!is_array($tags)) {
            return response()->json(['error' => 'Tags must be an array']);
        }

        $query = DB::table('models');

        foreach ($tags as $tag) {
            $query->whereJsonContains('tags', $tag);
        }

        $models = $query->select('id', 'name', 'images', 'background_color', 'tags')->get();

        return response()->json(['models' => $models]);
    }

}
