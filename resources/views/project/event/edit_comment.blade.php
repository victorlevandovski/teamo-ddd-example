@extends('layouts.app')

@section('title', trans('app.title_edit_comment'))

@section('content')

    @include('project.partials.event_navigation')

    @include('project.partials.edit_comment', ['controller' => 'event', 'entityId' => $eventId])

@endsection
