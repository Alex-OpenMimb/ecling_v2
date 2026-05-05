<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();
            $sessionId = session()->getId();

            if ($user &&
                Hash::check($request->password, $user->password) &&
                $user->status) {
//                $roleId    = $user->roles->first()->id;
//                if ($user->session_id && $user->session_id !== $sessionId && $roleId === 4 ) {
//                    throw ValidationException::withMessages([
//                        'email' => [trans('El usuario ya tiene una sesión activa en otro dispositivo.')],
//                    ]);
//                }
//                if( $roleId === 4 ){
//                    $user->session_id =  $sessionId;
//                    $user->last_session =  Carbon::now();
//                    $user->save();
//                }
                return $user;
            }

            if ($user && !$user->status) {
                throw ValidationException::withMessages([
                    'email' => [trans('Usuario inactivo.')],
                ]);
            }
        });
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });


        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.passwords.forgot');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.passwords.reset', ['request' => $request]);
        });

    }
}
