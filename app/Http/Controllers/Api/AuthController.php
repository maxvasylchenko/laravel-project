<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function __invoke(AuthRequest $request): JsonResponse
    {
        //        dd($request->validated());
        if (!auth()->attempt($request->validated())) {
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials']);
        }

        $permissions = auth()->user()->hasAnyRole('admin', 'editor') ? ['full'] : ['read'];

        return response()->json([
            'status' => 'success',
            'data' => [
                'token' => auth()->user()->createToken($request->device_name ?? 'api', $permissions)->plainTextToken
            ]
        ]);
    }
}
