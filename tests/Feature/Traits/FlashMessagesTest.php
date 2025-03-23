<?php

use Illuminate\Http\RedirectResponse;
use App\Traits\FlashMessages;
use Illuminate\Support\Facades\Session;

beforeEach(function () {
    Session::flush();
    $this->flashMessages = new class {
        use FlashMessages;
    };
});

it('sets success message and redirects back', function () {
    $response = $this->flashMessages->success('Operation successful');

    expect(session()->get('message'))->toBe('Operation successful')
        ->and(session()->get('status'))->toBe(true)
        ->and($response)->toBeInstanceOf(RedirectResponse::class);
});

it('sets error message and redirects back', function () {
    $response = $this->flashMessages->error('Operation failed');

    expect(session()->get('message'))->toBe('Operation failed')
        ->and(session()->get('status'))->toBe(false)
        ->and($response)->toBeInstanceOf(RedirectResponse::class);
});

it('verifies flash messages are actually flashed', function () {
    $this->flashMessages->success('Operation failed');

    expect(session()->has('message'))->toBeTrue()
        ->and(session()->has('status'))->toBeTrue();

    session()->driver()->save(); // Persist the data
    // Clear the session data
    session()->flush();
    session()->regenerate(); // Regenerate the session ID
    session()->start();  // Start a new session
    expect(session()->has('message'))->toBeFalse()
        ->and(session()->has('status'))->toBeFalse();
});