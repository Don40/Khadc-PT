<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
       public function login(Request $request) {
        $incomingFields = $request->validate([
            'loginname' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['name' => $incomingFields['loginname'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();
       

        return redirect('/');
    }
        return back()->withErrors(['login_error' => 'Invalid username or password.']);
}


       public function logout() {
        auth()->logout();
        return redirect('/');
    }
    
        public function register(Request $request) {
            $incomingFields = $request->validate([
                'name' => ['required', 'min:3', 'max:10', Rule::unique('users', 'name')],
                'email' => ['required', 'email', Rule::unique('users', 'email')],
                'password' => ['required', 'min:8', 'max:200']
            ], [
                'name.required' => 'Username is required.',
                'name.unique' => 'This username is already taken. Please choose another.',
                'email.required' => 'Email is required.',
                'email.email' => 'Enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters long.'
            ]);

            $incomingFields['password'] = bcrypt($incomingFields['password']);
            $user = User::create($incomingFields);
            auth()->login($user);
            return redirect('/');
        }

}
