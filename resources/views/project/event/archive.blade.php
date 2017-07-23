<?php /** @var \Teamo\Project\Application\Query\Event\EventsPayload $eventsPayload */ ?>
@extends('layouts.app')

@section('title', trans('app.events_archive'))

@section('content')

    @include('project.partials.event_navigation')

    {!! Form::flash() !!}

    <section class="flying s120 mb30">

        <ul class="mini-list">
            <li class="discussions-list first-item">
                <h2 class="mb0 fs22">
                    {{ trans('app.events_archive') }}
                </h2>
            </li>

            @foreach ($eventsPayload->events() as $event)
                <li class="discussions-list">
                    <div class="events-list-calendar past-event">
                        <div class="month">
                            {{ month_ui($event->occursOn(), $userTimezone) }}
                        </div>
                        <div class="day">
                            {{ utc2local($event->occursOn(), 'j', $userTimezone) }}
                        </div>
                    </div>

                    <div class="fs18">
                        {!! Html::linkRoute('project.event.show', $event->name(), [$selectedProjectId, $event->eventId()->id()], ['class' => 'title-link']) !!}
                    </div>
                    <div class="content c333">
                        {{ event_date_time_ui($event->occursOn(), $userTimeFormat, $userTimezone) }}
                    </div>
                    <div class="fs12 c666">
                        {{ $eventsPayload->teamMember($event->creator())->name() }} <span class="dot">•</span> {{ date_ui($event->createdOn()) }}
                        <span class="dot">•</span>
                        {!! Html::linkRoute('project.event.restore_event', trans('app.restore'), [$selectedProjectId, $event->eventId()->id()], ['class' => 'system']) !!}
                        <span class="dot">•</span>
                        {!! Html::linkRoute('project.event.delete', trans('app.delete'), [$selectedProjectId, $event->eventId()->id()],
                        ['class' => 'system confirm', 'data-confirm' => trans('app.confirm_delete_event')]) !!}
                    </div>
                </li>
            @endforeach
            @if ($eventsPayload->events()->isEmpty())
                <li class="no-items">{{ trans('app.no_any_events') }}</li>
            @endif
        </ul>

    </section>

@endsection
