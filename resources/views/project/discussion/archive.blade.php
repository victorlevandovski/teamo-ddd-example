<?php /** @var \Teamo\Project\Application\Query\Discussion\DiscussionsPayload $discussionsPayload */ ?>
@extends('layouts.app')

@section('title', trans('app.discussions_archive'))

@section('content')

    @include('project.partials.discussion_navigation')

    {!! Form::flash() !!}

    <section class="flying s120 mb30">

        <ul class="mini-list">
            <li class="discussions-list first-item">
                <h2 class="mb0 fs22">
                    {{ trans('app.discussions_archive') }}
                </h2>
            </li>
            @foreach ($discussionsPayload->discussions() as $discussion)
                <li class="discussions-list">
                    <img src="{{ avatar_of_id($discussion->author()->id(), 96) }}" class="avatar avatar48">
                    <div class="fs18">
                        {!! Html::linkRoute('project.discussion.show', $discussion->topic(), [$selectedProjectId, $discussion->discussionId()->id()], ['class' => 'title-link']) !!}
                    </div>
                    <div class="content">
                        {{ content_preview($discussion->content(), 10) }}
                    </div>
                    <div class="fs12 c333">
                        {{ $discussionsPayload->teamMember($discussion->author())->name() }} <span class="dot">•</span> {{ date_ui($discussion->createdOn()) }}
                        <span class="dot">•</span>
                        {!! Html::linkRoute('project.discussion.restore_discussion', trans('app.restore'), [$selectedProjectId, $discussion->discussionId()->id()], ['class' => 'system']) !!}
                        <span class="dot">•</span>
                        {!! Html::linkRoute('project.discussion.delete', trans('app.delete'), [$selectedProjectId, $discussion->discussionId()->id()],
                        ['class' => 'system confirm', 'data-confirm' => trans('app.confirm_delete_discussion')]) !!}
                    </div>
                </li>
            @endforeach
            @if ($discussionsPayload->discussions()->isEmpty())
                <li class="no-items">{{ trans('app.no_discussions') }}</li>
            @endif
        </ul>


    </section>

@endsection
