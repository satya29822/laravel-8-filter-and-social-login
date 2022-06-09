<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected $providers = [
        'facebook','google'
    ];

    public function show()
    {
        return view('auth.login');
    }

    public function redirectToProvider($driver)
    {
        // print_r($driver);
        // exit();
        if( ! $this->isProviderAllowed($driver) ) {
            return $this->sendFailedResponse("{$driver} is not currently supported");
        }

        try {
        return Socialite::driver($driver)->stateless()->redirect();
        } catch (Exception $e) {
            // You should show something simple fail message
            return $this->sendFailedResponse($e->getMessage());
        }
    }


    public function handleProviderCallback( $driver )
    {
        try {
            $user = Socialite::driver($driver)->stateless()->user();
        } catch (Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }

        // check for email in returned user
        return empty( $user->email )
            ? $this->sendFailedResponse("No email id returned from {$driver} provider.")
            : $this->loginOrCreateAccount($user, $driver);
    }

    protected function sendSuccessResponse()
    {
        return redirect()->intended('dashboard');
    }

    protected function sendFailedResponse($msg = null)
    {
        return redirect()->route('social.login')
            ->withErrors(['msg' => $msg ?: 'Unable to login, try with another provider to login.']);
    }


    private function isProviderAllowed($driver)
    {
        return in_array($driver, $this->providers) && config()->has("services.{$driver}");
    }
    protected function loginOrCreateAccount($providerUser, $driver) {
        $user = User::where('email', $providerUser->getEmail())->first();
        if( $user ) {
            // update the avatar and provider that might have changed
            // dd($providerUser->getEmail());
                $user->update([
                    'avatar' => $providerUser->avatar,
                    'provider' => $driver,
                    'provider_id' => $providerUser->id,
                    'access_token' => $providerUser->token
                ]);
            } else {
                  if($providerUser->getEmail()){ //Check email exists or not. If exists create a new user
                   $user = User::create([
                      'name' => $providerUser->getName(),
                      'email' => $providerUser->getEmail(),
                      'avatar' => $providerUser->getAvatar(),
                      'provider' => $driver,
                      'provider_id' => $providerUser->getId(),
                      'access_token' => $providerUser->token,
                      'password' => '' // user can use reset password to create a password
                ]);

                 }else{

                //Show message here what you want to show

               }
          }
        Auth::login($user, true);
        return $this->sendSuccessResponse();
    }
    public function add($user, $team, string $email, string $role = null)
    {
        Gate::forUser($user)->authorize('addTeamMember', $team);

        $this->validate($team, $email, $role);

        $newTeamMember = Jetstream::findUserByEmailOrFail($email);

        AddingTeamMember::dispatch($team, $newTeamMember);

        $team->users()->attach(
            $newTeamMember, ['role' => $role]
        );

        TeamMemberAdded::dispatch($team, $newTeamMember);
    }


}
