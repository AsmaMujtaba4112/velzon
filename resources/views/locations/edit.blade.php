@extends('layout.inapp')
@section('title','Edit Location')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Edit Location</h4>

    <form action="{{ route('locations.update', $location->id) }}" method="POST" class="card p-3">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input name="name" class="form-control" value="{{ old('name',$location->name) }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Slug (optional)</label>
            <input name="slug" class="form-control" value="{{ old('slug',$location->slug) }}">
            @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select" required>
                <option value="town"   {{ old('type',$location->type)=='town' ? 'selected':'' }}>Town</option>
                <option value="city" {{ old('type',$location->type)=='city' ? 'selected':'' }}>City</option>
                <option value="villege" {{ old('type',$location->type)=='villege' ? 'selected':'' }}>Villege</option>
            </select>
            @error('type') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="active"   {{ old('status',$location->status)=='active' ? 'selected':'' }}>Active</option>
                <option value="inactive" {{ old('status',$location->status)=='inactive' ? 'selected':'' }}>Inactive</option>
            </select>
            @error('status') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-success">Update</button>
            <a href="{{ route('locations.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
