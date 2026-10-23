<?php

namespace App\Application\Auth\UseCases;

use App\Models\User;

final class LogoutUser
{
    public function __invoke(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
