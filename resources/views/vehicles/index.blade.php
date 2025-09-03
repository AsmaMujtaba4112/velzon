@extends('layout.inapp')

@section('title', 'Vehicles')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Vehicles</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
            Add New Vehicle
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="vehiclesTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>SR No.</th>
                <th>Name</th>
                <th>Category</th>
                <th>Town</th>
                <th>Status</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $index => $vehicle)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $vehicle->name }}</td>
                    <td>{{ $vehicle->category->name ?? '-' }}</td>
                    <td>{{ $vehicle->town->name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $vehicle->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                            {{ $vehicle->status }}
                        </span>
                    </td>
                    <td>{{ $vehicle->created_at->format('d-M-Y') }}</td>
                    <td>{{ $vehicle->updated_at->format('d-M-Y') }}</td>
                    <td>
                        <button type="button"
                            class="btn btn-danger btn-sm btn-delete"
                            data-url="{{ route('vehicles.destroy', $vehicle->id) }}"
                            data-name="{{ $vehicle->name ?? 'Vehicle' }}">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('vehicles.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Vehicle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Vehicle Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Category</label>
            <select name="category_id" id="categoryDropdown" class="form-control" required></select>
          </div>
          <div class="mb-3">
            <label>Town</label>
            <select name="town_id" id="townDropdown" class="form-control" required></select>
          </div>
          <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
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
<script>
$(document).ready(function () {
    $('#vehiclesTable').DataTable();

    // Load categories
    $.get("{{ route('ajax.categories') }}", function(data) {
        let dropdown = $('#categoryDropdown');
        dropdown.empty().append('<option value="">Select Category</option>');
        $.each(data, function(_, category) {
            dropdown.append('<option value="'+ category.id +'">'+ category.name +'</option>');
        });
    });

    // Load towns
    $.get("{{ route('ajax.towns') }}", function(data) {
        let dropdown = $('#townDropdown');
        dropdown.empty().append('<option value="">Select Town</option>');
        $.each(data, function(_, town) {
            dropdown.append('<option value="'+ town.id +'">'+ town.name +'</option>');
        });
    });
});
</script>
@endsection
