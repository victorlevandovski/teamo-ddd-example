<?php /** @var \Teamo\Project\Application\Query\Discussion\DiscussionsPayload $discussionsPayload */ ?>
@extends('layouts.app')

@section('title', trans('app.discussions'))

@section('content')

    @include('project.partials.discussion_navigation')

    {!! Form::flash() !!}

    <section class="flying s120 mb30">

        <ul class="mini-list">
            <li class="discussions-list first-item">
                <h2 class="mb0 fs22">
                    {{ trans('app.discussions') }}
                    {!! Html::linkRoute('project.discussion.create', trans('app.add_discussion_action'), [$selectedProjectId], ['class' => 'btn btn-default btn-add-index']) !!}
                </h2>
            </li>
            @foreach ($discussionsPayload->discussions() as $discussion)
                {{--<li class="discussions-list {{ (!$discussion->is_read || $discussion->unread_comments_count) ? 'unread' : '' }}">--}}
                <li class="discussions-list">
                    <img src="{{ avatar_of_id($discussion->author()->id(), 96) }}" class="avatar avatar48">
                    <div class="fs18">
                        {{--{!! Html::link($discussion->url, $discussion->topic, ['class' => 'title-link']) !!}--}}
                        {!! Html::linkRoute('project.discussion.show', $discussion->topic(), [$selectedProjectId, $discussion->discussionId()->id()], ['class' => 'title-link']) !!}

                        {{--@if ($discussion->unread_comments_count)--}}
                            {{--<a href="{{ $discussion->url }}?unread=true">--}}
                                {{--<span class="unread-comments-counter">--}}
                                    {{--<i class="glyphicon glyphicon-comment"></i>--}}
                                    {{--<span>--}}
                                        {{--{{ $discussion->unread_comments_count }}--}}
                                    {{--</span>--}}
                                {{--</span>--}}
                            {{--</a>--}}
                        {{--@endif--}}

                    </div>
                    <div class="content">
                        {{ content_preview($discussion->content(), 10) }}
                    </div>
                    <div class="fs12 c333">
                        {{ $discussionsPayload->teamMember($discussion->author())->name() }} <span class="dot">•</span> {{ date_ui($discussion->createdOn()) }}
                        {{--@if ($discussion->updateAllowed() && false)--}}
                            {{--<span class="dot">•</span>--}}
                            {{--{!! Html::linkRoute('discussion.edit', trans('app.edit'), [$project, $discussion], ['class' => 'system']) !!}--}}
                            {{--<span class="dot">•</span>--}}
                            {{--{!! Html::linkRoute('discussion.delete', trans('app.delete'), [$project, $discussion],--}}
                            {{--['class' => 'system confirm', 'data-confirm' => trans('app.confirm_delete_discussion')]) !!}--}}
                        {{--@endif--}}
                    </div>
                    {{--@if ($discussion->unread_comments_count)--}}
                    {{--@endif--}}
                </li>
            @endforeach
            @if ($discussionsPayload->discussions()->isEmpty())
                <li class="no-items">{{ trans('app.no_discussions') }}</li>
            @endif
            <li>
                {!! Html::linkRoute('project.discussion.archive', trans('app.discussions_archive'), [$selectedProjectId], ['class' => 'system']) !!}
            </li>
        </ul>

    </section>

@endsection
