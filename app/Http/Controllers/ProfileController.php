<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return view('profile');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        // Deze functionaliteit wordt afgehandeld door Livewire
        return redirect()->route('profile.edit');
    }
=======

class ProfileController extends Controller
{
    //
>>>>>>> c54d3a0a132058ea52b3bf98e137615046e5b687
}
