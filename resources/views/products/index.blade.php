@extends('layout.inapp')
@section('title','Products')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <button class="btn btn-success mb-3" id="btnAddProduct">Add Product</button>
          <table id="productsTable" class="table table-striped table-bordered">
            <thead>
              <tr><th>ID</th><th>Name</th><th>Qty</th><th>Category</th><th>Sub-Category</th><th>Active</th><th>Actions</th></tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="productModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form id="productForm">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <input type="hidden" id="p_id">
          <div class="row">
            <div class="col-md-6 mb-3"><label>Name</label><input type="text" id="p_name" class="form-control" required></div>
            <div class="col-md-3 mb-3"><label>Quantity</label><input type="number" id="p_quantity" class="form-control" min="0" required></div>
            <div class="col-md-3 mb-3"><label>Unit Price</label><input type="number" step="0.01" id="p_unit_price" class="form-control" required></div>
            <div class="col-md-3 mb-3"><label>Cost Price</label><input type="number" step="0.01" id="p_cost_price" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label>Category</label><select id="p_category_id" class="form-control" required><option value="">Select</option></select></div>
            <div class="col-md-6 mb-3"><label>Sub-Category</label><select id="p_sub_category_id" class="form-control"><option value="">Select</option></select></div>
          </div>

          <div class="mb-3">
            <label>Images</label>
            <input type="file" id="images" multiple accept="image/*" class="form-control">
            <div id="imagePreview" class="mt-2 d-flex flex-wrap gap-2"></div>
            <div id="existingImages" class="mt-2 d-flex flex-wrap gap-2"></div>
          </div>

          <div class="form-check"><input type="checkbox" id="p_is_active" class="form-check-input" checked><label class="form-check-label">Active</label></div>
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

  // selected files array for preview and upload
  let selectedFiles = [];
  function resetForm(){ selectedFiles = []; $('#imagePreview').empty(); $('#existingImages').empty(); $('#productForm')[0].reset(); $('#p_id').val(''); }

  // load categories to selects
  function loadCategories(){
    $.get('/api/categories', function(data){
      let opts = '<option value="">Select</option>';
      data.forEach(c => opts += `<option value="${c.id}">${c.name}</option>`);
      $('#p_category_id').html(opts);
    });
  }
  loadCategories();

  $('#p_category_id').on('change', function(){
    let id = $(this).val();
    if(!id){ $('#p_sub_category_id').html('<option value="">Select</option>'); return; }
    $.get(`/api/categories/${id}/sub-categories`, function(data){
      let opts = '<option value="">Select</option>';
      data.forEach(s => opts += `<option value="${s.id}">${s.name}</option>`);
      $('#p_sub_category_id').html(opts);
    });
  });

  $('#images').on('change', function(e){
    selectedFiles = Array.from(e.target.files);
    $('#imagePreview').empty();
    selectedFiles.forEach((f, idx)=>{
      let url = URL.createObjectURL(f);
      $('#imagePreview').append(`<div class="position-relative" data-idx="${idx}"><img src="${url}" style="width:120px;height:90px;object-fit:cover;border:1px solid #ddd;"><button class="btn btn-sm btn-danger remove-local" data-idx="${idx}" style="position:absolute;top:2px;right:2px">X</button></div>`);
    });
  });

  $(document).on('click','.remove-local', function(){ let idx = $(this).data('idx'); selectedFiles.splice(idx,1); $(this).parent().remove(); });

  var productTable = $('#productsTable').DataTable({
    ajax: { url: '/api/products', dataSrc: '' },
    columns: [
      {data:'id'},{data:'name'},{data:'quantity'},{data:'category.name', defaultContent:''},{data:'subCategory.name', defaultContent:''},
      {data:'is_active', render:(d,r)=> `<input type="checkbox" class="prod-status" data-id="${r.id}" ${d? 'checked':''}>`},
      {data:null, orderable:false, render:(d,r)=> `<button class="btn btn-sm btn-primary editProd" data-id="${r.id}">Edit</button> <button class="btn btn-sm btn-danger delProd" data-id="${r.id}">Delete</button>`}
    ],
    dom:'Bfrtip', buttons:['copy','csv','excel','pdf','print']
  });

  $('#btnAddProduct').click(function(){ resetForm(); loadCategories(); $('#productModal').modal('show'); });

  $('#productForm').submit(function(e){
    e.preventDefault();
    let id = $('#p_id').val();
    let url = id ? `/api/products/${id}` : '/api/products';
    let method = id ? 'POST' : 'POST'; // use _method PUT for update
    let fd = new FormData();
    fd.append('name', $('#p_name').val());
    fd.append('quantity', $('#p_quantity').val());
    fd.append('category_id', $('#p_category_id').val());
    fd.append('sub_category_id', $('#p_sub_category_id').val() || '');
    fd.append('unit_price', $('#p_unit_price').val());
    fd.append('cost_price_per_unit', $('#p_cost_price').val());
    fd.append('is_active', $('#p_is_active').is(':checked')?1:0);
    if(id){ fd.append('_method','PUT'); }
    selectedFiles.forEach(f => fd.append('images[]', f));
    $.ajax({ url, method:'POST', data:fd, processData:false, contentType:false })
      .done(res => { toastr.success(res.message); $('#productModal').modal('hide'); productTable.ajax.reload(null,false); resetForm(); })
      .fail(xhr => { if(xhr.status===422) toastr.error(Object.values(xhr.responseJSON.errors).flat().join('\n')); else toastr.error('Error'); });
  });

  $(document).on('click','.editProd', function(){
    resetForm();
    let id = $(this).data('id');
    $.get(`/api/products/${id}`, function(res){
      $('#p_id').val(res.id); $('#p_name').val(res.name); $('#p_quantity').val(res.quantity);
      $('#p_unit_price').val(res.unit_price); $('#p_cost_price').val(res.cost_price_per_unit);
      $('#p_is_active').prop('checked', res.is_active);
      loadCategories();
      setTimeout(()=>{ $('#p_category_id').val(res.category_id).trigger('change'); setTimeout(()=>{ $('#p_sub_category_id').val(res.sub_category_id); },250); },200);
      // show existing images
      if(res.images && res.images.length){
        $('#existingImages').empty();
        res.images.forEach(p => {
          let url = '/storage/' + p;
          $('#existingImages').append(`<div class="position-relative" data-path="${p}"><img src="${url}" style="width:120px;height:90px;object-fit:cover;border:1px solid #ddd;"><button class="btn btn-sm btn-danger remove-remote" data-path="${p}" style="position:absolute;top:2px;right:2px">X</button></div>`);
        });
      }
      $('#productModal').modal('show');
    });
  });

  $(document).on('click','.remove-remote', function(){
    let path = $(this).data('path'), parent = $(this).parent();
    Swal.fire({ title:'Delete image?', showCancelButton:true }).then(res=>{ if(res.isConfirmed){
      $.ajax({ url:`/api/products/${$('#p_id').val()}/images`, method:'DELETE', data:{ path } })
        .done(r=>{ toastr.success(r.message); parent.remove(); })
        .fail(()=>toastr.error('Image delete failed'));
    }});
  });

  $(document).on('click','.delProd', function(){
    let id = $(this).data('id');
    Swal.fire({ title:'Are you sure?', showCancelButton:true }).then(res=>{ if(res.isConfirmed){
      $.ajax({ url:`/api/products/${id}`, method:'DELETE' }).done(r=>{ toastr.success(r.message); productTable.ajax.reload(null,false); }).fail(()=>toastr.error('Delete failed'));
    }});
  });

  $(document).on('change','.prod-status', function(){
    let id = $(this).data('id'), status = $(this).is(':checked')?1:0;
    $.ajax({ url:`/api/products/${id}/toggle-status`, method:'PATCH', data:{ is_active: status } })
      .done(r=>{ toastr.success(r.message); productTable.ajax.reload(null,false); })
      .fail(()=>{ toastr.error('Status change failed'); productTable.ajax.reload(null,false); });
  });
});
</script>
@endsection
