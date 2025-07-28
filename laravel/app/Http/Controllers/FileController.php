<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function picture(Request $request)
    {
        $user = Auth::user();

        $file = $request->file('file');

        $filename = 'avatar.' . $file->guessExtension();

        $path = 'profile-photo/'. $user->id;

        $file->storeAs('profile-photo/'. $user->id, $filename, 's3');

        $user->update(['photo' => $path . '/' . $filename]);

        $user->refresh();

        return response()->json(['user' => $user->load('profile')]);
    }
}
