@extends('layouts.app')

@section('title', trans('app.title_add_event'))

@section('header')
    {{ trans('app.title_add_event') }}
@endsection

@section('content')

    @include('project.partials.event_navigation')

    {!! Form::flash() !!}

    <section class="flying">
        <div class="panel-s">

            {!! Form::open(['route' => ['project.event.store', $selectedProjectId], 'id' => 'discussion-form']) !!}

            {!! Form::hidden('timezone', $userTimezone) !!}

            <div class="form-group">
                {!! Form::label('name', trans('app.label_event_name')) !!}
                <div>
                    {!! Form::text('name', null, ['class' => 'form-control focus']) !!}
                </div>
                {!! Form::error('name') !!}
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('day', trans('app.label_day')) !!}
                        <div>
                            {!! Form::text('day', utc_date_formatted(time(), $userDateFormat, $userTimezone), ['class' => 'form-control', 'id'=> 'day']) !!}
                        </div>
                        {!! Form::error('day') !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('hour', trans('app.label_hour')) !!}
                        <div>
                            {!! Form::select('hour', timepicker_hours($userTimeFormat), 12, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('minute', trans('app.label_minute')) !!}
                        <div>
                            {!! Form::select('minute', timepicker_minutes(), null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('details', trans('app.label_details')) !!} ({{ trans('app.label_optional') }})
                <div>
                    {!! Form::textarea('details') !!}
                    <script>
                        CKEDITOR.replace('details');
                    </script>
                </div>
                {!! Form::error('details') !!}
            </div>

            <div class="form-control-submit">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::submit(trans('app.save'), ['class' => 'btn btn-primary']) !!}
                        {!! Html::cancel(route('project.event.index', [$selectedProjectId])) !!}
                    </div>
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/jquery-ui.custom.min.js"></script>
    <link href="/datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <script src="/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="/datepicker/locales/bootstrap-datepicker.ru.min.js"></script>
    <script src="/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/js/event.js?v1"></script>
@append
