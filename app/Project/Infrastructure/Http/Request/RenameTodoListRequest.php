<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Http\Request;

use Teamo\Common\Http\Request;

class RenameTodoListRequest extends Request
{
    use Attachments;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }
}
