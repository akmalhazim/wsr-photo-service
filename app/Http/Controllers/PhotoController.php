<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $photos = $user->photos;

        return response()->json($photos);
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
        $this->validate($request, [
            'photo' => 'required|mimes:jpg,jpeg,png'
        ]);
        
        $extension = $request->photo->extension();
        $photoName = $request->photo->name ?? 'Untitled';
        $fileName = sprintf('%s.%s', uniqid(), $extension);
        $request->photo->storeAs('/public/photos', $fileName);
        $url = config('app.url') . '/photos/' . $fileName;

        $user = $request->user();

        $photo = $user->photos()->create([
            'name' => $photoName,
            'url' => $url,
            'users' => []
        ]);

        return $photo;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        return response()->json($photo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        $this->validate($request, [
            'name' => 'string',
            'photo' => 'mimes:jpg,jpeg,png'
        ]);

        if ($request->hasFile('photo')) {
            $extension = $request->photo->extension();
            $photoName = $request->photo->name ?? 'Untitled';
            $fileName = sprintf('%s.%s', uniqid(), $extension);
            $request->photo->storeAs('/public/photos', $fileName);
            $url = config('app.url') . '/photos/' . $fileName;

            $photo->url = $url;
        }

        if ($request->name) {
            $photo->name = $request->name;
        }

        $photo->save();

        return response()->json($photo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Photo $photo)
    {
        $user = $request->user();

        if ($photo->user_id !== $user->id) {
            abort(403);
        }

        $photo->delete();

        return response()->json('', 204);
    }
}
