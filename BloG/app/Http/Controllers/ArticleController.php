<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with(['user', 'tags'])->paginate();
        return view('articles.index', [
            'articles' => $articles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');
        $tags = Tag::pluck('name', 'id');

        return view('articles.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:articles,id',
            'excerpt' => 'required|string',
            'description' => 'required|string',
            'status' => 'in:on',
            'category_id' => 'required|integer|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => ['integer', Rule::exists('tags', 'id')]
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        $article = Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'description' => $request->description,
            'status' => $request->status === 'on',
            'user_id' => auth()->user()->id,
            'category_id' => $request->category_id
        ]);
        $article->tags()->attach($request->tags);
        return redirect(route('articles.index'))->with('message', 'article has successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('articles.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('articles.edit', array_merge(compact('article'), $this->getFormData()));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255', 'unique:articles,id'],
            'excerpt' => ['required', 'string'],
            'description' => ['required'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags' => ['nullable', 'array']
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'description' => $request->description,
            'status' => $request->status === 'on',
            'user_id' => auth()->user()->id,
            'category_id' => $request->category_id
        ]);
        $article->tags()->sync($request->tags);
        return redirect(route('dashboard'))->with('message', 'updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect(route('dashboard'))->with('message', 'deleted successfully');
    }

    private function getFormData()
    {
        $categories = Category::pluck('name', 'id');
        $tags = Tag::pluck('name', 'id');

        return compact('categories', 'tags');
    }
}
