<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'tel' => 'required',
            'linkedin' => 'required',
            'github' => 'required'
        ]);
        $user = new User;
        $user->full_name = preg_replace("/[^a-zA-z0-9 ]+/", "", $request->name);
        $user->email = $request->email; 
        $user->tel = $request->tel;
        $user->linkedin_url = $request->linkedin;
        $user->github_url = $request->github;
        $profile = preg_replace("/[^a-zA-Z0-9]+/", "", $request->name);
        $user->profile = UserController::checkProfile($profile);
        $user->save();
        return response()->json(['profile' => $user->profile], 201);
    }

    function checkProfile($profile)
    {
        if (is_null(User::where('profile', $profile)->first()))
        {
            return $profile;
        }
        else
        {
            $i = 0;
            while (!is_null(User::where('profile', $profile.$i)->first()))
            {
                $i++;
            }
            return $profile.$i;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profile = null)
    {
        $user = User::where('profile', $profile)->firstOr(function(){
            return response(['error' => 'Not found'], 404);
        });
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
