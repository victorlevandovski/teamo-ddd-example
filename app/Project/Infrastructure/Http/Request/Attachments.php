<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Http\Request;

trait Attachments
{
    public function attachments()
    {
        $files = [];
        $list = json_decode($this->get('files_list', '[]'), true);

        foreach ($list as $file) {
            $files[$file['file']] = $file['name'];
        }

        return $files;
    }

    abstract public function get($key, $default = null);
}
