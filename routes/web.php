<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

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

Route::view('/', 'pages/welcome');
Route::view('/home', 'pages/home')->name('home');
// Route::get('/blog', [PostController::class, 'index'])->name('posts.index');
Route::view('/blog', 'pages/blog')->name('blog');
// Route::view('/blog/{post:slug}', 'pages/show')->name('show.post');
Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'admin\dashboard')->name('dashboard');
    Route::view('category', 'admin\category')->name('category');
    Route::view('post', 'admin\post')->name('post');
    Route::view('profile', 'admin\profile')->name('profile');
});

require __DIR__.'/auth.php';
