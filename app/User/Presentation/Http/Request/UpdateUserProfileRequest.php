<?php
declare(strict_types=1);

namespace Teamo\User\Presentation\Http\Request;

use Teamo\Common\Http\Request;

class UpdateUserProfileRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:100',
        ];
    }
}
