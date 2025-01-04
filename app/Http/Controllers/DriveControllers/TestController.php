<?php

namespace App\Http\Controllers\DriveControllers;

use Inertia\Inertia;

class TestController
{

    public function index()
    {
        return Inertia::render('Aws/FileManager' );
    }
}
