@extends('layouts.app')

@section('title', trans('app.title_add_todo'))

@section('header')
    {{ trans('app.title_add_todo') }}
@endsection

@section('scripts')
@append

@section('content')

    @include('project.partials.todo_list_navigation')

    {!! Form::flash() !!}

    <section class="flying">

        <div class="panel-s">

            {!! Form::open(['route' => ['project.todo_list.store', $selectedProjectId]]) !!}

            <div class="form-group">
                {!! Form::label('name', trans('app.label_name')) !!}
                <div>
                    {!! Form::text('name', null, ['class' => 'form-control focus']) !!}
                </div>
                {!! Form::error('name') !!}
            </div>

            <div class="form-control-submit">
                {!! Form::submit(trans('app.save'), ['class' => 'btn btn-primary']) !!}
                {!! Html::cancel(route('project.todo_list.index', [$selectedProjectId])) !!}
            </div>

            {!! Form::close() !!}

        </div>

    </section>
@endsection
