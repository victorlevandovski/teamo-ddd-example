<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Http\Request;

use Teamo\Common\Http\Request;

class PostCommentRequest extends Request
{
    use Attachments;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'content' => 'required_if:files_list,[]',
        ];
    }

    public function messages()
    {
        return [
            'content.required_if' => trans('validation.required'),
        ];
    }
}
