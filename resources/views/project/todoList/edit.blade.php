@extends('layouts.app')

@section('title', trans('app.title_edit_todo'))

@section('header')
    {{ trans('app.title_edit_todo') }}
@endsection

@section('scripts')
@append

@section('content')

    @include('project.partials.todo_list_navigation')

    {!! Form::flash() !!}

    <section class="flying">
        <div class="panel-s">

            {!! Form::model(['name' => $todoList->name()], ['route' => ['project.todo_list.update', $selectedProjectId, $todoList->todoListId()->id()], 'method' => 'patch']) !!}

            <div class="form-group">
                {!! Form::label('name', trans('app.label_name')) !!}
                <div>
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                </div>
                {!! Form::error('name') !!}
            </div>

            <div class="form-control-submit">
                {!! Form::submit(trans('app.save'), ['class' => 'btn btn-primary']) !!}
                {!! Html::cancel(route('project.todo_list.show', [$selectedProjectId, $todoList->todoListId()->id()])) !!}
            </div>

            {!! Form::close() !!}

        </div>
    </section>
@endsection
