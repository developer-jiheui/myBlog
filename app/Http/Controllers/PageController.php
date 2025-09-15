<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PageController extends Controller
{
    public function show($name)
    {
        // List of allowed pages (to prevent errors or unwanted access)
        $pages = ['home', 'bio', 'resume', 'experience', 'portfoliofull', 'blog', 'login', 'register', 'blogfull', 'contact'];
        $admin_pages = ['admin'];
        $user_pages = ['profile'];
        $superAdmin = \App\Models\User::find(1);


        if (in_array($name, $pages)) {
            return view('pages.' . $name, compact('superAdmin'));
        }

        //direct to the pages that only admins can access
        if (in_array($name, $admin_pages)) {
            //check if the user is logged in
            if (Auth::check()) {
                $user = Auth::user();
                //check if the usertype is admin
                if ($user->USER_TYPE === 0) {
                    return view('pages.' . $name, compact('superAdmin'));
                } else {
                    //if unauthorized user get try to access admin page, they will be logged out and then redirect to login page
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Access denied. Please log in again.');
                }
            } else {
                return redirect()->route('login');
            }
        }

        if (in_array($name, $user_pages)) {
            if (Auth::check()) {
                $user = Auth::user();
                return view('pages.' . $name, compact('superAdmin'));
            } else {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Access denied. Please log in again.');
            }
        }
        // If not found, go to home
        return redirect()->route('home')->with('error', 'There is no such page');
    }
}
