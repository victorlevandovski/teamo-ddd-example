<?php
declare(strict_types=1);

namespace Teamo\Project\Presentation\Http\Request;

use Teamo\Common\Http\Request;

class ScheduleEventRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'details' => '',
            'day' => 'required|date',
            'hour' => 'required',
            'minute' => 'required',
        ];
    }

    public function occursOn()
    {
        $date = date('Y-m-d ', strtotime($this->get('day'))) . $this->get('hour') . ':' . $this->get('minute') . ':00';

        return local2utc(strtotime($date), 'Y-m-d H:i:s', $this->get('timezone'));
    }
}
