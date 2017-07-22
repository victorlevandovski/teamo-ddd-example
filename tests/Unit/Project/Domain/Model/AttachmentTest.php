<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Domain\Model\Project;

use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Tests\TestCase;

class AttachmentTest extends TestCase
{
    public function testAttachmentSetsCorrectType()
    {
        $attachment = new Attachment('1', 'image.jpg');
        $this->assertTrue($attachment->type()->isImage());
        $this->assertFalse($attachment->type()->isFile());

        $attachment = new Attachment('2', 'document.txt');
        $this->assertTrue($attachment->type()->isFile());
        $this->assertFalse($attachment->type()->isImage());
    }
}
