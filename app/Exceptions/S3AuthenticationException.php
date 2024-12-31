<?php

namespace App\Exceptions;

use Exception;

class S3AuthenticationException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('s3autherror')->with('error', 'Could not log into S3');
    }
}
