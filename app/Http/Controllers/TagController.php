<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tags;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tags::all();
        return response()->json($tags);
    }
    public function store(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tag' => 'required|string|in:Gender,Pose,Head,Arms,Finish,Color',
            'parent_tag_id' => 'nullable|exists:tags,id'
        ]);

        $tag = new Tags();
        $tag->name = $validatedData['name'];
        $tag->tag = $validatedData['tag'];
        $tag->parent_tag_id = $validatedData['parent_tag_id'];

        $imagePath = $request->file('image')->storeAs('public/images/tags', $request->file('image')->hashName());
        $tag->image = str_replace('public/', '', $imagePath); // Adjust the path for storage in the database

        $tag->save();


        return response()->json(['message' => 'Tag created successfully'], 200);
    }
    // PARENT TAG
    public function tagsByType()
    {
        $tagsByType = Tags::select('tag', DB::raw('group_concat(id) as ids, group_concat(name) as names, group_concat(image) as images'))
            ->whereIn('tag', ['Gender', 'Finish', 'Arms'])
            ->groupBy('tag')
            ->get();
        return response()->json($tagsByType);
    }

    // CHILDREN TAG
    public function tagsByTypePose($selectedFinishTagId)
    {
        $tagsByTypePose = Tags::select('tag', DB::raw('group_concat(name) as names, group_concat(image) as images'))
            ->whereIn('tag', ['Head',  'Color', 'Pose'])
            ->where('parent_tag_id', $selectedFinishTagId)
            ->groupBy('tag')
            ->get();
        return response()->json($tagsByTypePose);
    }


    public function tagsByTypeAdmin()
    {
        $tags = Tags::all();

        $tagsByType = Tags::select('tag', DB::raw('group_concat(name) as tags'))
            ->groupBy('tag')
            ->get();
        return response()->json($tagsByType);
    }


}
