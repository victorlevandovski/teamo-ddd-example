<?php /** @var \Teamo\Project\Application\Query\Event\EventPayload $eventPayload */ ?>
@extends('layouts.app')

@section('title', $eventPayload->event()->name())

@section('content')

    @include('project.partials.event_navigation')

    {!! Form::flash() !!}

    <section class="flying s120 mb30">

        <h2 class="center mb15 fs22">{{ $eventPayload->event()->name() }}</h2>

        <?php $past = $eventPayload->event()->occursOn()->getTimestamp() < strtotime(utc2local(time(), 'Y-m-d 00:00:00', $userTimezone)) ? 'past-event' : '' ?>
        <div class="event-details-calendar mt20 mb30 {{ $past }}">
            <div class="month">
                {{ month_ui($eventPayload->event()->occursOn(), $userTimezone) }}
            </div>
            <div class="day">
                <b>{{ utc2local($eventPayload->event()->occursOn(), 'j', $userTimezone) }}</b>
                <div class="c666 fs12">
                    {{ utc2local($eventPayload->event()->occursOn(), time_format_from_int($userTimeFormat), $userTimezone) }}
                </div>
            </div>
        </div>

        <div class="discussion-content">

            {!! $eventPayload->event()->details() !!}

            <p class="fs12 c666">
                {{ $eventPayload->teamMember($eventPayload->event()->creator())->name() }} <span class="dot">•</span> {{ date_ui($eventPayload->event()->createdOn()) }}
                <span class="dot">•</span>
                {!! Html::linkRoute('project.event.edit', trans('app.edit'), [$selectedProjectId, $eventPayload->event()->eventId()->id()], ['class' => 'system']) !!}
                <span class="dot">•</span>
                @if (!$eventPayload->event()->isArchived())
                    {!! Html::linkRoute('project.event.archive_event', trans('app.archive_action'), [$selectedProjectId, $eventPayload->event()->eventId()->id()], ['class' => 'system']) !!}
                @else
                    {!! Html::linkRoute('project.event.restore_event', trans('app.restore'), [$selectedProjectId, $eventPayload->event()->eventId()->id()], ['class' => 'system']) !!}
                @endif
                <span class="dot">•</span>
                {!! Html::linkRoute('project.event.delete', trans('app.delete'), [$selectedProjectId, $eventPayload->event()->eventId()->id()],
                ['class' => 'system confirm', 'data-confirm' => trans('app.confirm_delete_event')]) !!}
            </p>

        </div>

        @include('project.partials.comments', ['payload' => $eventPayload, 'controller' => 'event', 'entityId' => $eventPayload->event()->eventId()->id()])

    </section>

@endsection

@section('scripts')
    <script type="text/javascript" src="/lightbox/js/lightbox.min.js"></script>
    <script type="text/javascript" src="/js/jquery-ui.custom.min.js"></script>
    <script type="text/javascript" src="/js/jquery.fileupload.js"></script>
    <script type="text/javascript" src="/js/jquery.iframe-transport.js"></script>
    <script type="text/javascript" src="/js/discussion.js?v2"></script>
@append
