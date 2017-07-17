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

        {{--{!! Html::linkRoute('project.discussion.index', trans('app.discussions'), [$project->projectId()->id()]) !!}--}}
        {{--{!! Html::linkRoute('project.todo.index', trans('app.todos'), [$project->projectId()->id()]) !!}--}}
        {{--{!! Html::linkRoute('project.event.index', trans('app.events'), [$project->projectId()->id()]) !!}--}}
        {!! Html::linkRoute('project.project.activity', trans('app.activity_log'), [$project->projectId()->id()]) !!}
    </div>

    {!! Form::flash() !!}

    <div class="row">

        <div class="col-xs-4">
            <div class="flying">
                Discussions
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
