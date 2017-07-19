<?php
declare(strict_types=1);

namespace Teamo\Project\Presentation\Http\Controller;

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
}
