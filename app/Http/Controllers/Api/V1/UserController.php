<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{


    public function __construct(private UserService $userService) {}

    /**
     * List Users for Admin..
     */
    public function index(Request $request)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', UserResource::collection($this->userService->getAllUsers($request->all()))->response()->getData());
    }
}
