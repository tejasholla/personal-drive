<?php

namespace App\Http\Controllers\DriveControllers;

use Inertia\Inertia;

class TestController
{

    public function index()
    {
        return Inertia::render('Drive/DriveHome' );

    }

    public function landing()
    {
        return Inertia::render('Landing' );
    }
}
