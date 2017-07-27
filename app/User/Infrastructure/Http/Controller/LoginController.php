<?php
declare(strict_types=1);

namespace Teamo\User\Infrastructure\Http\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Teamo\Common\Http\Controller;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/my';

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function showLoginForm()
    {
        return view('user.login.login');
    }
}
