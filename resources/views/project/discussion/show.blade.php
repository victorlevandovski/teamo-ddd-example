<?php /** @var \Teamo\Project\Domain\Model\Project\Discussion\Discussion $discussion */ ?>
<?php
$teamMembers = [];
foreach ($project->teamMembers() as $teamMember) {
    $teamMembers[$teamMember->teamMemberId()->id()] = $teamMember;
}
?>
@extends('layouts.app')

@section('title', $discussion->topic())

@section('scripts')
    <script type="text/javascript" src="/lightbox/js/lightbox.min.js"></script>
    <script type="text/javascript" src="/js/jquery-ui.custom.min.js"></script>
    <script type="text/javascript" src="/js/jquery.fileupload.js"></script>
    <script type="text/javascript" src="/js/jquery.iframe-transport.js"></script>
    <script type="text/javascript" src="/js/discussion.js?v2"></script>
@append

@section('content')

    @include('project.partials.discussion_navigation')

    {!! Form::flash() !!}

    <section class="flying s120 mb30">

        <h2 class="center mb30 fs22">{{ $discussion->topic() }}</h2>

        <img src="{{ avatar_of_id($discussion->author()->id(), 96) }}" class="content-avatar avatar48">

        <div class="discussion-content">

            <p><b>{{ $teamMembers[$discussion->author()->id()]->name() }}</b></p>

            {!! $discussion->content() !!}

            <p class="fs12 c666">
                {{ date_ui($discussion->createdOn()) }}
                <span class="dot">•</span>
                {!! Html::linkRoute('project.discussion.edit', trans('app.edit'), [$selectedProjectId, $discussion->discussionId()->id()], ['class'=>'system']) !!}
                <span class="dot">•</span>
                @if (!$discussion->isArchived())
                    {!! Html::linkRoute('project.discussion.archive_discussion', trans('app.archive_action'), [$selectedProjectId, $discussion->discussionId()->id()], ['class' => 'system']) !!}
                @else
                    {!! Html::linkRoute('project.discussion.restore_discussion', trans('app.restore'), [$selectedProjectId, $discussion->discussionId()->id()], ['class' => 'system']) !!}
                @endif
                <span class="dot">•</span>
                {!! Html::linkRoute('project.discussion.delete', trans('app.delete'), [$selectedProjectId, $discussion->discussionId()->id()],
                ['class' => 'system confirm', 'data-confirm' => trans('app.confirm_delete_discussion')]) !!}
            </p>

        </div>

        @include('project.partials.attachments', ['attachments' => $discussion->attachments(), 'controller' => 'discussion', 'entityId' => $discussion->discussionId()->id()])

        @include('project.partials.comments', ['controller' => 'discussion', 'entityId' => $discussion->discussionId()->id()])

    </section>

@endsection
