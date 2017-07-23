<div class="navigation">
    {!! Html::linkRoute('project.discussion.index', trans('app.discussions'), [$selectedProjectId]) !!}
    {{--{!! Html::linkRoute('project.todo.index', trans('app.todos'), [$selectedProjectId]) !!}--}}
    {!! Html::linkRoute('project.event.index', trans('app.events'), [$selectedProjectId], ['class' => 'active']) !!}
    {{--{!! Html::linkRoute('project.project.activity', trans('app.activity_log'), [$selectedProjectId]) !!}--}}
</div>
