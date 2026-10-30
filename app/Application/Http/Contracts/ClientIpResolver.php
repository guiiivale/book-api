<?php

namespace App\Application\Http\Contracts;

use Illuminate\Http\Request;

interface ClientIpResolver
{
    public function resolve(Request $request): string;
}
