@extends('layout.inapp')

@section('content')
<div class="container">
    <h1>Location Details</h1>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $location->id }}</td></tr>
        <tr><th>Name</th><td>{{ $location->name }}</td></tr>
        <tr><th>Slug</th><td>{{ $location->slug }}</td></tr>
        <tr><th>Type</th><td>{{ $location->type }}</td></tr>
        <tr><th>Status</th><td>{{ $location->status }}</td></tr>
        <tr><th>Created At</th><td>{{ $location->created_at->format('d-M-Y H:i') }}</td></tr>
        <tr><th>Updated At</th><td>{{ $location->updated_at->format('d-M-Y H:i') }}</td></tr>
    </table>

    <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-primary">Edit</a>
    <a href="{{ route('locations.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
