@extends('layout.inapp')

@section('title','Categories')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <button class="btn btn-success mb-3" id="btnAddCategory">Add Category</button>
          <table id="categoriesTable" class="table table-striped table-bordered">
            <thead>
              <tr><th>ID</th><th>Name</th><th>Slug</th><th>Active</th><th>Actions</th></tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="categoryForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="category_id">
          <div class="mb-3">
            <label>Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Slug</label>
            <input type="text" id="slug" name="slug" class="form-control" required>
          </div>
          <div class="form-check">
            <input type="checkbox" id="is_active" name="is_active" class="form-check-input" checked>
            <label class="form-check-label">Active</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

<script>
$(function(){
  $.ajaxSetup({
    headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept':'application/json' }
  });

  function slugify(text){ return text.toString().toLowerCase().trim().replace(/[^\w\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-'); }
  $('#name').on('input', ()=> $('#slug').val(slugify($('#name').val())));

  var table = $('#categoriesTable').DataTable({
    ajax: { url: '/api/categories', dataSrc: '' },
    columns: [
      {data:'id'},{data:'name'},{data:'slug'},
      {data:'is_active', render:(d,r)=> `<input type="checkbox" class="status-toggle" data-id="${r.id}" ${d? 'checked':''}>`},
      {data:null, orderable:false, render:(d,r)=>`<button class="btn btn-sm btn-primary editBtn" data-id="${r.id}">Edit</button> <button class="btn btn-sm btn-danger deleteBtn" data-id="${r.id}">Delete</button>`}
    ],
    dom:'Bfrtip', buttons:['copy','csv','excel','pdf','print']
  });

  $('#btnAddCategory').click(function(){ $('#categoryForm')[0].reset(); $('#category_id').val(''); $('#categoryModal').modal('show'); });

  $('#categoryForm').submit(function(e){
    e.preventDefault();
    let id = $('#category_id').val();
    let url = id ? `/api/categories/${id}` : '/api/categories';
    let method = id ? 'PUT' : 'POST';
    let data = { name: $('#name').val(), slug: $('#slug').val(), is_active: $('#is_active').is(':checked')?1:0 };
    $.ajax({ url, method, data })
      .done(res => { toastr.success(res.message); $('#categoryModal').modal('hide'); table.ajax.reload(null,false); })
      .fail(xhr => {
        if(xhr.status===422){ let errs = xhr.responseJSON.errors; toastr.error(Object.values(errs).flat().join('\n')); }
        else toastr.error('Server error');
      });
  });

  $(document).on('click','.editBtn', function(){
    let id = $(this).data('id');
    $.get(`/api/categories/${id}`, function(res){
      $('#category_id').val(res.id); $('#name').val(res.name); $('#slug').val(res.slug); $('#is_active').prop('checked', res.is_active); $('#categoryModal').modal('show');
    });
  });

  $(document).on('click','.deleteBtn', function(){
    let id = $(this).data('id');
    Swal.fire({ title:'Are you sure?', text:'Are you sure you want to delete this?', icon:'warning', showCancelButton:true })
      .then(result => { if(result.isConfirmed){
        $.ajax({ url:`/api/categories/${id}`, method:'DELETE' })
          .done(res=>{ toastr.success(res.message); table.ajax.reload(null,false); })
          .fail(()=>toastr.error('Delete failed'));
      }});
  });

  $(document).on('change','.status-toggle', function(){
    let id = $(this).data('id'), status = $(this).is(':checked')?1:0;
    $.ajax({ url:`/api/categories/${id}/toggle-status`, method:'PATCH', data:{ is_active: status } })
      .done(res=>{ toastr.success(res.message); table.ajax.reload(null,false); })
      .fail(()=>{ toastr.error('Status change failed'); table.ajax.reload(null,false); });
  });
});
</script>
@endsection
