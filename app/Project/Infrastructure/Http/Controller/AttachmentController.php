<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Http\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Teamo\Common\Http\Controller;

class AttachmentController extends Controller
{
    public function ajaxUploadFile(Request $request)
    {
        $response = ['error' => 'File upload failed'];

        if ($request->file('files')) {
            foreach ($request->file('files') as $file) {
                /** @var UploadedFile $file */
                if ($file->isValid()) {
                    $f = storage_path('tmp') . '/' . uniqid('att');
                    $file->move(pathinfo($f, PATHINFO_DIRNAME), pathinfo($f, PATHINFO_BASENAME));
                    $response = [
                        'file' => pathinfo($f, PATHINFO_BASENAME),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            }
        }

        return response()->json($response);
    }

    public function download(string $attachmentId, string $name)
    {
        $file = storage_path('attachments/' . thumb_dir($attachmentId) . '/' . $attachmentId . '.att');

        if (!file_exists($file)) {
            return response(view('errors.404'), 404);
        }

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment;filename=' . $name);

        readfile($file);

        return true;
    }

    public function attachment(string $attachmentId, string $name)
    {
        /*if (!UserProject::where(['user_id' => Auth::id(), 'project_id' => $attachment->project_id])->exists()) {
            return response(view('errors.404'), 404);
        }*/

        $file = storage_path('attachments/' . thumb_dir($attachmentId) . '/' . $attachmentId . '.att');

        if (!file_exists($file)) {
            return response(view('errors.404'), 404);
        }

        /*if ($attachment->type != 'image') {
            return response(view('errors.404'), 404);
        }*/

        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $type = $ext == 'jpg' ? 'jpeg' : $ext;

        header('Pragma: public');
        header('Expires: 0');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($file)) . ' GMT');
        header('Content-type: image/' . $type);
        header('Content-Disposition: inline;');
        header("Content-Length: " . filesize($file));

        readfile($file);

        return true;
    }
}
