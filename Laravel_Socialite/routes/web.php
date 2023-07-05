<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// use Laravel\Socialite\Facades\Socialite;
// use App\Models\User;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Controllers\SocialAuthController;

// use Laravel\Socialite\Two\FacebookProvider; 
// Day la file define scope=['email'] cua Socialite. 
// Do vay luc nao cung co scope nay tren URL

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
// Auth::routes(['verify'=>true]);   // enable the routes for email verification (Auth/VerificationController.php)


// Social login with facebook, google, github and linkedin

Route::prefix('/auth')->name('auth.')->middleware('guest')->group(function(){

    Route::get('facebook', [SocialAuthController::class, 'facebookRedirect'])->name('facebook');
    Route::get('facebook/callback', [SocialAuthController::class, 'facebookLoginHandle']);

    Route::get('google', [SocialAuthController::class, 'googleRedirect'])->name('google');
    Route::get('google/callback', [SocialAuthController::class, 'googleLoginHandle']);

    Route::get('github', [SocialAuthController::class, 'githubRedirect'])->name('github');
    Route::get('github/callback', [SocialAuthController::class, 'githubLoginHandle']);

    Route::get('linkedin', [SocialAuthController::class, 'linkedinRedirect'])->name('linkedin');
    Route::get('linkedin/callback', [SocialAuthController::class, 'linkedinLoginHandle']);

    Route::get('myApiService', [SocialAuthController::class, 'myApiServiceRedirect'])->name('myApiServiceRedirect');
    Route::get('/apiservice/callback', [SocialAuthController::class, 'myApiServiceLoginHandle']);

});



Route::get('/home', [HomeController::class, 'index'])->name('home');



/*********************** START : Toan bo phan Email verificaiton cua middleware 'verified' ***********************/
        
                                /*******   Gom 3 buoc  *******/

// 1. link nay la ket qua khi yeu cau middleware 'verify'
Route::get('/email/verify', function (Request $request) {       // path ko quan trong
    
    $request->user()->sendEmailVerificationNotification();      // gui email
    return view('auth.verify');                                 // va hien thong bao la da gui email roi, please check mail
                                                                // view cua Laravel UI
})->middleware('auth')->name('verification.notice');


// 2. Sau khi nhan vao link verfiy trong mail thi den link nay. Middleware auth va signed
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();            
    
    // https://laravel.com/api/8.x/Illuminate/Foundation/Auth/EmailVerificationRequest.html
    // Fulfill the email verification request. return void.
    // column email_verified_at dc updated trong database

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

/*
Laravel signed routes allow us to create routes accessible only 
when a signature is passed as a GET parameter for this URL like:
    http://127.0.0.1:8000/registration?expires=1626122220&signature=3ce33cabeec51572f982690a05cbc5c2aa2922eb015a6256319be5d78b3b92c0
    https://laravel.com/docs/10.x/urls

*/


// 3. Gui lai link verify mail trong truong hop ko co mail hay mat link
Route::post('/email/verification-notification', function (Request $request) {

    $request->user()->sendEmailVerificationNotification();      // gui emai

    return back()->with('resent', 'Verification link sent!');   // thong bao da gui lai email

})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');      
// .send => doi thanh .resend
// to limit the request to 6 per minute

/*********************** END : Toan bo phan Email verificaiton cua middleware 'verified' ***********************/










// Lay tat ca Routes cua App
Route::get('routes', function () {
    $routeCollection = Route::getRoutes();      // get collection of routes infos
    // dd($routeCollection);

    echo "<table style='width:100%'>";
    echo "<tr>";
    echo "<td width='10%'><h4>HTTP Method</h4></td>";
    echo "<td width='10%'><h4>Route</h4></td>";
    echo "<td width='10%'><h4>Name</h4></td>";
    echo "<td width='70%'><h4>Corresponding Action</h4></td>";
    echo "</tr>";
    foreach ($routeCollection as $value) {
        echo "<tr>";
        echo "<td>" . $value->methods()[0] . "</td>";
        echo "<td>" . $value->uri() . "</td>";
        echo "<td>" . $value->getName() . "</td>";
        echo "<td>" . $value->getActionName() . "</td>";
        echo "</tr>";
    }
    echo "</table>";
});