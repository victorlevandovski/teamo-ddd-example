<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\FileSystem;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentId;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentManager;
use Teamo\Project\Domain\Model\Project\Attachment\UploadedFile;

class LocalAttachmentManager implements AttachmentManager
{
    /**
     * @param UploadedFile[] $uploadedFiles
     * @return Collection|Attachment[]
     */
    public function attachmentsFromUploadedFiles(array $uploadedFiles): Collection
    {
        $attachments = [];

        foreach ($uploadedFiles as $uploadedFile) {
            $tmpFile = storage_path('tmp') . '/' . $uploadedFile->file();
            $originalName = strip_tags(htmlspecialchars(trim($uploadedFile->name())));

            if (!file_exists($tmpFile) || !$originalName) {
                continue;
            }

            $id = Uuid::uuid4()->toString();

            $fileSystemPath = thumb_dir($id) . '/' . $id . '.att';

            if (Storage::put($fileSystemPath, file_get_contents($tmpFile), FILE_BINARY)) {
                $attachment = new Attachment(new AttachmentId($id), $originalName);

                if ($attachment->type()->isImage()) {
                    $saveAs = pathinfo(thumb_url($id), PATHINFO_BASENAME);
                    save_thumb($tmpFile, public_path('thumbs'), $id, 280, 200, $saveAs);
                }

                $attachments[] = $attachment;
            }

            unlink($tmpFile);
        }

        return new Collection($attachments);
    }
}
