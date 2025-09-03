@extends('layout.inapp')

@section('title', 'Locations')

@section('content')

<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Locations</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLocationModal">
            Add New Location
        </button>
    </div>

    {{-- Success Message --}}
    @if( session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="locationsTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>
                    <div class="form-check">
                        <!-- Master Checkbox -->
                        <input class="form-check-input fs-15" type="checkbox" id="checkAll">
                    </div>
                </th>
                <th>SR No.</th>
                <th>Location</th>
                <th>Slug</th>
                <th>Type</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($locations as $key => $location)
            <tr>
                <td>
                    <div class="form-check">
                        <!-- Row Checkbox -->
                        <input class="form-check-input row-check fs-15"
                            type="checkbox"
                            name="locations[]"
                            value="{{ $location->id }}">
                    </div>
                </td>
                <td>{{ $key+1 }}</td>
                <td>{{ $location->name }}</td>
                <td>{{ $location->slug }}</td>
                <td>
                    @if($location->type == 'town')
                        <span class="badge bg-success">Town</span>
                    @elseif ($location->type == 'city')
                        <span class="badge bg-danger">City</span>
                    @else
                        <span class="badge bg-danger">villege</span>
                    @endif
                </td>
                <td>
                    @if($location->status == 'active')
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>
                <td>{{ $location->created_at->format('d-M-Y') }}</td>
                <td>{{ $location->updated_at->format('d-M-Y') }}</td>
                <td>
                    <a href="{{ route('locations.edit',$location->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <button type="button"
                            class="btn btn-danger btn-sm btn-delete"
                            data-url="{{ route('locations.destroy', $location->id) }}"
                            data-name="{{ $location->name }}">
                        Delete
                    </button>


                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Single hidden delete form used by JS -->
    <form id="deleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<!-- Modal for Add Location -->
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('locations.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Location</h5>
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
            <label>Type</label>
            <select name="type" class="form-control">
              <option value="town">Town</option>
              <option value="city">City</option>
              <option value="villege">Villege</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
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
<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#locationsTable').DataTable({
        "pageLength": 10
    });
});
</script>
@endsection
