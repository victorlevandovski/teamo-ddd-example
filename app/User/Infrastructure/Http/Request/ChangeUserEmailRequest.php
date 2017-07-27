<?php
declare(strict_types=1);

namespace Teamo\User\Infrastructure\Http\Request;

use Teamo\Common\Http\Request;

class ChangeUserEmailRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|max:255|unique:Teamo\User\Domain\Model\User\User',
            'password' => 'required',
        ];
    }
}
