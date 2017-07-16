@extends('layouts.app')

@section('title', trans('app.projects_archive'))

@section('header')
    {{ trans('app.projects_archive') }}
@endsection

@section('content')

    <div class="navigation">
        {!! Html::linkRoute('project.project.index', trans('app.back_to_projects')) !!}
    </div>

    {!! Form::flash() !!}

    <section style="margin: 0 auto; width: 650px;">

        <?php $projects = $projects->filter(function ($project) { return $project->isArchived(); }); ?>
        @if (!$projects->isEmpty())
            @foreach ($projects as $project)
                <div class="flying projects-list-item mb20">
                    <div class="fs20" style="margin-top: -3px; font-weight: 500;">
                        {!! Html::linkRoute('project.project.show', $project->name(), $project->projectId()->id()) !!}

                        {!! Html::linkRoute('project.project.restore_project', trans('app.restore'), $project->projectId()->id(), [
                            'data-confirm' => trans('app.confirm_restore_project'),
                            'class'=>'btn btn-default confirm',
                            'style' => 'float: right'
                        ]) !!}

                    </div>
                </div>
            @endforeach
        @else
            <div class="no-items center">{{ trans('app.no_archived_projects') }}</div>
        @endif
    </section>

@endsection
