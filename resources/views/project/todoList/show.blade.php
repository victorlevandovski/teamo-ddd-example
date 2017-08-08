<?php
/** @var \Teamo\Project\Application\Query\TodoList\TodoListPayload $todoListPayload */
$todoListId = $todoListPayload->todoList()->todoListId()->id();
?>
@extends('layouts.app')

@section('title', $todoListPayload->todoList()->name())

@section('scripts')
    <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
    <link href="/datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <script src="/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="/datepicker/locales/bootstrap-datepicker.ru.min.js"></script>
    <script type="text/javascript" src="/js/todo.js?v1"></script>
    <script type="text/javascript">
        var projectId = '{{ $selectedProjectId }}';
        var todoListId = '{{ $todoListId }}';
        var userTimezone = '{{ $userTimezone }}';
    </script>
@append

@section('content')

    @include('project.partials.todo_list_navigation')

    {!! Form::flash() !!}

    <section class="flying todo mb30">

        {{--<div class="add-to-favourites favourites-icon">--}}
            {{--<a href="{{ route('add_to_favourites', ['todo', $todo->id]) }}" class="{{ $todo->is_favourite ? 'active' : '' }}" title="{{ trans('app.add_to_favourites') }}"><i class="glyphicon glyphicon-star"></i></a>--}}
        {{--</div>--}}

        <h2 class="center mb15 fs22">{{ $todoListPayload->todoList()->name() }}</h2>

        {!! Form::hidden('id', $todoListId) !!}
        {!! Form::token() !!}
        <div class="mb20">
            <div id="todos-section">
                <ul id="todos" class="">
                    @foreach ($todoListPayload->todoList()->todos() as $todo)
                        <?php /** @var \Teamo\Project\Domain\Model\Project\TodoList\Todo $todo */ ?>
                        <li class="item clearfix">
                            <div data-id="{{ $todo->todoId()->id() }}" class="item-div {{ $todo->isCompleted() ? 'done' : '' }}">
                                <div class="move-icon">
                                    <i class="glyphicon glyphicon-menu-hamburger fs12 c999"></i>
                                </div>
                                <div class="item-checkbox-div item-container">
                                    <input type="checkbox" class="item-checkbox" @if ($todo->isCompleted()) checked @endif>
                                </div>
                                <div class="item-name item-container" style="">
                                    {!! Html::linkRoute('project.todo_list.todo.show', $todo->name(), [$selectedProjectId, $todoListId, $todo->todoId()->id()]) !!}
                                </div>
                                {{--@if ($item->comments_count)--}}
                                    {{--<div class="item-comments-container ml10">--}}
                                        {{--<span class="item-comments-badge" title="{{ $item->comments_count_ui }}">{{ $item->comments_count_ui }}</span>--}}
                                    {{--</div>--}}
                                {{--@endif--}}
                                <div class="item-details fs12 c666 {{ $todo->assignee() || $todo->deadline() ? '' : 'hide' }}">
                                    @if ($todo->assignee())
                                        <img src="{{ avatar_of_id($todo->assignee(), 48) }}" class="avatar24 todo-item-avatar">
                                        {{ team_member_name_ui($todoListPayload->teamMember($todo->assignee())->name()) }}
                                    @endif
                                    @if ($todo->deadline())
                                        <div class="todo-deadline-icon todo-icon {{ $todo->assignee() ? 'ml10' : '' }}" title="{{ trans('date.due') }} {{ month_day_ui($todo->deadline(), $userTimezone) }}">
                                            <div class="day">
                                                {{ utc2local($todo->deadline(), 'j', $userTimezone) }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="item-controls">
                                    <a href="javascript:void(0)" class="edit-link" title="{{ trans('app.edit') }}"
                                       onclick="todoEditItem(this)"><i class="glyphicon glyphicon-pencil c666 fs12"></i></a>
                                    <a href="javascript:void(0);" class="delete-link" title="{{ trans('app.remove') }}"
                                       onclick="todoDeleteItem(this)"><i class="glyphicon glyphicon-trash c666 fs12"></i></a>
                                </div>
                                <input type="hidden" class="id-assignee-id" value="{{ $todo->assignee() ? $todo->assignee()->id(): '' }}">
                                <input type="hidden" class="id-due-date" value="{{ $todo->deadline() ? utc_date_formatted($todo->deadline(), $userDateFormat, $userTimezone) : '' }}">
                                <input type="hidden" class="id-assignee-text" value="{{ $todo->assignee() ? team_member_name_ui($todoListPayload->teamMember($todo->assignee())->name()) : '' }}">
                                <input type="hidden" class="id-due-date-text" value="{{ $todo->deadline() ? month_day_ui($todo->deadline(), $userTimezone) : '' }}">
                                <input type="hidden" class="id-assignee-avatar" value="{{ $todo->assignee() ? avatar_of_id($todo->assignee()->id(), 48) : ''}}">
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt10">
                <a href="javascript:void(0)" id="add-new-item-link" class="system">
                    {{ trans('app.add_items_action') }}
                </a>
            </div>

            <div style="display: none;" id="new-item-li">
                <li class="item clearfix">
                    <div data-id="new_item" class="item-div">
                        <div class="move-icon hide-icon">
                            <i class="glyphicon glyphicon-menu-hamburger fs12 c999"></i>
                        </div>
                        <div class="item-checkbox-div item-container">
                            <input type="checkbox" class="item-checkbox">
                        </div>
                        <div class="item-name item-container">
                            <input type="text" id="new-item-input" onkeydown="todoProcessInput(this, event)">
                        </div>
                        <div class="item-details fs12 c666 hide"></div>
                        <div class="item-controls hide">
                            <a href="javascript:void(0)" class="edit-link" title="{{ trans('app.edit') }}"
                               onclick="todoEditItem(this)"><i class="glyphicon glyphicon-pencil c666 fs12"></i></a>
                            <a href="javascript:void(0)" class="delete-link" title="{{ trans('app.remove') }}"
                               onclick="todoDeleteItem(this)"><i class="glyphicon glyphicon-trash c666 fs12"></i></a>
                        </div>
                        <input type="hidden" class="id-assignee-id" value="">
                        <input type="hidden" class="id-due-date" value="">
                        <input type="hidden" class="id-assignee-text" value="">
                        <input type="hidden" class="id-due-date-text" value="">
                        <input type="hidden" class="id-assignee-avatar" value="">
                    </div>
                    <br>
                    <div class="additionals-panel" style="display:inline-block;">
                        <div class="additionals-links">
                            <a href="javascript:void(0)" class="system fs12" id="assign-to-link">{{ trans('app.set_assignee_action') }}</a>
                            <a href="javascript:void(0)" class="system fs12 ml10" id="due-date-link">{{ trans('app.set_deadline_action') }}</a>
                            <button id="save-item" class="btn btn-xs btn-default ml10 fs12">{{ trans('app.save') }}</button>
                        </div>
                        <br>
                        <div id="assign-to">
                            <div class="clearfix">
                                @foreach ($todoListPayload->project()->teamMembers() as $teamMember)
                                    <div style="float: left; width: 180px;padding: 10px;">
                                        {{--<img src="{{ $user->avatar_24 }}" class="avatar24">--}}
                                        <img src="{{ avatar_of_id($teamMember->teamMemberId()->id(), 48) }}" class="avatar24">
                                        <a href="javascript:void(0);" class="c333 ml5" data-user="{{ $teamMember->name() }}"
                                           data-avatar="{{ avatar_of_id($teamMember->teamMemberId()->id(), 48) }}"
                                           onclick="todoSetAssignee(this, '{{ $teamMember->teamMemberId()->id() }}')">{{ team_member_name_ui($teamMember->name()) }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </li>
            </div>
        </div>

        <div class="fs12 c333 mt30">
            {{ $todoListPayload->teamMember($todoListPayload->todoList()->creator())->name() }} <span class="dot">•</span> {{ date_ui($todoListPayload->todoList()->createdOn()) }}
            <span class="dot">•</span>
            {!! Html::linkRoute('project.todo_list.edit', trans('app.edit'), [$selectedProjectId, $todoListId], ['class' => 'system']) !!}
            <span class="dot">•</span>
            @if (!$todoListPayload->todoList()->isArchived())
                {!! Html::linkRoute('project.todo_list.archive_todo_list', trans('app.archive_action'), [$selectedProjectId, $todoListId], ['class' => 'system']) !!}
            @else
                {!! Html::linkRoute('project.todo_list.restore_todo_list', trans('app.restore'), [$selectedProjectId, $todoListId], ['class' => 'system']) !!}
            @endif
            <span class="dot">•</span>
            {!! Html::linkRoute('project.todo_list.delete', trans('app.delete'), [$selectedProjectId, $todoListId],
            ['class' => 'system confirm', 'data-confirm' => trans('app.confirm_delete_todo')]) !!}
            <b><span id="ajax-status" class="ml20 c999"></span></b>
        </div>

    </section>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('app.edit_item') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal-name">{{ trans('app.label_name') }}</label>
                        <div>
                            <input type="text" name="modal_name" id="modal-name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="modal-assignee">{{ trans('app.label_assignee') }}</label>
                        <div>
                            <select name="modal_assignee" id="modal-assignee" class="form-control w50p">
                                <option value="0"></option>
                                @foreach ($todoListPayload->project()->teamMembers() as $teamMember)
                                    <option value="{{ $teamMember->teamMemberId()->id() }}" data-name="{{ team_member_name_ui($teamMember->name()) }}" data-avatar="{{ avatar_of_id($teamMember->teamMemberId()->id(), 48) }}">{{ $teamMember->name() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="modal-deadline">{{ trans('app.label_deadline') }}</label>
                        <div>
                            <input type="text" name="modal_deadline" id="modal-deadline" class="form-control w50p">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align: left;">
                    <button type="button" class="btn btn-primary modal-save" data-dismiss="modal">{{ trans('app.save') }}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('app.cancel') }}</button>
                    <input type="hidden" id="modal-id" value="">
                    <input type="hidden" id="modal-name-ui" value="">
                    <input type="hidden" id="modal-assignee-avatar" value="">
                </div>
            </div>
        </div>
    </div>

@endsection
