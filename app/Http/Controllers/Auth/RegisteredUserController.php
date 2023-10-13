<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function create(): View
    {
        return view('auth.register');
    }


    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string','max:255','unique:users,username','alpha_num'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ],[
            'name.required' => 'Nama tidak boleh kosong',
            'name.min' => 'name minimal harus 3 kata',
            'email.required' => 'data tidak boleh kosong',
            'email.email' => 'Email tidak boleh kosong',
            'email.unique' => 'Email sudah Diguanakan',
            'username.required' => ['Username Tidak Boleh Kosong'],
            'username.unique' => ['Username Telah Digunakan'],
            'username.max' => ['Username Maxsimal 255 karakter'],
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'password minimal 8 karakter.',
            'password.confirmed' => 'Password tidak sama',


        ]);

    $user = new User;
    $user->name = $request->name;
    $user->username = $request->username;
    $user->email = $request->email;
    $user->password = $request->password;

    $user->save();
    // User::create([
    //     'name' => $request->name,
    //     'email' => $request->email,
    //     'noTelp' => $request->telepon,
    //     'password' => Hash::make($request->password),

    // ]);


        $credentials = $request->only('email,password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('login')->with('success','Kamu Berhasil Registrasi');

    }



        // $user = User::create([
        //     'name' => $request->name,
        //     'username' => $request->username,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(RouteServiceProvider::HOME);
    }
