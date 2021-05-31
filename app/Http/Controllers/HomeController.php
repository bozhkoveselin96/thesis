<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\GoogleClassroomService;
use Google\Exception as GoogleException;
use Illuminate\Contracts\Support\Renderable;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Redirect the user to the Google Classroom authentication page.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws GoogleException
     */
    public function redirectToGoogleClassroom(Request $request): RedirectResponse
    {
        $service = new GoogleClassroomService($request);
        return redirect($service->getAuthUrl());
    }

    /**
     * Handle Google Classroom callback.
     *
     * @param Request $request
     * @throws GoogleException
     */
    public function handleGoogleClassroomCallback(Request $request)
    {
        Session::put('connected_with_classroom', true);
        new GoogleClassroomService($request);
        return redirect(route('classroom.courses'));
    }
}
