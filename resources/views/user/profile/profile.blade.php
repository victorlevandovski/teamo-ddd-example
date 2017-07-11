<?php /** @var \Teamo\User\Domain\Model\User\User $user */ ?>
@extends('layouts.app')

@section('title', trans('profile.profile'))

@section('header')
    {{ trans('profile.profile') }}
@endsection

@section('content')
    <div class="navigation">
        {!! Html::linkRoute('user.profile.notifications', trans('profile.notifications_settings')) !!}
    </div>

    {!! Form::flash() !!}

    <section class="flying mb20">
        <div class="row">

            <div class="col-md-5">
                {!! Form::open(['route' => ['user.profile.update', $user->userId()->id()], 'method' => 'patch']) !!}

                <div class="form-group">
                    {!! Form::label('name', trans('profile.name')) !!}
                    <div>
                        {!! Form::text('name', $user->name(), ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::error('name') !!}
                </div>

                <div class="form-group">
                    {!! Form::label('timezone', trans('profile.time_zone')) !!}
                    <div>
                        {!! Form::select('timezone', timezones($user->preferences()->timezone()), $user->preferences()->timezone(), ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::error('timezone') !!}
                </div>

                <div class="form-group">
                    {!! Form::label('date_format', trans('profile.date_format')) !!}
                    <div>
                        {!! Form::select('date_format', date_formats($user->preferences()->timezone()), $user->preferences()->dateFormat(), ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::error('date_format') !!}
                </div>

                <div class="form-group">
                    {!! Form::label('time_format', trans('profile.time_format')) !!}
                    <div>
                        {!! Form::select('time_format', time_formats($user->preferences()->timezone()), $user->preferences()->timeFormat(), ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::error('time_format') !!}
                </div>

                <div class="form-group">
                    {!! Form::label('week_start', trans('profile.week_start')) !!}
                    <div>
                        {!! Form::select('week_start', week_days(), $user->preferences()->firstDayOfWeek(), ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::error('week_start') !!}
                </div>

                <div class="form-group">
                    {!! Form::label('language', trans('profile.language')) !!}
                    <div>
                        {!! Form::select('language', languages(), $user->preferences()->language(), ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::error('language') !!}
                </div>

                <div>
                    {!! Form::submit(trans('app.save'), ['class' => 'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}
            </div>

            <div class="col-md-1"></div>

            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('email', trans('profile.email')) !!}
                    <div style="height:34px;padding: 6px 0;">
                        {{ $user->email() }}
                        {!! Html::linkRoute('user.profile.email', trans('profile.change_email_action'), [], ['class' => 'system']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('password', trans('profile.password')) !!}
                    <div style="height:34px;padding: 6px 0;">
                        ••••••••
                        {!! Html::linkRoute('user.profile.password', trans('profile.change_password_action'), [], ['class' => 'system']) !!}
                    </div>
                </div>

                <div class="">
                    {!! Form::label('avatar', trans('profile.avatar')) !!}
                    <div style="height:34px;padding: 6px 0;">
                        <img src="{{ avatar($user->avatar()->pathTo96pxAvatar()) }}" class="avatar48">
                        &nbsp;
                        {!! Html::linkRoute('user.profile.avatar', trans('profile.change_avatar_action'), [], ['class' => 'system']) !!}
                        @if (!$user->avatar()->isDefault())
                            {!! Html::linkRoute('user.profile.delete_avatar', trans('app.remove'), [],
                            ['class' => 'system ml5 confirm', 'data-confirm' => trans('profile.confirm_avatar')]) !!}
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
