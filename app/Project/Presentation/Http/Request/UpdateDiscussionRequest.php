<?php
declare(strict_types=1);

namespace Teamo\Project\Presentation\Http\Request;

use Teamo\Common\Http\Request;

class UpdateDiscussionRequest extends Request
{
    use Attachments;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'topic' => 'required|max:255',
            'content' => 'required',
        ];
    }
}
