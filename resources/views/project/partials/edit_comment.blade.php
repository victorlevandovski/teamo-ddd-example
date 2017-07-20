{!! Form::flash() !!}

<section class="flying">

    {!! Form::model(['content' => $comment->content()], [
        'route' => ["project.{$controller}.update_comment", $selectedProjectId, $entityId, $comment->commentId()->id()],
        'method' => 'patch',
    ]) !!}

    <div class="form-group">
        {!! Form::label('content', trans('app.label_comment')) !!}
        <div>
            {!! Form::textarea('content', strip_tags($comment->content()), ['class' => 'form-control']) !!}
        </div>
        {!! Form::error('content') !!}
    </div>

    <div class="form-control-submit">
        {!! Form::submit(trans('app.save'), ['class' => 'btn btn-primary']) !!}
        {!! Html::cancel(route("project.{$controller}.show", [$selectedProjectId, $entityId])) !!}
    </div>

    {!! Form::close() !!}

</section>
