<?php
declare(strict_types=1);

namespace Teamo\Project\Presentation\Http\Request;

use Teamo\Common\Http\Request;

class StartProjectRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:255',
        ];
    }
}
