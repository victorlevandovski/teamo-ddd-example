<div class="mt20">

    <div class="separator mt20 mb25"></div>

    @if (!$payload->comments()->isEmpty())

        {{--@if (isset($discussion) && $discussion)--}}
            {{--<div class="following-icon">--}}
                {{--<a href="{{ route('follow', ['discussion', $discussion->id]) }}" class="{{ $discussion->is_following ? 'active' : '' }}" title="{{ trans('app.follow') }}"><i class="glyphicon glyphicon-envelope"></i></a>--}}
            {{--</div>--}}
        {{--@elseif (isset($event) && $event)--}}
            {{--<div class="following-icon">--}}
                {{--<a href="{{ route('follow', ['event', $event->id]) }}" class="{{ $event->is_following ? 'active' : '' }}" title="{{ trans('app.follow') }}"><i class="glyphicon glyphicon-envelope"></i></a>--}}
            {{--</div>--}}
        {{--@endif--}}

        <h2 class="mb30 fs18">{{ trans('app.comments') }}</h2>

        <ul class="ulb comments">
            @foreach ($payload->comments() as $comment)
                <li id="comment-{{ $comment->commentId()->id() }}" class="{{ $comment->attachments()->isEmpty() ? 'mb25' : '' }}">
                    <a name="comment-{{ $comment->commentId()->id() }}"></a>

                    <img src="{{ avatar_of_id($comment->author()->id(), 96) }}" class="content-avatar avatar48">

                    <div>
                        <div class="mb5"><b>{{ $payload->teamMember($comment->author())->name() }}</b></div>
                        <div class="comment-content">
                            {!! nl2br($comment->content()) !!}
                        </div>
                        <div class="fs12 c666 mt5">
                            {{ date_ui($comment->createdOn()) }}
                            @if (is_authenticated($comment->author()))
                                <span class="dot">•</span>
                                @if ($controller == 'todoItem')
                                    {!! Html::linkRoute('project.' . $controller . '.edit_comment', trans('app.edit'), [$project, $todo, $todoItem, $comment->commentId()->id()], ['class' => 'system']) !!}
                                @else
                                    {!! Html::linkRoute('project.' . $controller.'.edit_comment', trans('app.edit'), [$selectedProjectId, $entityId, $comment->commentId()->id()], ['class' => 'system']) !!}
                                @endif
                                <span class="dot">•</span>
                                <a href="javascript:void(0)" class="ajax-delete system"
                                   data-href="{{ route("project.{$controller}.ajax_delete_comment", [$selectedProjectId, $entityId, $comment->commentId()->id()]) }}"
                                   data-confirm="{{ trans('app.confirm_delete_comment') }}"
                                   data-container="comment-{{ $comment->commentId()->id() }}"
                                   data-deleted="{{ trans('app.comment_deleted') }}">{{ trans('app.delete') }}</a>
                            @endif
                        </div>
                        @if (!$comment->attachments()->isEmpty())
                            <div class="mt5">

                                <div class="clearfix fs12 discussion-attachments">
                                    @foreach ($comment->attachments() as $attachment)
                                        <div id="attachment-{{ $attachment->attachmentId()->id() }}" class="attachment-box">

                                            <div class="attachment-box-preview">
                                                <div class="delete-attachment-link">
                                                    <a href="javascript:void(0)" class="ajax-delete"
                                                       data-href="{{ route("project.{$controller}.ajax_delete_comment_attachment", [$selectedProjectId, $entityId, $comment->commentId()->id(), $attachment->attachmentId()->id()]) }}"
                                                       data-confirm="{{ trans('app.confirm_delete_attachment') }}"
                                                       data-container="attachment-{{ $attachment->attachmentId()->id() }}">
                                                        <i class="glyphicon glyphicon-remove fs10 red"></i>
                                                    </a>
                                                </div>

                                                @if ($attachment->type()->isImage())
                                                    <a href="{{ route('project.attachment', [$attachment->attachmentId()->id(), $attachment->name()]) }}" rel ="lightbox" data-title="{{ $attachment->name() }}"
                                                       data-lightbox="preview">
                                                        <img src="/thumbs/{{ thumb_url($attachment->attachmentId()->id()) }}" width="140" height="100">
                                                    </a>
                                                @else
                                                    <a href="{{ route('project.attachment.download', [$attachment->attachmentId()->id(), $attachment->name()]) }}" class="no-preview">
                                                        {{ pathinfo($attachment->name(), PATHINFO_EXTENSION) }}
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="attachment-box-controls">
                                                {!! Html::linkRoute('project.attachment.download', $attachment->name(), [$attachment->attachmentId()->id(), $attachment->name()]) !!}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        {{--@if (isset($discussion) && $discussion)--}}
            {{--<div class="following-icon">--}}
                {{--<a href="{{ route('follow', ['discussion', $discussion->id]) }}" class="{{ $discussion->is_following ? 'active' : '' }}" title="{{ trans('app.follow') }}"><i class="glyphicon glyphicon-envelope"></i></a>--}}
            {{--</div>--}}
        {{--@elseif (isset($event) && $event)--}}
            {{--<div class="following-icon">--}}
                {{--<a href="{{ route('follow', ['event', $event->id]) }}" class="{{ $event->is_following ? 'active' : '' }}" title="{{ trans('app.follow') }}"><i class="glyphicon glyphicon-envelope"></i></a>--}}
            {{--</div>--}}
        {{--@endif--}}
        <h2 class="mb25 fs18 no-items">{{ trans('app.no_comments') }}</h2>
    @endif

    <div class="post-comment mt30">
        <?php if ($controller == 'todoItem') {
            $formRoute = ['todoItem.store_comment', $project, $todo, $todoItem];
        } else {
            $formRoute = ['project.' . $controller . '.store_comment', $selectedProjectId, $entityId];
        } ?>
        {!! Form::open(['route' => $formRoute, 'id' => 'discussion-comment-form']) !!}
        <div class="form-group">
            {!! Form::label('comment', trans('app.post_comment_action')) !!}
            <div>
                {!! Form::textarea('content', null, ['class'=>'form-control', 'rows' => '3']) !!}
            </div>
            {!! Form::error('content') !!}
        </div>
        <div class="form-control-submit">
            <div class="row">
                <div class="col-md-3">
                    {!! Form::submit(trans('app.post'), ['class' => 'btn btn-primary']) !!}
                </div>
                <div class="col-md-9" style="text-align: right;">
                    @include('project.partials.attachments_control')
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
