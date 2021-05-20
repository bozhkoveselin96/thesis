<?php
namespace App\Services;

use Exception;
use Google_Client;
use Illuminate\Http\Request;
use Google_Service_Classroom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Google\Exception as GoogleException;

class GoogleClassroomService
{
    private Google_Client $client;

    /**
     * @param Request $request
     * @throws Exception
     * @throws GoogleException
     */
    public function __construct(Request $request)
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName('Technical university');
        $this->client->setAuthConfig([
            "web" => [
                'client_id'                     => config('services.google.client_id'),
                'client_secret'                 => config('services.google.client_secret'),
                'redirect_uris'                 => [ config('services.google.redirect_classroom') ],
                'project_id'                    => config('services.google.project_id'),
                'auth_uri'                      => 'https://accounts.google.com/o/oauth2/auth',
                'token_uri'                     => 'https://oauth2.googleapis.com/token',
                'auth_provider_x509_cert_url'   => 'https://www.googleapis.com/oauth2/v1/certs'
            ]
        ]);
        $this->client ->addScope([
            Google_Service_Classroom::CLASSROOM_PROFILE_PHOTOS,
            Google_Service_Classroom::CLASSROOM_PROFILE_EMAILS,
            Google_Service_Classroom::CLASSROOM_TOPICS_READONLY,
            Google_Service_Classroom::CLASSROOM_ROSTERS_READONLY,
            Google_Service_Classroom::CLASSROOM_COURSES_READONLY,
            Google_Service_Classroom::CLASSROOM_PUSH_NOTIFICATIONS,
            Google_Service_Classroom::CLASSROOM_COURSEWORK_STUDENTS,
            Google_Service_Classroom::CLASSROOM_ANNOUNCEMENTS_READONLY,
            Google_Service_Classroom::CLASSROOM_COURSEWORK_ME_READONLY,
            Google_Service_Classroom::CLASSROOM_GUARDIANLINKS_STUDENTS,
            Google_Service_Classroom::CLASSROOM_COURSEWORK_STUDENTS_READONLY,
            Google_Service_Classroom::CLASSROOM_COURSEWORKMATERIALS_READONLY,
            Google_Service_Classroom::CLASSROOM_GUARDIANLINKS_STUDENTS_READONLY,
            Google_Service_Classroom::CLASSROOM_STUDENT_SUBMISSIONS_ME_READONLY,
            Google_Service_Classroom::CLASSROOM_STUDENT_SUBMISSIONS_STUDENTS_READONLY,
        ]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        if (Auth::check()) {
            $this->authorize($request);
        }
    }

    /**
     * Construct and return the service object.
     *
     * @return Google_Service_Classroom
     */
    public function getClassroom(): Google_Service_Classroom
    {
        return new Google_Service_Classroom($this->getClient());
    }

    /**
     * Returns Google Classroom authorization link.
     *
     * @return string
     */
    public function getAuthUrl(): string
    {
        return $this->getClient()->createAuthUrl();
    }

    /**
     * Returns an authorized API client.
     *
     * @return Google_Client the authorized client object
     */
    private function getClient(): Google_Client
    {
        return $this->client;
    }

    /**
     * @param string $code
     * @return string
     * @throws Exception
     */
    private function getAccessToken(string $code): string
    {
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);
        if (array_key_exists('error', $accessToken)) {
            throw new Exception(join(', ', $accessToken));
        }
        return json_encode($accessToken);
    }

    /**
     * Set the access token used for requests.
     *
     * @param string $accessToken
     * @return void
     */
    private function setAccessToken(string $accessToken): void
    {
        $this->client->setAccessToken($accessToken);
    }

    /**
     * Authorize Api client
     *
     * @param Request $request
     * @return void
     * @throws Exception
     */
    private function authorize(Request $request): void
    {
        $this->checkAccessTokenExists($request);
        $this->checkAccessTokenExpired();
    }

    /**
     * Load previously authorized token from a classroom_token property, if it is set.
     * The classroom_token property stores the user's access and refresh tokens.
     *
     * @param Request $request
     * @return void
     * @throws Exception
     */
    private function checkAccessTokenExists(Request $request): void
    {
        if (!Session::get('classroom_token')) {
            if ($request->has('code')) {
                $accessToken = $this->getAccessToken($request->get('code'));
                $this->setAccessToken($accessToken);
                Session::put('classroom_token', $accessToken);
            }
        } else {
            $this->setAccessToken(Session::get('classroom_token'));
        }
    }

    /**
     * If there is no previous token or it's expired.
     * Refresh the token if possible, else fetch a new one.
     *
     * @return void
     */
    private function checkAccessTokenExpired(): void
    {
        if ($this->client->isAccessTokenExpired() && $this->client->getRefreshToken()) {
            $accessToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            $this->setAccessToken(json_encode($accessToken));
            Session::put('classroom_token', $accessToken);
        }
    }
}
