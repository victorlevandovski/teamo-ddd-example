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
        {{--{!! Html::linkRoute('project.event.index', trans('app.events'), [$project->projectId()->id()]) !!}--}}
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
                Events
            </div>
        </div>
    </div>

@endsection
