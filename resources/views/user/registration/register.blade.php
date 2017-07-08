@extends('layouts.public')

@section('content')
    <script type="text/javascript" src="/js/jstz.min.js"></script>
    <script type="text/javascript">
        function getLocalTimezone()
        {
            var timezone = jstz.determine();
            return timezone.name();
        }
    </script>

    <div class="public-container">

        <div style="text-align: center">{!! Form::flash() !!}</div>

        <div class="flying form-container">

            <div class="form-title">{{ trans('public.registration') }}</div>

            <form method="POST" action="{{ url('/register') }}" onsubmit="$('#timezone').val(getLocalTimezone()); return true;">

                {!! csrf_field() !!}

                <div class="form-group">
                    <label class="control-label">{{ trans('public.name') }}</label>
                    <div>
                        <input type="text" class="form-control focus" name="name" value="{{ old('name') }}" placeholder="{{ trans('public.placeholder_name') }}">
                        {!! Form::error('name') !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">{{ trans('public.email') }}</label>
                    <div>
                        <input type="email" class="form-control" name="email" value="{{ old('email') ? old('email') : $email }}"  placeholder="matt.damon@example.com">
                        {!! Form::error('email') !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">{{ trans('public.password') }}</label>
                    <div>
                        <input type="password" class="form-control" name="password" placeholder="">
                        {!! Form::error('password') !!}
                    </div>
                </div>

                <input type="hidden" name="timezone" id="timezone">

                <div>
                    <button type="submit" class="btn btn-primary">{{ trans('public.register_action') }}</button>
                </div>

            </form>
        </div>
    </div>

@endsection
