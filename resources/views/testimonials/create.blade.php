@extends('layouts.skydash')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Share Your Experience</h3>
                <h6 class="font-weight-normal mb-0">Help others by sharing your learning journey with us</h6>
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
                <form action="{{ route('testimonials.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="course_id" class="form-label">Course (Optional)</label>
                        <select class="form-control @error('course_id') is-invalid @enderror" 
                                id="course_id" name="course_id">
                            <option value="">General Testimonial</option>
                            @foreach($enrolledCourses ?? [] as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
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
                                   {{ old('rating') == $i ? 'checked' : '' }}
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
                                  required>{{ old('testimonial_text') }}</textarea>
                        <small class="form-text text-muted">
                            <span id="char-count">0</span> / 1000 characters (Minimum 20 characters required)
                        </small>
                        @error('testimonial_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="icon-info"></i>
                        <strong>Note:</strong> Your testimonial will be reviewed before being published. This helps us maintain quality and authenticity.
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-check"></i> Submit Testimonial
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

    /* Fallback for browsers that don't support :has() */
    .rating-input input[value="5"]:checked ~ .star-label,
    .rating-input input[value="4"]:checked ~ .star-label,
    .rating-input input[value="3"]:checked ~ .star-label,
    .rating-input input[value="2"]:checked ~ .star-label,
    .rating-input input[value="1"]:checked ~ .star-label {
        color: #ffb606;
    }

    .rating-input input[value="5"]:checked ~ .star-label[data-rating="4"],
    .rating-input input[value="5"]:checked ~ .star-label[data-rating="3"],
    .rating-input input[value="5"]:checked ~ .star-label[data-rating="2"],
    .rating-input input[value="5"]:checked ~ .star-label[data-rating="1"],
    .rating-input input[value="4"]:checked ~ .star-label[data-rating="3"],
    .rating-input input[value="4"]:checked ~ .star-label[data-rating="2"],
    .rating-input input[value="4"]:checked ~ .star-label[data-rating="1"],
    .rating-input input[value="3"]:checked ~ .star-label[data-rating="2"],
    .rating-input input[value="3"]:checked ~ .star-label[data-rating="1"],
    .rating-input input[value="2"]:checked ~ .star-label[data-rating="1"] {
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
        const starLabels = document.querySelectorAll('.star-label');
        starLabels.forEach(label => {
            label.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                document.getElementById('rating_' + rating).checked = true;
                
                // Update all stars
                starLabels.forEach((l, index) => {
                    const lRating = l.getAttribute('data-rating');
                    if (lRating <= rating) {
                        l.querySelector('i').className = 'fas fa-star';
                    } else {
                        l.querySelector('i').className = 'far fa-star';
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

        // Reset stars on mouse leave (unless one is selected)
        document.querySelector('.rating-input').addEventListener('mouseleave', function() {
            const checked = document.querySelector('input[name="rating"]:checked');
            if (checked) {
                const rating = checked.value;
                starLabels.forEach(l => {
                    const lRating = l.getAttribute('data-rating');
                    l.style.color = lRating <= rating ? '#ffb606' : '#ddd';
                    l.querySelector('i').className = lRating <= rating ? 'fas fa-star' : 'far fa-star';
                });
            } else {
                starLabels.forEach(l => {
                    l.style.color = '#ddd';
                    l.querySelector('i').className = 'far fa-star';
                });
            }
        });
    });
</script>
@endpush
@endsection

