@extends('layouts.skydash')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Edit Course</h3>
                <h6 class="font-weight-normal mb-0">Update course information</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                        <i class="icon-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title" class="form-label">Course Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $course->title) }}" 
                                       placeholder="Enter course title" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="6" 
                                          placeholder="Enter course description" required>{{ old('description', $course->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="level" class="form-label">Level <span class="text-danger">*</span></label>
                                <select class="form-control @error('level') is-invalid @enderror" 
                                        id="level" name="level" required>
                                    <option value="">Select Level</option>
                                    <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $course->price) }}" 
                                       placeholder="0.00" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="duration_hours" class="form-label">Duration (Hours) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration_hours') is-invalid @enderror" 
                                       id="duration_hours" name="duration_hours" value="{{ old('duration_hours', $course->duration_hours) }}" 
                                       placeholder="e.g. 10" min="0" required>
                                @error('duration_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check form-check-primary">
                                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" {{ old('is_published', $course->is_published) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">
                                        Publish course
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="thumbnail" class="form-label">Course Thumbnail</label>
                                @if($course->thumbnail)
                                <div class="mb-3">
                                    <img src="{{ Storage::url($course->thumbnail) }}" alt="Current thumbnail" class="img-thumbnail" style="max-width: 300px;">
                                    <p class="text-muted mt-2">Current thumbnail</p>
                                </div>
                                @endif
                                <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                                       id="thumbnail" name="thumbnail" accept="image/*" onchange="previewImage(this)">
                                <small class="form-text text-muted">Leave empty to keep current thumbnail. Max 2MB. Recommended: 400x300px</small>
                                @error('thumbnail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="preview" class="mt-3" style="display: none;">
                                    <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-check"></i> Update Course
                            </button>
                            <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const previewImg = document.getElementById('preview-img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>
@endpush
@endsection
