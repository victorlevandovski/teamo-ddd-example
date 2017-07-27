<?php
declare(strict_types=1);

namespace Teamo\User\Infrastructure\Http\Request;

use Teamo\Common\Http\Request;

class RegisterUserRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'email' => 'required|email|max:255|unique:Teamo\User\Domain\Model\User\User',
            'password' => 'required',
            'timezone',
        ];
    }
}
