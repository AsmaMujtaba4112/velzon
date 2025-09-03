@extends('layout.inapp')

@section('title', 'Vehicle-Categories')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Vehicle Categories</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            Add New Category
        </button>
    </div>

    @if( session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="categoriesTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>SR No.</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $index => $cat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->slug }}</td>
                    <td>
                        <span class="badge {{ $cat->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $cat->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $cat->created_at->format('d-M-Y') }}</td>
                    <td>{{ $cat->updated_at->format('d-M-Y') }}</td>
                    <td>
                        <a href="{{ route('vehicle-categories.edit', $cat->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <button type="button"
                            class="btn btn-danger btn-sm btn-delete"
                            data-url="{{ route('vehicle-categories.destroy', $cat->id) }}"
                            data-name="{{ $cat->name }}">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal for Add Category -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('vehicle-categories.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Vehicle Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Slug</label>
            <input type="text" name="slug" class="form-control">
            <small class="text-muted">Leave empty to auto-generate</small>
          </div>
          <div class="mb-3">
            <label>Status</label>
            <select name="is_active" class="form-control">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#categoriesTable').DataTable({
        "pageLength": 10
    });
});
</script>
@endsection
