<?php /** @var \Teamo\Project\Application\Query\Event\EventsPayload $eventsPayload */ ?>
@extends('layouts.app')

@section('title', trans('app.events'))

@section('content')

    @include('project.partials.event_navigation')

    {!! Form::flash() !!}

    <section class="flying s120 mb30">

        <ul class="mini-list">
            <li class="discussions-list first-item">
                <h2 class="mb0 fs22">
                    {{ trans('app.events') }}
                    {!! Html::linkRoute('project.event.create', trans('app.add_event_action'), [$selectedProjectId],
                    ['class' => 'btn btn-default btn-add-index']) !!}
                </h2>
            </li>

            @foreach ($eventsPayload->events() as $event)
                <li class="discussions-list">
                    <div class="events-list-calendar">
                        <div class="month">
                            {{ month_ui($event->occursOn(), $userTimezone) }}
                        </div>
                        <div class="day">
                            {{ utc2local($event->occursOn(), 'j', $userTimezone) }}
                        </div>
                    </div>

                    <div class="fs18">
                        {!! Html::linkRoute('project.event.show', $event->name(), [$selectedProjectId, $event->eventId()->id()], ['class' => 'title-link']) !!}

                        {{--@if ($event->unread_comments_count)--}}
                            {{--<a href="{{ route('event.show', [$project, $event, 'unread' => true]) }}">--}}
                                {{--<span class="unread-comments-counter">--}}
                                    {{--<i class="glyphicon glyphicon-comment"></i>--}}
                                    {{--<span>--}}
                                        {{--{{ $event->unread_comments_count }}--}}
                                    {{--</span>--}}
                                {{--</span>--}}
                            {{--</a>--}}
                        {{--@endif--}}

                    </div>
                    <div class="content c333">
                        {{ event_date_time_ui($event->occursOn(), $userTimeFormat, $userTimezone) }}
                    </div>
                    <div class="fs12 c666">
                        {{ $eventsPayload->teamMember($event->creator())->name() }}
                    </div>
                </li>
            @endforeach
            @if ($eventsPayload->events()->isEmpty())
                <li class="no-items">{{ trans('app.no_events') }}</li>
            @endif
        </ul>

        <ul class="mini-list">
            <li>
                {!! Html::linkRoute('project.event.archive', trans('app.events_archive'), [$selectedProjectId], ['class' => 'system']) !!}
            </li>
        </ul>

    </section>

@endsection
