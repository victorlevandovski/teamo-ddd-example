@extends('layouts.app')

@section('title', trans('app.start_project'))

@section('header')
    {{ trans('app.start_project') }}
@endsection

@section('content')

    {!! Form::flash() !!}

    <section class="flying">
        <div class="panel-s">

            {!! Form::open(['route' => 'project.project.store']) !!}

            <div class="form-group">
                {!! Form::label('name', trans('app.label_name')) !!}
                <div>
                    {!! Form::text('name', null, ['class' => 'form-control  focus']) !!}
                </div>
                {!! Form::error('name') !!}
            </div>

            {{--@if (!$users->isEmpty())--}}
                {{--{!! Form::label('team', trans('app.add_to_team_action')) !!}--}}
                {{--<a href="javascript:void(0);" class="ml5 mass-toggle system"--}}
                   {{--data-select_none="{{ trans('app.select_none') }}"--}}
                   {{--data-select_all="{{ trans('app.select_all') }}">{{ trans('app.select_all') }}</a>--}}
                {{--<div class="form-group">--}}
                    {{--<ul class="uli">--}}
                        {{--@foreach ($users as $user)--}}
                            {{--<li style="width: 49%">--}}
                                {{--{!! Form::checkbox('users[]', $user->id, false, ['id' => 'users-'.$user->id, 'class' => 'toggle']) !!}--}}
                                {{--{!! Form::label('users-'.$user->id, $user->name, ['style' => 'font-weight:normal']) !!}--}}
                            {{--</li>--}}
                        {{--@endforeach--}}
                    {{--</ul>--}}
                {{--</div>--}}
            {{--@endif--}}

            <div class="form-control-submit">
                {!! Form::submit(trans('app.create'), ['class' => 'btn btn-primary']) !!}
                {!! Html::linkRoute('project.project.index', trans('app.cancel'), []) !!}
            </div>

            {!! Form::close() !!}

        </div>
    </section>
@endsection
