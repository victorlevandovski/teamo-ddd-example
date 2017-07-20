@extends('layouts.app')

@section('title', trans('app.title_add_discussion'))

@section('header')
    {{ trans('app.title_add_discussion') }}
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/jquery-ui.custom.min.js"></script>
    <script type="text/javascript" src="/js/jquery.fileupload.js"></script>
    <script type="text/javascript" src="/js/jquery.iframe-transport.js"></script>
    <script type="text/javascript" src="/js/discussion.js"></script>
    <script src="/ckeditor/ckeditor.js"></script>
@append

@section('content')

    @include('project.partials.discussion_navigation')

    {!! Form::flash() !!}

    <section class="flying">

        {!! Form::open([
            'route' => ['project.discussion.store', $selectedProjectId],
            'files' => true,
            'id' => 'discussion-form'
        ]) !!}

        <div class="form-group">
            {!! Form::label('topic', trans('app.label_topic')) !!}
            <div>
                {!! Form::text('topic', null, ['class' => 'form-control focus']) !!}
            </div>
            {!! Form::error('topic') !!}
        </div>

        <div class="form-group">
            {!! Form::label('content', trans('app.label_content')) !!}
            <div>
                {!! Form::textarea('content') !!}
                <script>
                    CKEDITOR.replace('content');
                </script>
            </div>
            {!! Form::error('content') !!}
        </div>

        <div class="form-control-submit">
            <div class="row">
                <div class="col-md-3">
                    {!! Form::submit(trans('app.save'), ['class' => 'btn btn-primary']) !!}
                    {!! Html::cancel(route('project.discussion.index', [$selectedProjectId])) !!}
                </div>
                <div class="col-md-9" style="text-align: right;">
                    @include('project.partials.attachments_control')
                </div>
            </div>
        </div>

        {!! Form::close() !!}

    </section>
@endsection
