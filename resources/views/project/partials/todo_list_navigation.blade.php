<div class="navigation">
    {!! Html::linkRoute('project.discussion.index', trans('app.discussions'), [$selectedProjectId]) !!}
    {!! Html::linkRoute('project.todo_list.index', trans('app.todos'), [$selectedProjectId], ['class' => 'active']) !!}
    {!! Html::linkRoute('project.event.index', trans('app.events'), [$selectedProjectId]) !!}
    {{--{!! Html::linkRoute('project.project.activity', trans('app.activity_log'), [$selectedProjectId]) !!}--}}
</div>
