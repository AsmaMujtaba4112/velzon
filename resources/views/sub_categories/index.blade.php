@extends('layout.inapp')
@section('title','Sub-Categories')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <button class="btn btn-success mb-3" id="btnAddSub">Add Sub-Category</button>
          <table id="subsTable" class="table table-striped table-bordered">
            <thead>
              <tr><th>ID</th><th>Name</th><th>Slug</th><th>Category</th><th>Active</th><th>Actions</th></tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="subModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="subForm">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Sub-Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <input type="hidden" id="sub_id">
          <div class="mb-3"><label>Name</label><input type="text" id="s_name" class="form-control" required></div>
          <div class="mb-3"><label>Slug</label><input type="text" id="s_slug" class="form-control" required></div>
          <div class="mb-3"><label>Category</label>
            <select id="s_category_id" class="form-control" required><option value="">Select</option></select>
          </div>
          <div class="form-check"><input type="checkbox" id="s_is_active" class="form-check-input" checked><label class="form-check-label">Active</label></div>
        </div>
        <div class="modal-footer"><button class="btn btn-primary" type="submit">Save</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(function(){
  $.ajaxSetup({ headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept':'application/json' } });

  function slugify(t){ return t.toString().toLowerCase().trim().replace(/[^\w\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-'); }
  $('#s_name').on('input', ()=> $('#s_slug').val(slugify($('#s_name').val())));

  // load categories for select
  function loadCategories(selectEl, callback){
    $.get('/api/categories', function(data){
      let html = '<option value="">Select</option>';
      data.forEach(c => html += `<option value="${c.id}">${c.name}</option>`);
      $(selectEl).html(html);
      if(callback) callback();
    });
  }

  var table = $('#subsTable').DataTable({
    ajax: { url: '/api/sub-categories', dataSrc: '' },
    columns: [
      {data:'id'},{data:'name'},{data:'slug'},{data:'category.name', defaultContent:''},
      {data:'is_active', render:(d,r)=> `<input type="checkbox" class="sub-status" data-id="${r.id}" ${d? 'checked':''}>`},
      {data:null, orderable:false, render:(d,r)=>`<button class="btn btn-sm btn-primary editSub" data-id="${r.id}">Edit</button> <button class="btn btn-sm btn-danger delSub" data-id="${r.id}">Delete</button>`}
    ],
    dom:'Bfrtip', buttons:['copy','csv','excel','pdf','print']
  });

  $('#btnAddSub').click(function(){ $('#subForm')[0].reset(); $('#sub_id').val(''); loadCategories('#s_category_id', ()=> $('#subModal').modal('show')); });

  $('#subForm').submit(function(e){
    e.preventDefault();
    let id = $('#sub_id').val();
    let url = id ? `/api/sub-categories/${id}` : '/api/sub-categories';
    let method = id ? 'PUT' : 'POST';
    let data = { name: $('#s_name').val(), slug: $('#s_slug').val(), category_id: $('#s_category_id').val(), is_active: $('#s_is_active').is(':checked')?1:0 };
    $.ajax({ url, method, data })
      .done(res => { toastr.success(res.message); $('#subModal').modal('hide'); table.ajax.reload(null,false); })
      .fail(xhr=>{ if(xhr.status===422) toastr.error(Object.values(xhr.responseJSON.errors).flat().join('\n')); else toastr.error('Error'); });
  });

  $(document).on('click','.editSub', function(){
    let id = $(this).data('id');
    $.get(`/api/sub-categories/${id}`, function(res){
      loadCategories('#s_category_id', function(){
        $('#sub_id').val(res.id); $('#s_name').val(res.name); $('#s_slug').val(res.slug); $('#s_category_id').val(res.category_id); $('#s_is_active').prop('checked', res.is_active); $('#subModal').modal('show');
      });
    });
  });

  $(document).on('click','.delSub', function(){
    let id = $(this).data('id');
    Swal.fire({ title:'Are you sure?', text:'Are you sure you want to delete this?', icon:'warning', showCancelButton:true }).then(res=>{ if(res.isConfirmed){
      $.ajax({ url:`/api/sub-categories/${id}`, method:'DELETE' }).done(r=>{ toastr.success(r.message); table.ajax.reload(null,false); }).fail(()=>toastr.error('Delete failed'));
    }});
  });

  $(document).on('change','.sub-status', function(){
    let id = $(this).data('id'), status = $(this).is(':checked')?1:0;
    $.ajax({ url:`/api/sub-categories/${id}`, method:'PUT', data:{ is_active: status, name: 'temp', slug: 'temp-'+id, category_id: $('#s_category_id').val() } })
      .done(res=>{ toastr.success('Status updated'); table.ajax.reload(null,false); })
      .fail(()=>{ toastr.error('Status change failed'); table.ajax.reload(null,false); });
  });
});
</script>
@endsection
