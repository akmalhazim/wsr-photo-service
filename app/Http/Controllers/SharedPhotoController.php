<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;

class SharedPhotoController extends Controller
{
    public function create(User $user, Request $request)
    {
        $this->validate($request, [
            'photos' => 'required',
            'photos.*' => 'required|exists:photos,id'
        ]);

        foreach ($request->photos as $photoId) {
            $photo = Photo::find($photoId);

            // only save if not shared
            if (!in_array($user->id, $photo->users)) {
                $photo->users = [
                    ...$photo->users,
                    $user->id
                ];
                $photo->save();
            }
        }

        // get all shared photos
        $sharedPhotoIds = [];
        $photos = Photo::all();
        
        foreach ($photos as $photo) {
            if (in_array($user->id, $photo->users)) {
                array_push($sharedPhotoIds, $photo->id);
            }
        }

        return response()->json($sharedPhotoIds, 201);
    }
}
