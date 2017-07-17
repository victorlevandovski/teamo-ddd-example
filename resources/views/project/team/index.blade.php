@extends('layouts.app')

@section('title', trans('app.team'))

@section('content')

    {!! Form::flash() !!}

    <div class="flying">
        <h2>{{ trans('app.team') }}</h2>
        @if ($teamMembers = $project->teamMembers())
            <div class="row mt30">
                @for ($i = 1; $i <= count($teamMembers); $i++)
                    <?php $teamMember = $teamMembers[$i - 1]; ?>
                    <div class="col-sm-4 project-team-member">
                        <img src="{{ avatar_of_id($teamMember->teamMemberId()->id(), 96) }}" class="avatar48">
                        <div class="mt5">
                            <b>
                                {{ $teamMember->name() }}
                                @if ($project->owner()->equals($teamMember->teamMemberId())) ({{ trans('app.project_admin') }}) @endif
                            </b>
                        </div>
{{--                        <div>{!! Html::mailto($user->email) !!}</div>--}}
                        @if (is_authenticated($project->owner()) && !$project->owner()->equals($teamMember->teamMemberId()))
                            <div class="mt5">
                                {!! Html::linkRoute('project.team.remove_team_member', trans('app.remove'), [$project->projectId()->id(), $teamMember->teamMemberId()->id()],
                                ['class' => 'system confirm fs12 btn btn-xs btn-default', 'data-confirm' => trans('app.confirm_remove_user')]) !!}
                            </div>
                        @endif
                    </div>
                    @if ($i %3 == 0 && $i < count($teamMembers)) </div><div class="row mt30"> @endif
                @endfor
            </div>
        @else
            <div class="no-items">
                {{ trans('app.no_members') }}
            </div>
        @endif
    </div>

    @if (is_authenticated($project->owner()))

        <div class="flying mt30">

            <h2>{{ trans('app.invited_members') }}</h2>
            @if (!$invites->isEmpty())
                <ul class="ulb">
                    @foreach ($invites as $invite)
                        <li>
                            {{ $invite->email }}

                            {!! Html::linkRoute('project.team.remove_invite', trans('app.cancel'), [$project, $invite],
                                ['class'=>'system confirm', 'data-confirm' => trans('app.confirm_cancel_invite')]) !!}
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="no-items">
                    {{ trans('app.no_invitations') }}
                </div>
                <div class="text-danger mt30">
                    Invitations are not implemented yet.
                    To manually add team member to a project please register new user and then add a connection between team member and project into <i>projects_team_members</i> table.
                </div>
            @endif

            <div class="mt30">
                {!! Form::open() !!}

                <div class="form-group">
                    {!! Form::label('email', trans('app.invite_new_member_action')) !!}
                    <div>
                        {!! Form::text('email', null, ['class' => 'form-control w50p', 'placeholder' => 'matt.damon@gmail.com']) !!}
                    </div>
                    {!! Form::error('email') !!}
                </div>

                <div class="form-control-submit">
                    {!! Form::submit(trans('app.invite_action'), ['class' => 'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}
            </div>

        </div>

    {{--@else--}}
        {{--<div class="flying mt20">--}}
            {{--{!! Html::linkRoute('project.remove_me', trans('app.quit_project_action'), [$project],--}}
            {{--['data-confirm' => trans('app.confirm_quit_project'), 'class' => 'btn btn-primary confirm']) !!}--}}
        {{--</div>--}}
    @endif

@endsection
