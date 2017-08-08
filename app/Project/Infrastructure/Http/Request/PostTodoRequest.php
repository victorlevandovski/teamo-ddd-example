<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Http\Request;

use Teamo\Common\Http\Request;

class PostTodoRequest extends Request
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

    public function deadline()
    {
        if ($this->get('deadline')) {
            return local2utc(strtotime($this->get('deadline')), 'Y-m-d H:i:s', $this->get('timezone'));
        } else {
            return '';
        }
    }
}
