<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class SocialAuthController extends Controller
{
    /*************************Facebook login *********************************/

    // Click on the Login button, It will redirect to the Login page of the social network 
    public function facebookRedirect() {
        // return Socialite::driver('facebook')->setScopes([''])->redirect();  // default is scope=email,

        // I have turned on the Email Permission, and now I can get email data
        return Socialite::driver('facebook')->redirect();      
    }

    // if the login is well done, we will be redirected to our page callback to do this treatment below
    public function facebookLoginHandle() {
        try {
            $userData = Socialite::driver('facebook')->user();      // ->stateless(). Get user data from facebook
            // dd($userData);

            $provider = 'facebook';
            return $this->loginHandle($provider, $userData);

        } catch (\Exception $e) {
            echo ($e->getMessage());                        // display the errors
        }
    }

    /*************************Google login *********************************/

    public function googleRedirect() {

        return Socialite::driver('google')->redirect();
    }

    public function googleLoginHandle() {
        try {
            $userData = Socialite::driver('google')->user();      // ->stateless()
            
            $provider = 'google';
            return $this->loginHandle($provider, $userData);

        } catch (\Exception $e) {
            echo ($e->getMessage());
        }
    }

    /*************************Github login *********************************/

    public function githubRedirect() {

        return Socialite::driver('github')->redirect();
    }

    public function githubLoginHandle() {
        try {
            $userData = Socialite::driver('github')->user();      // ->stateless()
            
            $provider = 'github';
            return $this->loginHandle($provider, $userData);

        } catch (\Exception $e) {
            echo ($e->getMessage());
        }
    }


    /*************************My API Service login *********************************/

    public function myApiServiceRedirect(Request $request) {

        $request->session()->put('state', $state = Str::random(40));  // set up state
 
        $query = http_build_query([                 // generate URL-encoded query string
            'client_id'         => config('services.myApiService.client_id'),                    // client_id = 11
            'redirect_uri'      => config('services.myApiService.redirect'),  // client->redirect. This URL below. My web on port 8000
            'response_type'     => 'code',
            'scope' => '',
            'state' => $state,
            // 'prompt' => '', // "none", "consent", or "login"
        ]);
       
        // link of API Service, return a modal for authorization
        // Laravel\Passport\Http\Controllers\AuthorizationController@authorize

        return redirect('http://localhost:8080/oauth/authorize?'.$query);  // OPen Api Service on port 8080
    }

    public function myApiServiceLoginHandle(Request $request) {
        try {
            $code = $request->code;             // get code from Api Service in request of callback
    
            $state = $request->session()->pull('state');    // get back the state
        
            throw_unless(                                   // check state and throw error
                strlen($state) > 0 && $state === $request->state,
                InvalidArgumentException::class,
                'Invalid state value.'
            );

            // require POST from api service => post the form to get response
            $response = Http::asForm()->post('http://localhost:8080/oauth/token', [     
                'grant_type'        => 'authorization_code',
                'client_id'         => config('services.myApiService.client_id'),
                'client_secret'     => config('services.myApiService.client_secret'),
                'redirect_uri'      => config('services.myApiService.redirect'),
                'code' => $code,
            ]);
        
            // return $response->json();
            $tokensResponse = $response->json();    // is an Array
            
            $response = Http::withHeaders([

                'Accept'        => 'application/json',          // this is for Postman to call API. We can let it, it still works.
                'Authorization' => 'Bearer '.$tokensResponse['access_token'],

            ])->get('http://localhost:8080/api/user');      // link api of Api Service to get $user detail
            
            // return $response->json();
            
            $user = $response->json();  // is an Array        

            $provider = 'My API Service';
            return $this->apiServiceLoginHandle($provider, $user);

        } catch (\Exception $e) {
            echo ($e->getMessage());
        }
    }



    /************************* Functions Helpers *****************************/

    public function emailHandler(string $email, string $provider) : string
    {
        return "($provider)_$email";
    }


    public function loginHandle(string $provider, $userData)
    {
        $user = User::updateOrCreate(                       // update or create an user
            [   'provider'     => $provider,                // condition for finding the instance
                'provider_id'  => $userData->id             // if found, update with the 2nd array
            ],                                              // if not found, create with both 1er and 2nd arrays                                           
            [                                               
                'name'      => $userData->name ?? $provider.'_'.$userData->id,  
                'nickname'  => $userData->nickname ?? null,
                'email'     => $this->emailHandler($userData->email, $provider),
                'password'  => Hash::make($provider.'_laravel')
            ]
        );    
        
        // dd($user);
        Auth::login($user);                                     // login to our app with 'user' instance
        $redirectTo = RouteServiceProvider::HOME ?? '/home';    // setup the path = homepage

        return redirect($redirectTo);                           // redirect to Homepage of User
    }


    public function apiServiceLoginHandle(string $provider, array $userData)
    {
        $user = User::updateOrCreate(                       // update or create an user
            [   'provider'     => $provider,                // condition for finding the instance
                'provider_id'  => $userData['id']             // if found, update with the 2nd array
            ],                                              // if not found, create with both 1er and 2nd arrays                                           
            [                                               
                'name'      => $userData['name'] ?? $provider.'_'.$userData['id'],
                'nickname'  => $userData['nickname'] ?? null,
                'email'     => $this->emailHandler($userData['email'], $provider),
                'password'  => Hash::make($provider.'_laravel')
            ]
        );    
        
        Auth::login($user);                                     // login to our app with 'user' instance
        $redirectTo = RouteServiceProvider::HOME ?? '/home';    // setup the path = homepage

        return redirect($redirectTo);                           // redirect to Homepage of User
    }

    
}
