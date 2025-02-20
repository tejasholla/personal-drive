<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequests\SetupAccountRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SetupController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Admin/Setup');
    }

    public function update(SetupAccountRequest $request): RedirectResponse
    {
        Artisan::call('migrate:fresh');
        $status = false;
        $message = "Error. could not create user. Try re-installing, checking permissions for storage folder";
        if ($user = User::factory()->create([
            'name' => $request->username,
            'is_admin' => 1,
            'password' => bcrypt($request->password),
        ])
        ) {
            $message = "Created User successfully";
            $status = true;
            $request->session()->invalidate(); // Discard the current file-based session
            config(['session.driver' => 'database']); // Switch to database session
            Auth::login($user, true);
            $request->session()->regenerate(); // Start a fresh database-backed session
        }
        session()->flash('status', $status);
        session()->flash('message', $message);

        return redirect()->route('admin-config', ['setupMode' => true]);
    }

    public function setupStorage()
    {
        return Inertia::render('Admin/SetupStorage');
    }


}
