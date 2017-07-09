@extends('layouts.public')

@section('content')
    <div class="public-container">

        <div class="flying form-container">

            <div class="form-title">{{ trans('public.login') }}</div>

            <form method="POST" action="{{ url('/login') }}">

                {!! csrf_field() !!}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="control-label">{{ trans('public.email') }}</label>
                    <div>
                        <input type="email" class="form-control focus" name="email" value="{{ old('email') }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="control-label">{{ trans('public.password') }}</label>

                    <div>
                        <input type="password" class="form-control" name="password" value="">

                        @if ($errors->has('password'))
                            <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember" checked> {{ trans('public.remember') }}
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">
                        {{ trans('public.login_action') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
