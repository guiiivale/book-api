<?php

namespace App\Infrastructure\Http;

use App\Application\Http\Contracts\ClientIpResolver;
use Illuminate\Http\Request;

final class IlluminateClientIpResolver implements ClientIpResolver
{
    public function resolve(Request $request): string
    {
        $ip = $request->ip();

        if (is_string($ip) && $ip !== '') {
            return $ip;
        }

        return (string) config('auth_limits.fallback_client_ip', '0.0.0.0');
    }
}
