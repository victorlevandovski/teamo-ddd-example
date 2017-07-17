@extends('layouts.app')

@section('title', trans('app.project_settings'))

@section('content')

    {!! Form::flash() !!}

    <section class="flying">
        <div class="panel-s">
            {!! Form::model(['name' => $project->name()], ['route' => ['project.project.update', $project->projectId()->id()], 'method' => 'patch']) !!}

            <div class="form-group">
                {!! Form::label('name', trans('app.label_name')) !!}
                <div>
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                </div>
                {!! Form::error('name') !!}
            </div>

            <div class="form-control-submit">
                {!! Form::submit(trans('app.rename'), ['class' => 'btn btn-primary']) !!}
                {!! Html::linkRoute('project.project.show', trans('app.cancel'), $project->projectId()->id()) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </section>

    <div class="separator mt30 mb30"></div>

    @if (is_authenticated($project->owner()))
        <section class="flying center mt30">
            {!! Html::linkRoute('project.project.archive_project', trans('app.archive_project_action'), $project->projectId()->id(),
            ['class' => 'btn btn-info confirm', 'data-confirm' => trans('app.confirm_archive_project')]) !!}
        </section>
    @endif
@endsection
