@extends('layouts.app')

@section('title', trans('app.title_edit_comment'))

@section('content')

    @include('project.partials.discussion_navigation')

    @include('project.partials.edit_comment', ['controller' => 'discussion', 'entityId' => $discussionId])

@endsection
