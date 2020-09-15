<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class IndexUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if ($request->search) {
            $query = '%' . $request->search . '%';
            $users = User::where('first_name', 'like', $query)->orWhere('surname', 'like', $query)->orWhere('phone', 'like', $query)->get();
        } else {
            $users = User::all();
        }

        return response()->json($users);
    }
}
