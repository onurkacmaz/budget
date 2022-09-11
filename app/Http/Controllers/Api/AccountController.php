<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Requests\UpdateProfilePictureRequest;
use App\Http\Resources\AccountResource;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController
{

    public function __construct(public AccountService $accountService){}

    public function view(Request $request): AccountResource {
        return new AccountResource($this->accountService->getAccountById($request->get('id')));
    }

    /**
     * @throws ApiException
     */
    public function update(int $id, UpdateAccountRequest $request): AccountResource {
        return new AccountResource($this->accountService->updateAccount($id, $request->all()));
    }

    public function destroy(int $id): Response {
        $this->accountService->deleteAccount($id);
        return response()->noContent();
    }

    /**
     * @throws ApiException
     */
    public function updateProfilePicture(int $id, UpdateProfilePictureRequest $request): JsonResponse
    {
        $response = $this->accountService->updateProfilePicture($id, $request->get('photo'));
        return response()->json($response);
    }
}
