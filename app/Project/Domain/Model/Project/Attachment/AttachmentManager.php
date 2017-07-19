<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Attachment;

use Illuminate\Support\Collection;

interface AttachmentManager
{
    /**
     * @param UploadedFile[] $uploadedFiles
     * @return Collection|Attachment[]
     */
    public function attachmentsFromUploadedFiles(array $uploadedFiles): Collection;
}
