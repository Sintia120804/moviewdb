<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function homepage(){
        $movies = Movie::latest()->paginate(6);
        return view('homepage', compact('movies'));
    }
    public function show($id)
    {
        $movie = Movie::findOrFail($id);
        return view('detail', compact('movie'));
    }
    public function create()
    {
        $categories = Category::all();
        return view('create_movie' ,compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'synopsis' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'year' => 'required|integer',
            'actors' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->title);

        $overPath = null;
        if ($request->hasfile('cover_image')) {
            $coverPath = $request->file('cover_image')->store
            ('covers', 'public');
        }

        Movie::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'synopsis' => $validated['synopsis'],
            'category_id' => $validated['category_id'],
            'year' => $validated['year'],
            'actors' => $validated['actors'],
            'cover_image' => $coverPath,
        ]);

        return redirect('/')->with('Success', 'Data Movie Berhasil Disimpan.');
    }
}

