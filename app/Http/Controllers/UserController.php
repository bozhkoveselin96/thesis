<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $users = UserResource::collection(
                 User::where('email', '!=', Auth::user()->email)
                     ->orderBy('created_at', 'DESC')
                     ->simplePaginate(5));

        return view('admin.teachers', compact('users'));
    }
}
