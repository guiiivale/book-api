<?php

namespace App\Drivers\Http\Controllers;

use App\Drivers\Http\Support\ApiResponseService;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    public function __construct(
        protected ApiResponseService $response
    ) {}
}
