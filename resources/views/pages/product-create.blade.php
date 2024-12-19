@extends('layouts.app')
@section('title', 'Add Product')
@section('content')
<div class="product-form-container">
    <h1>Add New Product</h1>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="template_id">Product Template</label><br>
            <!-- Radio button to choose an existing template or add a new one -->
            <input type="radio" name="template_option" id="select_existing" value="existing" checked> Use Existing Template
            <input type="radio" name="template_option" id="add_new" value="new"> Add New Template
        </div>

        <!-- Existing Template Dropdown -->
        <div id="existing-template-container" class="form-group">
            <label for="template_id">Select Product Template</label>
            <select name="template_id" id="template_id" class="form-control" required>
                <option value="">Select Product Template</option>
                @foreach($templates as $template)
                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- New Template Fields (hidden by default) -->
        <div id="new-template-container" class="form-group" style="display:none;">
            <label for="name">Template Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Template Name">

            <label for="developer">Developer</label>
            <input type="text" name="developer" id="developer" class="form-control" placeholder="Enter Developer Name">

            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter Template Description"></textarea>

            <label for="images">Upload Images</label>
            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" name="price" id="price" class="form-control" required min="0" step="0.01">
        </div>

        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" id="stock" class="form-control" required min="0">
        </div>

        <div class="form-group">
            <label>Platforms</label><br>
            @foreach($platforms as $platform)
                <label class="mr-2">
                    <input type="checkbox" name="platforms[]" value="{{ $platform }}">{{ $platform }}
                </label>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>

<script>
// JavaScript to toggle between selecting an existing template or adding a new one
document.addEventListener('DOMContentLoaded', function () {
    const selectExisting = document.getElementById('select_existing');
    const addNew = document.getElementById('add_new');
    const existingTemplateContainer = document.getElementById('existing-template-container');
    const newTemplateContainer = document.getElementById('new-template-container');
    const newTemplateFields = ['name', 'developer', 'description'];

    function toggleFields() {
        if (addNew.checked) {
            existingTemplateContainer.style.display = 'none';
            newTemplateContainer.style.display = 'block';

            // Set required attributes for new template fields
            newTemplateFields.forEach(id => {
                document.getElementById(id).setAttribute('required', 'required');
            });

            // Remove required attribute for existing template dropdown
            document.getElementById('template_id').removeAttribute('required');
        } else {
            existingTemplateContainer.style.display = 'block';
            newTemplateContainer.style.display = 'none';

            // Remove required attributes for new template fields
            newTemplateFields.forEach(id => {
                document.getElementById(id).removeAttribute('required');
            });

            // Set required attribute for existing template dropdown
            document.getElementById('template_id').setAttribute('required', 'required');
        }
    }

    selectExisting.addEventListener('change', toggleFields);
    addNew.addEventListener('change', toggleFields);

    // Initialize the fields based on the selected option
    toggleFields();
});

</script>

@endsection
