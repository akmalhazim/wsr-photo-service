<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SignUpController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'surname' => 'required|string',
            'phone' => 'required|unique:users|string|min:11|max:11',
            'password' => 'required|string'
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'password' => bcrypt($request->password)
        ]);

        return response()->json($user, 201);
    }
}
