<?php

use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;

it('returns a successful JSON response', function () {
    $response = ResponseHelper::json('Success message');

    expect($response)->toBeInstanceOf(JsonResponse::class)
        ->and($response->getData(true))
        ->toMatchArray([
            'status' => true,
            'message' => 'Success message',
        ]);
});

it('returns a failed JSON response', function () {
    $response = ResponseHelper::json('Error message', false);

    expect($response)->toBeInstanceOf(JsonResponse::class)
        ->and($response->getData(true))
        ->toMatchArray([
            'status' => false,
            'message' => 'Error message',
        ]);
});