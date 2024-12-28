<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminConfigController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $message = session('message');
        $status = session('status');

        return Inertia::render('Admin/Config', [
            'settings' => $settings, 'message' => $message, 'status' => $status
        ]);
    }

    public function update(Request $request)
    {
//        $request->validate([
//            'storage_path' => 'required|string|max:255',
//        ]);

        $updateResult = Setting::updateOrCreate(
            ['key' => 'storage_path'],
            ['value' => $request->input('storage_path')]
        );

        if ($updateResult->wasRecentlyCreated || $updateResult->wasChanged()) {
            $message = 'Storage path updated successfully.';
            $status = true;
        } else {
            $message = 'No changes were made.';
            $status = false;
        }

        return to_route('admin-config')->with([
            'message' => $message,
            'status' => $status
        ]);
    }
}
