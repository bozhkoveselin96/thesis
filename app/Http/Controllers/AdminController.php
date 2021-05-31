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
     * Display a listing of the allowed emails.
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
     * Store a newly email address in allowed_emails.
     *
     * @param StoreEmailRequest $request
     * @return EmailResource
     */
    public function store(StoreEmailRequest $request): EmailResource
    {
        $email = Allow::create($request->validated());
        return new EmailResource($email);
    }

    /**
     * Update the specified email address in allowed_emails.
     *
     * @param Allow $email
     * @param StoreEmailRequest $request
     * @return EmailResource
     */
    public function update(Allow $email, StoreEmailRequest $request): EmailResource
    {
        $email->update($request->validated());
        return new EmailResource($email);
    }

    /**
     * Remove only email address or
     * email address and teacher if the teacher exists.
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

    /**
     * Update the specified teacher in users.
     *
     * @param User  $teacher
     * @param TeacherAccessRequest $request
     * @return UserResource
     */

    public function blockOrUnblockTeacher(User $teacher, TeacherAccessRequest $request): UserResource
    {
        $teacher->update($request->validated());
        return new UserResource($teacher);
    }
}
