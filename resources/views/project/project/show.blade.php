@extends('layouts.app')

@section('title', $project->name())

@section('header')
    {{ $project->name() }}
@endsection

@section('content')

    <div class="navigation">
        <div class="pull-right">
            <div class="project-menu">
                {!! Html::linkRoute('project.team.index', trans('app.team'), [$project->projectId()->id()]) !!}
                @if (is_authenticated($project->owner()))
                    {!! Html::linkRoute('project.project.edit', trans('app.project_settings'), [$project->projectId()->id()]) !!}
                @endif
            </div>
        </div>

        {!! Html::linkRoute('project.discussion.index', trans('app.discussions'), [$project->projectId()->id()]) !!}
        {{--{!! Html::linkRoute('project.todo.index', trans('app.todos'), [$project->projectId()->id()]) !!}--}}
        {!! Html::linkRoute('project.event.index', trans('app.events'), [$project->projectId()->id()]) !!}
{{--        {!! Html::linkRoute('project.project.activity', trans('app.activity_log'), [$project->projectId()->id()]) !!}--}}
    </div>

    {!! Form::flash() !!}

    <div class="row">

        <div class="col-xs-4">
            <div class="flying">
                <h2>
                    {!! Html::linkRoute('project.discussion.index', trans('app.discussions'), [$project->projectId()->id()], ['class' => 'c333']) !!}
                    {!! Html::linkRoute('project.discussion.create', trans('app.add'), [$project->projectId()->id()], ['class' => 'btn btn-default btn-add']) !!}
                </h2>

                @if (!$discussions->isEmpty())
                    <ul class="mini-list">
                        @foreach ($discussions as $discussion)
                            {{--<li class="discussions-mini-list {{ (!$discussion->is_read || $discussion->unread_comments_count) ? 'unread' : '' }}">--}}
                            <li class="discussions-mini-list">
                                <img src="{{ avatar_of_id($discussion->author()->id(), 48) }}" class="avatar avatar24">
                                {{--{!! Html::link($discussion->url, $discussion->topic(), ['class' => 'title-link']) !!}--}}
                                {!! Html::linkRoute('project.discussion.show', $discussion->topic(), [$selectedProjectId, $discussion->discussionId()->id()], ['class' => 'title-link']) !!}
                                <div class="content">
                                    {{ content_preview($discussion->content(), 6) }}
                                </div>
                                {{--@if ($discussion->unread_comments_count)--}}
                                    {{--<span class="unread-info">--}}
                                        {{--{!! Html::link($discussion->url.'?unread=true', $discussion->unread_comments_count_ui, ['class' => 'title-link']) !!}--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            </li>
                        @endforeach
                        <li class="discussions-mini-list last-item">
                            {!! Html::linkRoute('project.discussion.index', trans('app.all_discussions'), [$selectedProjectId], ['class' => 'system']) !!}
                        </li>
                    </ul>
                @else
                    <div class="fs12 c666 mt20">{!! trans('app.no_discussions_invitation') !!}</div>
                @endif
            </div>
        </div>

        <div class="col-xs-4">
            <div class="todos flying">
                Todo lists
            </div>
        </div>

        <div class="col-xs-4">
            <div class="events flying">
                <h2>
                    {!! Html::linkRoute('project.event.index', trans('app.events'), [$selectedProjectId], ['class' => 'c333']) !!}
                    {!! Html::linkRoute('project.event.create', trans('app.add'), [$selectedProjectId], ['class' => 'btn btn-default btn-add']) !!}
                </h2>
                @if (!$events->isEmpty())
                    <ul class="mini-list">
                        @foreach ($events as $event)
                            <li class="events-mini-list">
                                <div class="events-list-icon event-icon">
                                    <div class="day">
                                        {{ utc2local($event->occursOn(), 'j', $userTimezone) }}
                                    </div>
                                </div>
                                {!! Html::linkRoute('project.event.show', $event->name(), [$selectedProjectId, $event->eventId()->id()], ['class' => 'title-link']) !!}
                                <div class="content">
                                    {{ event_date_time_ui($event->occursOn(), $userTimeFormat, $userTimezone) }}
                                    {{--@if ($event->soon)--}}
                                        {{--<b class="soon">{{ $event->soon }}</b>--}}
                                    {{--@endif--}}
                                </div>
                                {{--@if ($event->unread_comments_count)--}}
                                    {{--<span class="unread-info">--}}
                                        {{--{!! Html::linkRoute('event.show', $event->unread_comments_count_ui,--}}
                                        {{--[$project, $event, 'unread' => true]) !!}--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            </li>
                        @endforeach
                        <li class="last-item">
                            {!! Html::linkRoute('project.event.index', trans('app.all_events'), [$selectedProjectId], ['class' => 'system']) !!}
                        </li>
                    </ul>
                @else
                    {{--@if ($events_exist)--}}
                    <div class="no-items">{{ trans('app.no_events') }}</div>
                    {{--@else--}}
                        {{--<div class="fs12 c666 mt20">{!! trans('app.no_events_invitation') !!}</div>--}}
                    {{--@endif--}}
                @endif

                {{--@if ($events->isEmpty() && $events_exist)--}}
                    {{--<div class="mt10">--}}
                        {{--{!! Html::linkRoute('event.index', trans('app.past_events'), [$project], ['class' => 'system']) !!}--}}
                    {{--</div>--}}
                {{--@endif--}}
            </div>
        </div>
    </div>

@endsection
