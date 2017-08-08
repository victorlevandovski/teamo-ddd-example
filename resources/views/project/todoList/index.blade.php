<?php /** @var \Teamo\Project\Application\Query\TodoList\TodoListsPayload $todoListsPayload */ ?>
@extends('layouts.app')

@section('title', trans('app.todos'))

@section('scripts')
    <script type="text/javascript" src="/js/todo-index.js?v1"></script>
@append

@section('content')

    @include('project.partials.todo_list_navigation')

    {!! Form::flash() !!}

    <section class="flying s120 mb30">

        <ul class="mini-list">
            <li class="discussions-list first-item">
                <h2 class="mb0 fs22">
                    {{ trans('app.todos') }}
                    {!! Html::linkRoute('project.todo_list.create', trans('app.add_todo_action'), [$selectedProjectId], ['class' => 'btn btn-default btn-add-index']) !!}
                </h2>
            </li>
            <li class="fs12">
                <span style="margin-right: 8px;">{{ trans('app.todo_index_show') }}:</span>
                <?php $listMenu = ['todo_list' => trans('app.todo_index_lists'), 'todo' => trans('app.todo_index_items'), 'my_todo' => trans('app.todo_index_my_items')]; ?>
                @foreach ($listMenu as $option => $label)
                    @if ($listAs == $option)
                        <span style="padding: 3px 8px; background-color: #808080; border-radius: 50px;">
                        <a href="{{ route('project.todo_list.index', [$selectedProjectId, 'list_as' => $option]) }}" style="color: #ffffff;">{{ $label }}</a></span>
                    @else
                        <span style="padding: 3px 8px; background-color: #ffffff; border-radius: 50px;">
                        <a href="{{ route('project.todo_list.index', [$selectedProjectId, 'list_as' => $option]) }}" style="color: #333;text-decoration: underline;">{{ $label }}</a></span>
                    @endif
                @endforeach
            </li>

            {{--@if ($todos)--}}
                {{--@foreach ($todos as $todo)--}}
                    {{--<li class="discussions-list {{ $todo->is_done ? 'done' : '' }}">--}}
                        {{--<div class="fs18">--}}

                            {{--@if ($todo->favourite)--}}
                                {{--<div class="add-to-favourites-list-todos favourites-list">--}}
                                    {{--<a href="{{ route('add_to_favourites', ['todo', $todo->id]) }}" class="active" title="{{ trans('app.is_favourite') }}"><i class="glyphicon glyphicon-star"></i></a>--}}
                                {{--</div>--}}
                            {{--@endif--}}

                            {{--{!! Html::linkRoute('project.todo.show', $todo->name, [$selectedProjectId, $todo], ['class' => 'todo-link title-link']) !!}--}}

                            {{--@if ($todo->unread_comments_count)--}}
                                {{--<a href="{{ route('todo.show', [$selectedProjectId, $todo, 'unread' => true]) }}">--}}
                    {{--<span class="unread-comments-counter">--}}
                        {{--<i class="glyphicon glyphicon-comment"></i>--}}
                        {{--<span>--}}
                            {{--{{ $todo->unread_comments_count }}--}}
                        {{--</span>--}}
                    {{--</span>--}}
                                {{--</a>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                        {{--@if ($todo->items_count)--}}
                            {{--@if (!$todo->is_done)--}}
                                {{--<div class="todo-index-progress clearfix">--}}
                                    {{--<div class="todo-progress pull-left clearfix">--}}
                                        {{--@for ($i = 0; $i < $todo->done_items_count; $i++)--}}
                                            {{--<div class="bar-cell-done"></div>--}}
                                        {{--@endfor--}}
                                        {{--@for ($i = $todo->done_items_count; $i < $todo->items_count; $i++)--}}
                                            {{--<div class="bar-cell"></div>--}}
                                        {{--@endfor--}}
                                    {{--</div>--}}
                                    {{--<div class="content pull-left ml10">--}}
                                        {{--{{ $todo->done_items_count }} / {{ $todo->items_count }}--}}
                                        {{--@if ($todo->assigned_count)--}}
                                            {{--<span class="fs12 my-item my-item-badge" title="{{ trans('app.assigned_undone_hint') }}">--}}
                                                {{--<b>{{ $todo->assigned_count }}</b>--}}
                                            {{--</span>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--@endif--}}
                        {{--@else--}}
                            {{--<div class="content">--}}
                                {{--{{ trans('app.no_todo_items') }}--}}
                            {{--</div>--}}
                        {{--@endif--}}

                        {{--<div class="fs12 c333">--}}
                            {{--{{ $todo->user->name }} <span class="dot">•</span> {{ $todo->created_at_ui }}--}}
                        {{--</div>--}}
                    {{--</li>--}}
                {{--@endforeach--}}
                {{--@if ($todos->isEmpty())--}}
                    {{--<li><span class="no-items">{{ trans('app.no_todos') }}</span></li>--}}
                {{--@endif--}}
                {{--<li>--}}
                    {{--{!! Html::linkRoute('todo.archive', trans('app.todos_archive'), [$selectedProjectId], ['class' => 'system']) !!}--}}
                {{--</li>--}}
            {{--@endif--}}

            <?php $id = 0; ?>

            {{--@if ($items)--}}
                {{--<li class="discussions-list">--}}
                    {{--<?php //foreach ($items as $item): ?>--}}
                    {{--<?php //if ($item->todo->is_archived) continue; ?>--}}
                    {{--@if ($id != $item->todo_id)--}}
                        {{--@if ($id > 0) </li><li class="discussions-list"> @endif--}}
                    {{--@if ($item->favourite)--}}
                        {{--<div class="add-to-favourites-list-todos favourites-list">--}}
                            {{--<a href="{{ route('add_to_favourites', ['todo', $item->todo_id]) }}" class="active" title="{{ trans('app.remove_from_favourites') }}"><i class="glyphicon glyphicon-star"></i></a>--}}
                        {{--</div>--}}
                    {{--@endif--}}
                    {{--<div class="fs18 mb5">--}}
                        {{--{!! Html::linkRoute('todo.show', $item->todo->name, [$selectedProjectId, $item->todo], ['class' => 'todo-link title-link system']) !!}--}}
                    {{--</div>--}}
                    {{--@endif--}}

                    {{--<?php //$id = $item->todo_id; ?>--}}
                    {{--<div class="todo-items-list clearfix">--}}
                        {{--<div class="item-checkbox-div">--}}
                            {{--<input type="checkbox" value="{{ $item->id }}" data-id="{{ $item->todo_id }}" class="item-checkbox" @if ($item->done) checked @endif>--}}
                        {{--</div>--}}
                        {{--<div class="item-name" style="">--}}
                            {{--{!! Html::linkRoute('todoItem.show', $item->name, [$selectedProjectId, $item->todo_id, $item]) !!}--}}
                        {{--</div>--}}
                        {{--@if ($item->comments_count)--}}
                            {{--<div class="item-comments-container ml10">--}}
                                {{--<span class="item-comments-badge" title="{{ $item->comments_count_ui }}">{{ $item->comments_count_ui }}</span>--}}
                            {{--</div>--}}
                        {{--@endif--}}
                        {{--<div class="item-details fs12 c666 {{ $item->assignee || $item->deadline ? '' : 'hide' }}">--}}
                            {{--@if ($item->assignee)--}}
                                {{--<img src="{{ $item->assignee->avatar_24 }}" class="avatar24" style="margin: -2px 2px 0 0;">--}}
                                {{--{{ $item->assignee->name_ui }}--}}
                            {{--@endif--}}
                            {{--@if ($item->deadline)--}}
                                {{--<div class="todo-deadline-icon todo-icon {{ $item->assignee ? 'ml10' : '' }}" title="{{ trans('date.due') }} {{ $item->deadline_ui }}">--}}
                                    {{--<div class="day">--}}
                                        {{--{{ utc2local($item->deadline, 'j') }}--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<?php //endforeach; ?>--}}
                    {{--@if ($items->isEmpty())--}}
                        {{--<span class="no-items">{{ trans('app.no_todo_items') }}</span>--}}
                    {{--@endif--}}
                {{--</li>--}}
            {{--@endif--}}

        </ul>

        {!! Form::token() !!}

    </section>

    <div class="ajax-status-fixed"><span id="ajax-status" class="c666 fs12"></span></div>

@endsection
