@extends('layouts.skydash')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Create New Quiz</h3>
                <h6 class="font-weight-normal mb-0">Create a new quiz for: {{ $lesson->title }}</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">
                        <i class="icon-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form id="quizForm" action="{{ route('quizzes.store', $lesson) }}" method="POST" onsubmit="event.preventDefault(); handleFormSubmit(this);">
                    @csrf
                    
                    <!-- Basic Information Section -->
                    <h5 class="mb-3">
                        <i class="icon-info text-primary mr-2"></i> Basic Information
                    </h5>
                    
                    <div class="form-group mb-3">
                        <label for="title" class="form-label font-weight-bold">Quiz Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" 
                               placeholder="Enter quiz title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="description" class="form-label font-weight-bold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Enter quiz description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Provide a brief description of what this quiz covers</small>
                    </div>
                    
                    <hr class="my-3">
                    
                    <!-- Quiz Settings Section -->
                    <h5 class="mb-3">
                        <i class="icon-settings text-primary mr-2"></i> Quiz Settings
                    </h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="passing_score" class="form-label font-weight-bold">Passing Score (%) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('passing_score') is-invalid @enderror" 
                                       id="passing_score" name="passing_score" value="{{ old('passing_score', 60) }}" 
                                       min="0" max="100" required>
                                @error('passing_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Minimum score required to pass (0-100)</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="time_limit_minutes" class="form-label font-weight-bold">Time Limit (Minutes)</label>
                                <input type="number" class="form-control @error('time_limit_minutes') is-invalid @enderror" 
                                       id="time_limit_minutes" name="time_limit_minutes" value="{{ old('time_limit_minutes') }}" 
                                       min="1" placeholder="Leave empty for no limit">
                                @error('time_limit_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty if there's no time limit</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label font-weight-bold">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Draft quizzes are not visible to students</small>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden default values -->
                    <input type="hidden" name="allow_multiple_attempts" value="1">
                    <input type="hidden" name="show_results_immediately" value="1">
                    <input type="hidden" name="allow_navigation" value="1">
                    <input type="hidden" name="shuffle_questions" value="0">
                    <input type="hidden" name="shuffle_answers" value="0">
                    <input type="hidden" name="show_correct_answers" value="0">
                    <input type="hidden" name="negative_marking" value="0">
                    
                    <hr class="my-3">
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-start align-items-center mt-3">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="icon-check mr-2"></i> Create Quiz
                        </button>
                        <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">
                            <i class="icon-close mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-12 grid-margin stretch-card">
        <!-- Course Information Card -->
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="icon-book text-primary mr-2"></i> Course Information
                </h5>
                
                <div class="mb-3">
                    <label class="text-muted mb-1 d-block" style="font-size: 0.875rem;">Course</label>
                    <p class="mb-0 font-weight-bold" style="word-break: break-word;">{{ $lesson->module->course->title ?? 'N/A' }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted mb-1 d-block" style="font-size: 0.875rem;">Module</label>
                    <p class="mb-0 font-weight-bold" style="word-break: break-word;">{{ $lesson->module->title ?? 'N/A' }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted mb-1 d-block" style="font-size: 0.875rem;">Lesson</label>
                    <p class="mb-0 font-weight-bold" style="word-break: break-word;">{{ $lesson->title }}</p>
                </div>
                
                <div class="mb-0">
                    <label class="text-muted mb-1 d-block" style="font-size: 0.875rem;">Lesson Type</label>
                    <span class="badge badge-info">{{ ucfirst($lesson->type ?? 'N/A') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Quick Tips Card -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="icon-lightbulb text-warning mr-2"></i> Quick Tips
                </h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="icon-check text-success mr-2"></i>
                        <small>Add questions after creating the quiz.</small>
                    </li>
                    <li class="mb-2">
                        <i class="icon-check text-success mr-2"></i>
                        <small>Set appropriate passing score.</small>
                    </li>
                    <li class="mb-2">
                        <i class="icon-check text-success mr-2"></i>
                        <small>Time limits ensure fair assessment.</small>
                    </li>
                    <li>
                        <i class="icon-check text-success mr-2"></i>
                        <small>Save as draft before publishing.</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function handleFormSubmit(form) {
    Swal.fire({
        title: "Do you want to save the changes?",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Save",
        denyButtonText: `Don't save`
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        } else if (result.isDenied) {
            Swal.fire("Changes are not saved", "", "info");
        }
    });
}
</script>
@endpush
@endsection

