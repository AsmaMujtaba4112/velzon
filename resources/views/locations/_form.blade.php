@csrf

<div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $location->name ?? '') }}" required>
    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="form-group">
    <label for="slug">Slug (optional)</label>
    <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $location->slug ?? '') }}">
    <small class="form-text text-muted">If left blank, slug will be auto-generated.</small>
    @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="form-group">
    <label for="type">Type</label>
    <select name="type" id="type" class="form-control">
        <option value="town" {{ old('type', $location->type ?? '') === 'town' ? 'selected' : '' }}>Town</option>
        <option value="city" {{ old('type', $location->type ?? '') === 'city' ? 'selected' : '' }}>City</option>
        <option value="villege" {{ old('type', $location->type ?? '') === 'villege' ? 'selected' : '' }}>Villege</option>
    </select>
    @error('type') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="form-group">
    <label for="status">Status</label>
    <select name="status" id="status" class="form-control">
        <option value="Active" {{ old('status', $location->status ?? '') === 'Active' ? 'selected' : '' }}>Active</option>
        <option value="Inactive" {{ old('status', $location->status ?? '') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('locations.index') }}" class="btn btn-secondary">Cancel</a>

<!-- Optional JS to auto generate slug -->
@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    if (!nameInput || !slugInput) return;

    nameInput.addEventListener('blur', function () {
        if (slugInput.value.trim() === '') {
            // simple slugify
            let slug = nameInput.value.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-');
            slugInput.value = slug;
        }
    });
});
</script>
@endpush
@endonce
