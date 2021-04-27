<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Allow;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Resources\EmailResource;
use App\Http\Requests\StoreEmailRequest;
use App\Http\Requests\TeacherAccessRequest;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index() {
        $emails = EmailResource::collection(
            Allow::where('email', '!=', Auth::user()->email)
                ->orderBy('created_at', 'DESC')
                ->simplePaginate(5));
        return view('admin.emails', compact('emails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User  $teacher
     * @param TeacherAccessRequest $request
     * @return UserResource
     */

    public function blockOrUnblockTeacher(User $teacher, TeacherAccessRequest $request): UserResource
    {
        $teacher->update($request->only('blocked'));
        return new UserResource($teacher);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmailRequest $request
     * @return EmailResource
     */
    public function creteEmail(StoreEmailRequest $request): EmailResource
    {
        $email = Allow::create($request->only('email'));
        return new EmailResource($email);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Allow $email
     * @param StoreEmailRequest $request
     * @return EmailResource
     */
    public function updateEmail(Allow $email, StoreEmailRequest $request): EmailResource
    {
        $email->update($request->only('email'));
        return new EmailResource($email);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Allow $email
     * @return Response
     */
    public function destroy(Allow $email): Response
    {
        $user = User::where('email', $email->email)->first();
        if (!$user) {
            $email->delete();
        } else {
            \DB::transaction(function () use ($user, $email) {
                $email->delete();
                $user->delete();
            });
        }
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
