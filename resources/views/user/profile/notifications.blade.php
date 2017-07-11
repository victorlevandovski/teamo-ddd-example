<?php /** @var \Teamo\User\Domain\Model\User\User $user */ ?>
@extends('layouts.app')

@section('title', trans('profile.notifications_settings'))

@section('header')
    {{ trans('profile.notifications_settings') }}
@endsection

@section('content')

    {!! Form::flash() !!}

    <section class="flying s120 mb20">

        {!! Form::open(['route' => ['user.profile.notifications'], 'method' => 'patch']) !!}

        <div>
            {{ trans('profile.you_will_receive') }}:
        </div>

        <div class="mt20"><b>{{ trans('app.discussions') }}</b></div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('notify_new_discussion', null, $user->notifications()->whenDiscussionStarted()) !!} {{ trans('profile.notify_new_discussion') }}
            </label>
        </div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('notify_new_discussion_comment', null, $user->notifications()->whenDiscussionCommented()) !!} {{ trans('profile.notify_new_discussion_comment') }}
            </label>
        </div>


        <div class="mt20"><b>{{ trans('app.todos') }}</b></div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('notify_new_todo', null, $user->notifications()->whenTodoListCreated()) !!} {{ trans('profile.notify_new_todo') }}
            </label>
        </div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('notify_new_todo_comment', null, $user->notifications()->whenTodoCommented()) !!} {{ trans('profile.notify_new_todo_comment') }}
            </label>
        </div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('notify_new_todo_assigned', null, $user->notifications()->whenTodoAssignedToMe()) !!} {{ trans('profile.notify_new_todo_assigned') }}
            </label>
        </div>


        <div class="mt20"><b>{{ trans('app.events') }}</b></div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('notify_new_event', null, $user->notifications()->whenEventAdded()) !!} {{ trans('profile.notify_new_event') }}
            </label>
        </div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('notify_new_event_comment', null, $user->notifications()->whenEventCommented()) !!} {{ trans('profile.notify_new_event_comment') }}
            </label>
        </div>



        <div class="form-control-submit mt30">
            {!! Form::submit(trans('app.update'), ['class' => 'btn btn-primary']) !!}
            {!! Html::linkRoute('user.profile.profile', trans('profile.back_to_settings'), [], ['class' => 'system']) !!}
        </div>

        {!! Form::close() !!}

    </section>
@endsection
