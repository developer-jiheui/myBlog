<?php

use App\Http\Controllers\PortfolioController;
use Illuminate\Http\Request;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Support\Facades\Cache;


use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::post('/send-email', function (\Illuminate\Http\Request $request) {
    try {
        $data = $request->validate([
            'senderName' => 'required|string',
            'senderEmail' => 'required|email',
            'emailContent' => 'required|string',
        ]);

        Log::info("sending email to admin", $data);
        Mail::to('developer.jiheuilee@gmail.com')->send(new ContactMessage($data));
        return response()->json([
            'message' => '✅ Email sent successfully!',
        ]);
    } catch (\Throwable $e) {
        Log::error("❌ Email failed", [
            'error' => $e->getMessage(),
        ]);
        return response()->json([
            'error' => 'Failed to send email.',
            'details' => $e->getMessage()
        ], 500);
    }
});
//PAGES
Route::get('/', [PageController::class, 'show'])->defaults('name', 'home')->name('home');
Route::get('/page/{name}', [PageController::class, 'show'])->name('page.show');

//LOGIN, LOG OUT
Route::get('/login', function () {
    return view('pages.login');
});
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


//USER CRUD
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::middleware('auth')->post('/edit/profile', [UserController::class, 'edit_profile'])->name('edit.profile');
Route::middleware('auth')->put('/profile', [UserController::class, 'update'])
    ->name('profile.update');
//@TODO change the one
Route::middleware('auth')->put('/profile', [UserController::class, 'update'])
    ->name('update.profile');
//ADMIN

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.home');
});
Route::get('/admin/profile', [AdminController::class, 'profile'])
    ->middleware('auth')
    ->name('admin.profile');
Route::post('/admin/update', [AdminController::class, 'update'])->middleware('auth')->name('admin.update');
Route::get('/admin-debug', function () {
    $users = \App\Models\User::all();
    return view('pages.admin', compact('users'));
});
Route::middleware(['auth'])->group(function () {
    Route::post('/admin/promote/{id}', [AdminController::class, 'promote'])->name('admin.promote');
    Route::post('/admin/demote/{id}', [AdminController::class, 'demote'])->name('admin.demote');
    Route::delete('/admin/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');
});
//USER
Route::get('/profile', function () {
    return view('pages.profile');
})->middleware('auth')->name('profile');

//-----------------


Route::middleware('auth')->get('/edit/home', function () {
    return view('edit.home');
})->name('edit.home');
Route::middleware('auth')->get('/edit/portfolio', function () {
    return view('edit.portfolio');
})->name('edit.portfolio');
/* Route::middleware('auth')->get('/edit/blog', function () {
    return view('edit.blog');
})->name('edit.blog'); */

//TESTIMONIALS
Route::middleware('auth')->group(function () {
    Route::get('/testimonials', [TestimonialController::class, 'dashboard'])->name('testimonials.dashboard');
    Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');

    Route::get('/testimonials/{t}/edit', [TestimonialController::class, 'edit'])->whereNumber('t')->name('testimonials.edit');
    Route::put('/testimonials/{t}', [TestimonialController::class, 'update'])->whereNumber('t')->name('testimonials.update');
    Route::post('/testimonials/{t}/pin', [TestimonialController::class, 'togglePin'])->whereNumber('t')->name('testimonials.pin');
    Route::post('/testimonials/{t}/status', [TestimonialController::class, 'updateStatus'])
        ->name('testimonials.status');
});


//PORTFOLIOS

Route::get('/portfolios', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::post('/portfolios', [PortfolioController::class, 'store'])->middleware('auth')->name('portfolio.store');
Route::put('/portfolios/{portfolio}', [PortfolioController::class, 'update'])->middleware('auth')->name('portfolio.update');
Route::delete('/portfolios/{portfolio}', [PortfolioController::class, 'destroy'])->middleware('auth')->name('portfolio.destroy');

Route::post('/portfolios/{portfolio}/like', [PortfolioController::class, 'like'])
    ->middleware('auth')
    ->name('portfolio.like');


Route::get('/page/portfoliofull', function () {
    return view('page.portfoliofull');
})->name('page.portfoliofull');
Route::middleware(['auth'])->get('/edit/portfolio', function () {
    return view('edit.portfolio');
})->name('edit.portfolio');
Route::middleware(['auth'])->delete('/edit/portfolio/delete', 'App\Http\Controllers\PortfolioController@delete')->name('edit.portfolio.delete');
Route::middleware(['auth'])->patch('/edit/portfolio/update', 'App\Http\Controllers\PortfolioController@update')->name('edit.portfolio.update');
Route::middleware(['auth'])->post('/edit/portfolio/create', 'App\Http\Controllers\PortfolioController@create')->name('edit.portfolio.create');
Route::middleware(['auth'])->post('/portfolio/like', 'App\Http\Controllers\PortfolioController@like')->name('page.portfolio.like');


Route::get('/edit/blog', function () {
    return view('edit.blog');
})->name('edit.blog');
Route::delete('/edit/blog/delete', 'App\Http\Controllers\BlogController@delete')->name('edit.blog.delete');
Route::patch('/edit/blog/update', 'App\Http\Controllers\BlogController@edit')->name('edit.blog.update');
Route::post('/edit/blog/create', 'App\Http\Controllers\BlogController@create')->name('edit.blog.create');
Route::middleware(['auth'])->post('/page/blog/comment', 'App\Http\Controllers\CommentController@create')->name('page.blog.comment');
Route::patch('/page/blog/comment/update', 'App\Http\Controllers\CommentController@edit')->name('page.blog.comment.update');
Route::delete('/page/blog/comment/delete', 'App\Http\Controllers\CommentController@delete')->name('page.blog.comment.delete');

//route to set up blogfull
Route::get('/page/blogfull', function () {
    return view('page.blogfull');
})->name('page.blogfull');


//MODAL

Route::post('/modal/dismiss', function (Request $request) {
    $key = (string)$request->input('key', '');
    if ($key === '') {
        return response()->json(['ok' => false, 'error' => 'Missing key'], 422);
    }
    $dismissed = session('dismissed_modals', []);
    $dismissed[$key] = true;
    session(['dismissed_modals' => $dismissed]);

    return response()->json(['ok' => true]);
})->name('modal.dismiss');


//@TODO: REMOVE THIS ONCE EVERYTHING IS UPDATED
Route::post('/guest-login', function () {
    $guestUser = \App\Models\User::firstOrCreate(
        ['email' => 'demo@example.com'],
        [
            'name' => 'Guest User',
            'password' => bcrypt('Demo@1234'),
            'USER_TYPE' => 1,
        ]
    );

    Auth::login($guestUser);

    return redirect()->route('page.show', ['name' => 'home'])
        ->with('success', 'You are logged in as a guest!');
})->name('guest.login');


//TEST
// routes/web.php (temporary)
Route::get('/debug-env', function () {
    return [
        'token_present' => (bool)config('services.notion.token'),
        'db_present' => (bool)config('services.notion.portfolio_db'),
    ];
});

Route::get('/debug-cache-flush', fn() => tap(Cache::forget('notion.portfolio.projects'), fn() => 'ok'));

Route::get('/p', [PortfolioController::class, 'notion'])->name('portfolio.notion');
Route::get('/portfolio', [PortfolioController::class, 'notion'])->name('portfolio.notion');
Route::get('/portfolio/{key}', [PortfolioController::class, 'show'])
    ->where('key', '[-a-zA-Z0-9_]+')
    ->name('page.portfoliofull');
