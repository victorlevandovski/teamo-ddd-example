@extends('layouts.app')

@section('title', trans('app.my_projects'))

@section('header')
    {{ trans('app.my_projects') }}
@endsection

@section('content')

    <div class="navigation">
        {!! Html::linkRoute('project.project.create', trans('app.start_project_action')) !!}
        {!! Html::linkRoute('project.project.archive', trans('app.projects_archive')) !!}
    </div>

    {!! Form::flash() !!}

    <section style="margin: 0 auto; width: 650px;">
        @if (!$projects->isEmpty())
            @foreach ($projects as $project)
                <div class="flying projects-list-item mb20" onclick="document.location='{{ route('project.show', $project->id) }}'">
                    <div class="fs20" style="margin-top: -3px; font-weight: 500;">
                        {!! Html::linkRoute('project.project.show', $project->name, $project) !!}
                    </div>
                    <div class="projects-list-progress">
                        {{ trans('app.last_activity') }} {{ $project->updated_at_ui }}
                    </div>
                </div>
            @endforeach
        @else
            <div class="no-items center">{{ trans('app.no_active_projects') }}</div>
        @endif
    </section>

@endsection
