<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Crypt;
use Auth;
use Str;

class AuthController extends Controller
{
    public function index()
    {
      if(Auth::check()){
        //If Authenticated redirect to Dashboard
        return redirect('/dashboard');
      }
      //Return to Welcome page
      return view('welcome');
    }

    public function postlogin(Request $request)
    {
        //Get Request Parameters
        $username = $request->email;
        $password = $request->password;
    
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
          //If Username is Email
          Auth::attempt(['email' => $username, 'password' => $password]);
        } else {
          //If Username is Not Email
          Auth::attempt(['username' => $username, 'password' => $password]);
        }

        if(Auth::check()){
          // Get Auth User
          $user = Auth::user();
          //Update timestamps
          $user->touch();  
          //Return to Intended URL
          return redirect()->intended('/dashboard');
          
        }
        //Return to login page with Errors
        return redirect('/')->with('gagal', 'Your Credential not found.');

    }

    public function profile()
    {
        $user = Auth::user();

        if($user->hasRole('super-admin')){
          $roles = Role::all();
        } else {
          $roles = Role::where('name', '<>', 'super-admin')->get();
        }      

        return view('pages.profile', compact(['user', 'roles']));
    }

    public function update(Request $request, User $user)
    {
      $validator = Validator::make($request->all(), [
                              'name' => 'required',
                              'username' => [
                                'required',
                                Rule::unique('users')->ignore($user)
                              ],
                              'email' => [
                                'required',
                                Rule::unique('users')->ignore($user)
                              ],         
                            ]);

      (Auth::user()->hasRole('super-admin')) ? $redirBack = '/administrator/profile' : $redirBack = '/profile';

      if($validator->fails()){
        return redirect($redirBack)->withErrors($validator);
      }

      if($user->id != Auth::id()){
        return redirect($redirBack)->with('gagal', 'You are not Authorize to Edit this Account.');
      }

      $user->update([
                    'email' => $request->email,
                    'username' => $request->username,
                    'name' => Crypt::encrypt($request->name),
                  ]);

      if($request->password != ''){
        $user->password = bcrypt($request->password);
        $user->save();
      }

      if($request->hasFile('avatar')){
        $ext = $request->file('avatar')->getClientOriginalExtension();

        if(in_array(Str::lower($ext), getRestrictedExt())){
          return "FORBIDDEN";
        }
        
        $fileLama = public_path().'/img/users/'.$user->avatar;
        if(!is_dir($fileLama) && file_exists($fileLama)){
          unlink($fileLama);
        }

        $name = Str::slug($user->name).'_'.round(microtime(true)).'.'.$ext;
        $request->file('avatar')->move('img/users/', $name);
        $user->avatar = $name;
        $user->save();

      }

      return redirect($redirBack)->with('sukses', 'Edit Profile Success');
      
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/')->with('sukses', 'Logout success.');  
    }
}
