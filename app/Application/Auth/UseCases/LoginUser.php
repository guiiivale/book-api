<?php

namespace App\Application\Auth\UseCases;

use App\Application\Auth\Contracts\AuthenticationThrottle;
use App\Application\Auth\DTOs\LoginDTO;
use App\Domain\Auth\Exceptions\InvalidCredentialsException;
use App\Domain\Auth\Exceptions\TooManyAuthenticationAttemptsException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class LoginUser
{
    private const SCOPE = 'login';

    public function __construct(private AuthenticationThrottle $throttle) {}

    /**
     * @return array{user: User, token: string, token_type: string}
     */
    public function __invoke(LoginDTO $dto): array
    {
        $key = $this->throttle->key(self::SCOPE, $dto->email, $dto->ipAddress);

        if ($this->throttle->tooManyAttempts($key, self::SCOPE)) {
            throw new TooManyAuthenticationAttemptsException(
                $this->throttle->availableIn($key)
            );
        }

        $user = User::query()->where('email', $dto->email)->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            $this->throttle->hit($key, self::SCOPE);

            throw new InvalidCredentialsException();
        }

        $this->throttle->clear($key);

        $token = $user->createToken($dto->deviceName)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }
}
