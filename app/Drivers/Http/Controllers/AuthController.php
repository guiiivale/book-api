<?php

namespace App\Drivers\Http\Controllers;

use App\Application\Auth\DTOs\LoginDTO;
use App\Application\Auth\UseCases\LoginUser;
use App\Application\Auth\UseCases\LogoutUser;
use App\Domain\Auth\Exceptions\InvalidCredentialsException;
use App\Drivers\Http\Requests\Auth\LoginRequest;
use App\Drivers\Http\Resources\UserResource;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    public function login(LoginRequest $request, LoginUser $loginUser)
    {
        $dto = LoginDTO::fromArray(
            $request->validated(),
            $request->userAgent() ?? 'book-registry-api'
        );

        try {
            $result = $loginUser($dto);

            return $this->response->ok([
                'token_type' => $result['token_type'],
                'access_token' => $result['token'],
                'user' => new UserResource($result['user']),
            ], 'Login successful.');
        } catch (InvalidCredentialsException $e) {
            return $this->response->fail($e->getMessage(), 401);
        }
    }

    public function logout(Request $request, LogoutUser $logoutUser)
    {
        $user = $request->user();

        if ($user) {
            $logoutUser($user);
        }

        return $this->response->ok(null, 'Logout successful.');
    }

    public function me(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return $this->response->fail('Unauthenticated.', 401);
        }

        return $this->response->ok(new UserResource($user));
    }
}
