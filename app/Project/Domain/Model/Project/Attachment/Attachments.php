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

    /**
     * @param Collection|Attachment[] $attachments
     */
    protected function setAttachments(Collection $attachments)
    {
        $this->attachments = new Collection();

        foreach ($attachments as $attachment) {
            $this->attachments->put($attachment->id(), $attachment);
        }
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
        $attachments = new Collection($this->attachments->toArray());
        $attachments->put($attachment->id(), $attachment);

        $this->setAttachments($attachments);
    }

    public function removeAttachment(string $id)
    {
        $this->assertAttachmentExists($id);

        $attachments = new Collection($this->attachments->toArray());
        $attachments->forget($id);

        $this->setAttachments($attachments);
    }

    protected function assertAttachmentExists(string $id)
    {
        if (!$this->attachments->has($id)) {
            throw new \InvalidArgumentException('Invalid attachment id');
        }
    }
}
