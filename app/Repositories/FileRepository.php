<?php

namespace App\Repositories;

use App\Models\File;
use Illuminate\Support\Facades\DB;

class FileRepository
{
    public function insertFiles(array $files): void
    {
        File::insertOrIgnore($files);
    }

}
