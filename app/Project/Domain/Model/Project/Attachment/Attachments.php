<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Attachment;

use Illuminate\Support\Collection;

trait Attachments
{
    /**
     * @var Collection
     */
    protected $attachments;

    protected function setAttachments(Collection $attachments = null)
    {
        if (null === $attachments) {
            $attachments = new Collection();
        }

        $this->attachments = $attachments;
    }

    /**
     * @return Collection|Attachment[]
     */
    public function attachments(): Collection
    {
        return $this->attachments;
    }

    public function attach(Attachment $attachment)
    {
        $this->attachments->put($attachment->attachmentId()->id(), $attachment);
    }

    public function removeAttachment(AttachmentId $attachmentId)
    {
        $this->assertAttachmentExists($attachmentId);

        $this->attachments->forget($attachmentId->id());
    }

    protected function assertAttachmentExists(AttachmentId $attachmentId)
    {
        if (!$this->attachments->has($attachmentId->id())) {
            throw new \InvalidArgumentException('Invalid Attachment Id');
        }
    }
}
