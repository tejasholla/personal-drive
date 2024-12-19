<?php

namespace App\Services;

class FileValidationService
{
    private bool $isValid = true;
    private string $errorMessage = '';

    public function validate(string $fileName): void
    {
//        // Check if filename is empty
//        if (empty($fileName)) {
//            dd('Filename cannot be empty');
//            $this->isValid = false;
//            $this->errorMessage = 'Filename cannot be empty';
//            return;
//        }
//
//        // Check filename length
//        if (strlen($fileName) > 255) {
//            dd('Filename is too long (max 255 characters)');
//            $this->isValid = false;
//            $this->errorMessage = 'Filename is too long (max 255 characters)';
//            return;
//        }

        // Check for special characters in filename
//        if (
//            !preg_match('/^[\/\)\(\sa-zA-Z0-9\s._-]+$/', $fileName)
//        ) {
//            $this->isValid = false;
//            $this->errorMessage = 'Filename contains invalid characters';
//        }
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
