<?php
declare(strict_types=1);

namespace Teamo\User\Infrastructure\Http\Request;

use Teamo\Common\Http\Request;

class ChangeUserPasswordRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required',
            'new_password' => 'required',
        ];
    }
}
