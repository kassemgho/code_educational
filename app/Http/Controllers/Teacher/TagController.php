<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function addTag(Request $request) {
        $request->validate([
            'name' => 'required' 
        ]);
        Tag::create([
            'name' => $request->name
        ]);
        return response()->json([
            'message' => 'tag added succfully'
        ]);
    }
}
