<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Services\UserService;

class AuthController extends Controller
{
    protected $userService;

    /**
     * Initializes a new instance of the AuthController class.
     *
     * @param UserService $userService An instance of the UserService class.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handles user registration by validating the request data and calling the UserService to perform the registration logic.
     *
     * @param StoreUserRequest $request The request object containing user registration data.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the registration result.
     */
    public function register(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        // Call the UserService to handle the registration logic
        $result = $this->userService->register($validatedData);

        return response()->json($result, 201);
    }

    /**
     * Handles user login by validating credentials and generating a JWT token.
     *
     * @param LoginRequest $request The request object containing login credentials.
     * @throws JWTException If an error occurs while creating the JWT token.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the JWT token or an error message.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('login', 'password');
        // Check if the login input is an email or a username
        $fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        try {
            $inputCredentials = [
                $fieldType => $credentials['login'],
                'password' => $credentials['password']
            ];


            if (! $token = JWTAuth::attempt($inputCredentials)) {
                return response()->json(['error' => 'Invalid Credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json(compact('token'));
    }

    /**
     * Handles user logout by invalidating the current session.
     *
     * @param Request $request The request object containing the logout request.
     * @return \Illuminate\Http\JsonResponse A JSON response containing a success message.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Retrieves the authenticated user and returns a JSON response.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the authenticated user.
     */
    public function me()
    {
        return response()->json(Auth::user());
    }
}
