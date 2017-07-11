@extends('layouts.app')

@section('title', trans('profile.change_password'))

@section('header')
    {{ trans('profile.change_password') }}
@endsection

@section('content')

    {!! Form::flash() !!}

    <section class="flying mb20">

        <div class="row">

            <div class="col-md-3"></div>

            <div class="col-md-6">

                {!! Form::open(['method' => 'patch']) !!}

                <div class="form-group">
                    {!! Form::label('password', trans('profile.current_password')) !!}
                    <div>
                        {!! Form::password('password', ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::error('password') !!}
                </div>

                <div class="form-group">
                    {!! Form::label('new_password', trans('profile.new_password')) !!}
                    <div>
                        {!! Form::password('new_password', ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::error('new_password') !!}
                </div>

                <div class="form-group form-control-submit">
                    {!! Form::submit(trans('app.update'), ['class' => 'btn btn-primary']) !!}
                    {!! Html::linkRoute('user.profile.profile', trans('app.cancel'), [], ['class' => 'system']) !!}
                </div>

                {!! Form::close() !!}
            </div>

            <div class="col-md-3"></div>

        </div>
    </section>

@endsection
