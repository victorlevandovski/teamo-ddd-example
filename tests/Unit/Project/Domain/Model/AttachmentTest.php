<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Domain\Model\Project;

use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentId;
use Tests\TestCase;

class AttachmentTest extends TestCase
{
    public function testAttachmentSetsCorrectType()
    {
        $attachment = new Attachment(new AttachmentId(), 'image.jpg');
        $this->assertTrue($attachment->type()->isImage());
        $this->assertFalse($attachment->type()->isFile());

        $attachment = new Attachment(new AttachmentId(), 'document.txt');
        $this->assertTrue($attachment->type()->isFile());
        $this->assertFalse($attachment->type()->isImage());
    }
}
