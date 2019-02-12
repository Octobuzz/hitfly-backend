<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Social;
use App\Services\SocialAccountService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Socialite;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialController extends Controller
{
	use ApiResponseTrait;

	/**
     * Redirect to provider for authentication
     *
     * @param $driver
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($driver)
    {
        if( ! ( config()->has("services.{$driver}" ) ) ){
            return $this->sendFailedResponse("Драйвер {$driver} не поддерживается", 204);
        }

        try {
            return Socialite::with($driver)->redirect();
        } catch (Exception $e){
            return $this->sendFailedResponse( $e->getMessage() );
        }
    }

    /**
     * Login social user
     *
     * @param string $provider
     * @return
     */
    public function handleProviderCallback($provider, SocialAccountService $service)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }

        //return $this->loginOrRegisterBySocials($socialUser, $provider);

        $user = $service->loginOrRegisterBySocials($socialUser, $provider);
        auth('api')->login($user);
        dd(Auth::user());
        return redirect()->to('/home');
    }

    /**
     * @param Social $socialUser
     * @param string $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginOrRegisterBySocials($socialUser, $provider)
    {
        $authSocial = Social::updateOrCreate(
        	[
        		'social_id' => $socialUser->id, 
        		'social_driver' => $provider,
        		],
        	[
        	    'name' => $socialUser->name,
                'avatar' => $socialUser->avatar,
            ]);
      
        try {
        	$user = $authSocial->user();
            $token = JWTAuth::fromUser($user);

        } catch (JWTException $e) {
            return $this->sendFailedResponse('Could not create token', 302);
        }

        return $user ? $this->returnAccessData($token, $user) : $this->redirectToRegisterForm($authSocial);

    }

    public function redirectToRegisterForm($socialUser)
    {
        return view('auth.registerBySocials', [ 'socialUser' => $socialUser]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function returnAccessData($token, $user)
    {
       return [
            'digicoapp_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()*10,
            'name' => $user->name,
            'avatar' => $user->avatar,
        ];
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth('api')->logout();
        return $this->sendSuccessResponse("Successfully logged out", 200);
    }
    
}
