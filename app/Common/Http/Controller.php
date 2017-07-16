<?php

namespace Teamo\Common\Http;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function authenticatedId(): string
    {
        $authId = Auth::id();

        if (!$authId) {
            throw new \Exception('User not authenticated');
        }

        return (string) $authId;
    }
}
