<?php
declare(strict_types=1);

namespace Teamo\Project\Presentation\Http\Controller;

use Ramsey\Uuid\Uuid;
use Teamo\Common\Application\CommandBus;
use Teamo\Common\Http\Controller;
use Teamo\Project\Application\Command\Discussion\ArchiveDiscussionCommand;
use Teamo\Project\Application\Command\Discussion\PostDiscussionCommentCommand;
use Teamo\Project\Application\Command\Discussion\RemoveAttachmentOfDiscussionCommand;
use Teamo\Project\Application\Command\Discussion\RemoveAttachmentOfDiscussionCommentCommand;
use Teamo\Project\Application\Command\Discussion\RemoveDiscussionCommand;
use Teamo\Project\Application\Command\Discussion\RemoveDiscussionCommentCommand;
use Teamo\Project\Application\Command\Discussion\RestoreDiscussionCommand;
use Teamo\Project\Application\Command\Discussion\StartDiscussionCommand;
use Teamo\Project\Application\Command\Discussion\UpdateDiscussionCommand;
use Teamo\Project\Application\Command\Discussion\UpdateDiscussionCommentCommand;
use Teamo\Project\Application\Query\Discussion\AllDiscussionsQuery;
use Teamo\Project\Application\Query\Discussion\DiscussionQuery;
use Teamo\Project\Application\Query\Discussion\DiscussionQueryHandler;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionCommentRepository;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Presentation\Http\Request\PostCommentRequest;
use Teamo\Project\Presentation\Http\Request\StartDiscussionRequest;
use Teamo\Project\Presentation\Http\Request\UpdateCommentRequest;
use Teamo\Project\Presentation\Http\Request\UpdateDiscussionRequest;

class DiscussionController extends Controller
{
    public function index(string $projectId, DiscussionQueryHandler $queryHandler)
    {
        $query = new AllDiscussionsQuery($projectId, $this->authenticatedId(), false);

        return view('project.discussion.index', [
            'discussionsPayload' => $queryHandler->allDiscussions($query),
        ]);
    }

    public function archive(string $projectId, DiscussionQueryHandler $queryHandler)
    {
        $query = new AllDiscussionsQuery($projectId, $this->authenticatedId(), true);

        return view('project.discussion.archive', [
            'discussionsPayload' => $queryHandler->allDiscussions($query),
        ]);
    }

    public function show(string $projectId, string $discussionId, DiscussionQueryHandler $queryHandler)
    {
        $query = new DiscussionQuery($projectId, $discussionId, $this->authenticatedId());

        return view('project.discussion.show', [
            'discussionPayload' => $queryHandler->discussion($query),
        ]);
    }

    public function create()
    {
        return view('project.discussion.create');
    }

    public function store(string $projectId, StartDiscussionRequest $request, CommandBus $commandBus)
    {
        $discussionId = Uuid::uuid4()->toString();

        $command = new StartDiscussionCommand($projectId, $discussionId, $this->authenticatedId(), $request->get('topic'), $request->get('content'), $request->attachments());
        $commandBus->handle($command);

        return redirect(route('project.discussion.show', [$projectId, $discussionId]));
    }

    public function edit(string $projectId, string $discussionId, DiscussionRepository $discussionRepository)
    {
        return view('project.discussion.edit', [
            'discussion' => $discussionRepository->ofId(new DiscussionId($discussionId), new ProjectId($projectId)),
        ]);
    }

    public function update(string $projectId, string $discussionId, UpdateDiscussionRequest $request, CommandBus $commandBus)
    {
        $command = new UpdateDiscussionCommand($projectId, $discussionId, $this->authenticatedId(), $request->get('topic'), $request->get('content'), $request->attachments());
        $commandBus->handle($command);

        return redirect(route('project.discussion.show', [$projectId, $discussionId]))
            ->with('success', trans('app.flash_discussion_updated'));
    }

    public function archiveDiscussion(string $projectId, string $discussionId, CommandBus $commandBus)
    {
        $command = new ArchiveDiscussionCommand($projectId, $discussionId, $this->authenticatedId());
        $commandBus->handle($command);

        return redirect(route('project.discussion.index', $projectId))->with('success', trans('app.flash_discussion_archived'));
    }

    public function restoreDiscussion(string $projectId, string $discussionId, CommandBus $commandBus)
    {
        $command = new RestoreDiscussionCommand($projectId, $discussionId, $this->authenticatedId());
        $commandBus->handle($command);

        return redirect(route('project.discussion.show', [$projectId, $discussionId]))
            ->with('success', trans('app.flash_discussion_restored'));
    }

    public function destroy(string $projectId, string $discussionId, CommandBus $commandBus)
    {
        $command = new RemoveDiscussionCommand($projectId, $discussionId, $this->authenticatedId());
        $commandBus->handle($command);

        $route = strstr(\URL::previous(), 'archive') ? 'archive' : 'index';

        return redirect(route('project.discussion.' . $route, $projectId))->with('success', trans('app.flash_discussion_deleted'));
    }

    public function ajaxDestroyAttachment(string $projectId, string $discussionId, string $attachmentId, CommandBus $commandBus)
    {
        try {
            $command = new RemoveAttachmentOfDiscussionCommand($projectId, $discussionId, $attachmentId, $this->authenticatedId());
            $commandBus->handle($command);
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()]);
        }
    }


    /* Comments */


    public function storeComment(string $projectId, string $discussionId, PostCommentRequest $request, CommandBus $commandBus)
    {
        $commentId = Uuid::uuid4()->toString();

        $command = new PostDiscussionCommentCommand($projectId, $discussionId, $commentId, $this->authenticatedId(), (string) $request->get('content'), $request->attachments());
        $commandBus->handle($command);

        return redirect(route('project.discussion.show', [$projectId, $discussionId]) . '#comment-' . $commentId);
    }

    public function editComment(string $projectId, string $discussionId, string $commentId, DiscussionCommentRepository $commentRepository)
    {
        return view('project.discussion.edit_comment', [
            'discussionId' => $discussionId,
            'comment' => $commentRepository->ofId(new CommentId($commentId), new DiscussionId($discussionId)),
        ]);
    }

    public function updateComment(string $projectId, string $discussionId, string $commentId, UpdateCommentRequest $request, CommandBus $commandBus)
    {
        $command = new UpdateDiscussionCommentCommand($projectId, $discussionId, $commentId, $this->authenticatedId(), $request->get('content'));
        $commandBus->handle($command);

        return redirect(route('project.discussion.show', [$projectId, $discussionId]) . '#comment-' . $commentId);
    }

    public function ajaxDestroyComment(string $projectId, string $discussionId, string $commentId, CommandBus $commandBus)
    {
        try {
            $command = new RemoveDiscussionCommentCommand($projectId, $discussionId, $commentId, $this->authenticatedId());
            $commandBus->handle($command);
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()]);
        }
    }

    public function ajaxDestroyCommentAttachment(string $projectId, string $discussionId, string $commentId, string $attachmentId, CommandBus $commandBus)
    {
        try {
            $command = new RemoveAttachmentOfDiscussionCommentCommand($projectId, $discussionId, $commentId, $attachmentId, $this->authenticatedId());
            $commandBus->handle($command);
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()]);
        }
    }
}
