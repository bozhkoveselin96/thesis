<?php

namespace App\Http\Controllers;

use Google_Service_Classroom;
use App\Services\GoogleClassroomService;
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
     * @return RedirectResponse
     */
    public function redirectToGoogleClassroom(): RedirectResponse
    {
        $service = new GoogleClassroomService();
        $googleClient = $service->getClient();
        return redirect($googleClient->createAuthUrl());
    }

    /**
     * Obtain the information from Google Classroom
     *
     * @throws \Exception
     */
    public function handleGoogleClassroomCallback()
    {
//        TODO: features/google-api -> the next step after connecting to Google Classroom
    }
}
