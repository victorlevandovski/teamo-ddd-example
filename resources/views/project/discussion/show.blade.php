<?php /** @var \Teamo\Project\Application\Query\Discussion\DiscussionPayload $discussionPayload */ ?>
@extends('layouts.app')

@section('title', $discussionPayload->discussion()->topic())

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

        <h2 class="center mb30 fs22">{{ $discussionPayload->discussion()->topic() }}</h2>

        <img src="{{ avatar_of_id($discussionPayload->discussion()->author()->id(), 96) }}" class="content-avatar avatar48">

        <div class="discussion-content">

            <p><b>{{ $discussionPayload->teamMember($discussionPayload->discussion()->author())->name() }}</b></p>

            {!! $discussionPayload->discussion()->content() !!}

            <p class="fs12 c666">
                {{ date_ui($discussionPayload->discussion()->createdOn()) }}
                <span class="dot">•</span>
                {!! Html::linkRoute('project.discussion.edit', trans('app.edit'), [$selectedProjectId, $discussionPayload->discussion()->discussionId()->id()], ['class'=>'system']) !!}
                <span class="dot">•</span>
                @if (!$discussionPayload->discussion()->isArchived())
                    {!! Html::linkRoute('project.discussion.archive_discussion', trans('app.archive_action'), [$selectedProjectId, $discussionPayload->discussion()->discussionId()->id()], ['class' => 'system']) !!}
                @else
                    {!! Html::linkRoute('project.discussion.restore_discussion', trans('app.restore'), [$selectedProjectId, $discussionPayload->discussion()->discussionId()->id()], ['class' => 'system']) !!}
                @endif
                <span class="dot">•</span>
                {!! Html::linkRoute('project.discussion.delete', trans('app.delete'), [$selectedProjectId, $discussionPayload->discussion()->discussionId()->id()],
                ['class' => 'system confirm', 'data-confirm' => trans('app.confirm_delete_discussion')]) !!}
            </p>

        </div>

        @include('project.partials.attachments', ['attachments' => $discussionPayload->discussion()->attachments(), 'controller' => 'discussion', 'entityId' => $discussionPayload->discussion()->discussionId()->id()])

        @include('project.partials.comments', ['payload' => $discussionPayload, 'controller' => 'discussion', 'entityId' => $discussionPayload->discussion()->discussionId()->id()])

    </section>

@endsection
