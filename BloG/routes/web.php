<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Models\Article;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ArticleController::class , 'index']);

Route::get('/dashboard', function () {
    $articles = Article::where('user_id', auth()->id())->paginate();
    return view('dashboard', compact('articles'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->resource('dashboard/articles', ArticleController::class)->except(['index', 'show']);
Route::resource('articles', ArticleController::class)->only(['index', 'show']);

require __DIR__ . '/auth.php';
