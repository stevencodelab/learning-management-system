@extends('layouts.skydash')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Edit Your Testimonial</h3>
                <h6 class="font-weight-normal mb-0">Update your testimonial</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('testimonials.index') }}" class="btn btn-secondary">
                        <i class="icon-arrow-left"></i> Back to Testimonials
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
                <form action="{{ route('testimonials.update', $testimonial) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="course_id" class="form-label">Course (Optional)</label>
                        <select class="form-control @error('course_id') is-invalid @enderror" 
                                id="course_id" name="course_id">
                            <option value="">General Testimonial</option>
                            @foreach($enrolledCourses ?? [] as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $testimonial->course_id) == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                                @if($course->instructor)
                                    - by {{ $course->instructor->name }}
                                @endif
                            </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Select a course if this testimonial is about a specific course you've taken.</small>
                    </div>

                    <div class="form-group">
                        <label for="rating" class="form-label">Rating (Optional)</label>
                        <div class="rating-input">
                            @for($i = 5; $i >= 1; $i--)
                            <input type="radio" 
                                   name="rating" 
                                   id="rating_{{ $i }}" 
                                   value="{{ $i }}" 
                                   {{ old('rating', $testimonial->rating) == $i ? 'checked' : '' }}
                                   style="display: none;">
                            <label for="rating_{{ $i }}" 
                                   class="star-label" 
                                   data-rating="{{ $i }}">
                                <i class="far fa-star"></i>
                            </label>
                            @endfor
                        </div>
                        @error('rating')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Rate your overall experience (1-5 stars).</small>
                    </div>

                    <div class="form-group">
                        <label for="testimonial_text" class="form-label">Your Testimonial <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('testimonial_text') is-invalid @enderror" 
                                  id="testimonial_text" 
                                  name="testimonial_text" 
                                  rows="8" 
                                  placeholder="Share your experience, what you learned, how it helped you, etc. (Minimum 20 characters)"
                                  required>{{ old('testimonial_text', $testimonial->testimonial_text) }}</textarea>
                        <small class="form-text text-muted">
                            <span id="char-count">0</span> / 1000 characters (Minimum 20 characters required)
                        </small>
                        @error('testimonial_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="icon-info"></i>
                        <strong>Note:</strong> Your testimonial will be reviewed again before being published after editing.
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-check"></i> Update Testimonial
                        </button>
                        <a href="{{ route('testimonials.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
    }

    .star-label {
        font-size: 28px;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }

    .star-label:hover,
    .star-label:hover ~ .star-label {
        color: #ffb606;
    }

    .rating-input input:checked ~ .star-label,
    .rating-input input:checked ~ .star-label ~ .star-label {
        color: #ffb606;
    }

    .rating-input input:checked + .star-label {
        color: #ffb606;
    }

    .rating-input:has(input:checked) .star-label {
        color: #ffb606;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Character counter
        const textarea = document.getElementById('testimonial_text');
        const charCount = document.getElementById('char-count');
        
        function updateCharCount() {
            const length = textarea.value.length;
            charCount.textContent = length;
            
            if (length < 20) {
                charCount.style.color = '#dc3545';
            } else if (length > 1000) {
                charCount.style.color = '#dc3545';
            } else {
                charCount.style.color = '#28a745';
            }
        }
        
        textarea.addEventListener('input', updateCharCount);
        updateCharCount();

        // Star rating interaction
        const checkedRating = document.querySelector('input[name="rating"]:checked');
        if (checkedRating) {
            const rating = checkedRating.value;
            const starLabels = document.querySelectorAll('.star-label');
            starLabels.forEach(l => {
                const lRating = l.getAttribute('data-rating');
                if (lRating <= rating) {
                    l.querySelector('i').className = 'fas fa-star';
                    l.style.color = '#ffb606';
                }
            });
        }

        const starLabels = document.querySelectorAll('.star-label');
        starLabels.forEach(label => {
            label.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                document.getElementById('rating_' + rating).checked = true;
                
                starLabels.forEach((l, index) => {
                    const lRating = l.getAttribute('data-rating');
                    if (lRating <= rating) {
                        l.querySelector('i').className = 'fas fa-star';
                        l.style.color = '#ffb606';
                    } else {
                        l.querySelector('i').className = 'far fa-star';
                        l.style.color = '#ddd';
                    }
                });
            });
            
            label.addEventListener('mouseenter', function() {
                const rating = this.getAttribute('data-rating');
                starLabels.forEach(l => {
                    const lRating = l.getAttribute('data-rating');
                    if (lRating <= rating) {
                        l.style.color = '#ffb606';
                    }
                });
            });
        });

        document.querySelector('.rating-input').addEventListener('mouseleave', function() {
            const checked = document.querySelector('input[name="rating"]:checked');
            if (checked) {
                const rating = checked.value;
                starLabels.forEach(l => {
                    const lRating = l.getAttribute('data-rating');
                    l.style.color = lRating <= rating ? '#ffb606' : '#ddd';
                    l.querySelector('i').className = lRating <= rating ? 'fas fa-star' : 'far fa-star';
                });
            }
        });
    });
</script>
@endpush
@endsection

