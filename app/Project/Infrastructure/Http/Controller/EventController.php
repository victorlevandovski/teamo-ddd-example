<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Http\Controller;

use Ramsey\Uuid\Uuid;
use Teamo\Common\Application\CommandBus;
use Teamo\Common\Http\Controller;
use Teamo\Project\Application\Command\Event\ArchiveEventCommand;
use Teamo\Project\Application\Command\Event\PostEventCommentCommand;
use Teamo\Project\Application\Command\Event\RemoveAttachmentOfEventCommentCommand;
use Teamo\Project\Application\Command\Event\RemoveEventCommand;
use Teamo\Project\Application\Command\Event\RemoveEventCommentCommand;
use Teamo\Project\Application\Command\Event\RestoreEventCommand;
use Teamo\Project\Application\Command\Event\ScheduleEventCommand;
use Teamo\Project\Application\Command\Event\UpdateEventCommand;
use Teamo\Project\Application\Command\Event\UpdateEventCommentCommand;
use Teamo\Project\Application\Query\Event\AllEventsQuery;
use Teamo\Project\Application\Query\Event\EventQuery;
use Teamo\Project\Application\Query\Event\EventQueryHandler;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Event\EventCommentRepository;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\Event\EventRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Infrastructure\Http\Request\PostCommentRequest;
use Teamo\Project\Infrastructure\Http\Request\ScheduleEventRequest;
use Teamo\Project\Infrastructure\Http\Request\UpdateCommentRequest;
use Teamo\Project\Infrastructure\Http\Request\UpdateEventRequest;

class EventController extends Controller
{
    public function index(string $projectId, EventQueryHandler $queryHandler)
    {
        $query = new AllEventsQuery($projectId, $this->authenticatedId(), false);

        return view('project.event.index', [
            'eventsPayload' => $queryHandler->allEvents($query),
        ]);
    }

    public function archive(string $projectId, EventQueryHandler $queryHandler)
    {
        $query = new AllEventsQuery($projectId, $this->authenticatedId(), true);

        return view('project.event.archive', [
            'eventsPayload' => $queryHandler->allEvents($query),
        ]);
    }

    public function show(string $projectId, string $eventId, EventQueryHandler $queryHandler)
    {
        $query = new EventQuery($projectId, $eventId, $this->authenticatedId());

        return view('project.event.show', [
            'eventPayload' => $queryHandler->event($query),
        ]);
    }

    public function create()
    {
        return view('project.event.create');
    }

    public function store(string $projectId, ScheduleEventRequest $request, CommandBus $commandBus)
    {
        $eventId = Uuid::uuid4()->toString();

        $command = new ScheduleEventCommand($projectId, $eventId, $this->authenticatedId(), $request->get('name'), (string) $request->get('details'), $request->occursOn());
        $commandBus->handle($command);

        return redirect(route('project.event.show', [$projectId, $eventId]));
    }

    public function edit(string $projectId, string $eventId, EventRepository $eventRepository)
    {
        return view('project.event.edit', [
            'event' => $eventRepository->ofId(new EventId($eventId), new ProjectId($projectId)),
        ]);
    }

    public function update(string $projectId, string $eventId, UpdateEventRequest $request, CommandBus $commandBus)
    {
        $command = new UpdateEventCommand($projectId, $eventId, $this->authenticatedId(), $request->get('name'), $request->get('details'), $request->occursOn());
        $commandBus->handle($command);

        return redirect(route('project.event.show', [$projectId, $eventId]))
            ->with('success', trans('app.flash_event_updated'));
    }

    public function archiveEvent(string $projectId, string $eventId, CommandBus $commandBus)
    {
        $command = new ArchiveEventCommand($projectId, $eventId, $this->authenticatedId());
        $commandBus->handle($command);

        return redirect(route('project.event.index', $projectId))->with('success', trans('app.flash_event_archived'));
    }

    public function restoreEvent(string $projectId, string $eventId, CommandBus $commandBus)
    {
        $command = new RestoreEventCommand($projectId, $eventId, $this->authenticatedId());
        $commandBus->handle($command);

        return redirect(route('project.event.show', [$projectId, $eventId]))
            ->with('success', trans('app.flash_event_restored'));
    }

    public function destroy(string $projectId, string $eventId, CommandBus $commandBus)
    {
        $command = new RemoveEventCommand($projectId, $eventId, $this->authenticatedId());
        $commandBus->handle($command);

        $route = strstr(\URL::previous(), 'archive') ? 'archive' : 'index';

        return redirect(route('project.event.' . $route, $projectId))->with('success', trans('app.flash_event_deleted'));
    }


    /* Comments */


    public function storeComment(string $projectId, string $eventId, PostCommentRequest $request, CommandBus $commandBus)
    {
        $commentId = Uuid::uuid4()->toString();

        $command = new PostEventCommentCommand($projectId, $eventId, $commentId, $this->authenticatedId(), (string) $request->get('content'), $request->attachments());
        $commandBus->handle($command);

        return redirect(route('project.event.show', [$projectId, $eventId]) . '#comment-' . $commentId);
    }

    public function editComment(string $projectId, string $eventId, string $commentId, EventCommentRepository $commentRepository)
    {
        return view('project.event.edit_comment', [
            'eventId' => $eventId,
            'comment' => $commentRepository->ofId(new CommentId($commentId), new EventId($eventId)),
        ]);
    }

    public function updateComment(string $projectId, string $eventId, string $commentId, UpdateCommentRequest $request, CommandBus $commandBus)
    {
        $command = new UpdateEventCommentCommand($projectId, $eventId, $commentId, $this->authenticatedId(), $request->get('content'));
        $commandBus->handle($command);

        return redirect(route('project.event.show', [$projectId, $eventId]) . '#comment-' . $commentId);
    }

    public function ajaxDestroyComment(string $projectId, string $eventId, string $commentId, CommandBus $commandBus)
    {
        try {
            $command = new RemoveEventCommentCommand($projectId, $eventId, $commentId, $this->authenticatedId());
            $commandBus->handle($command);
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()]);
        }
    }

    public function ajaxDestroyCommentAttachment(string $projectId, string $eventId, string $commentId, string $attachmentId, CommandBus $commandBus)
    {
        try {
            $command = new RemoveAttachmentOfEventCommentCommand($projectId, $eventId, $commentId, $attachmentId, $this->authenticatedId());
            $commandBus->handle($command);
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()]);
        }
    }
}
