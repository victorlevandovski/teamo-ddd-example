<?php
declare(strict_types=1);

namespace Teamo\User\Presentation\Http\Controller;

use Teamo\Common\Http\Controllers\Controller;

class RegistrationController extends Controller
{
    public function register()
    {
        return view('user.registration.register', ['email' => '']);
    }
}
