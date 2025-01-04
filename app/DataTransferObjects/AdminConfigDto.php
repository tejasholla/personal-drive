<?php

namespace App\DataTransferObjects;

class AdminConfigDto
{

    public function __construct(
        public readonly string $phpMaxUploadSize,
        public readonly string $phpPostMaxSize,
        public readonly int $phpMaxFileUploads
    )
    {
    }
}