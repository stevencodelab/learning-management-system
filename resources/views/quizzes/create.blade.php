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
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('quizzes.store', $lesson) }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="title" class="form-label">Quiz Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" 
                               placeholder="Enter quiz title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Enter quiz description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="passing_score" class="form-label">Passing Score (%) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('passing_score') is-invalid @enderror" 
                                       id="passing_score" name="passing_score" value="{{ old('passing_score', 60) }}" 
                                       min="0" max="100" required>
                                @error('passing_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Minimum score to pass</small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="time_limit_minutes" class="form-label">Time Limit (Minutes)</label>
                                <input type="number" class="form-control @error('time_limit_minutes') is-invalid @enderror" 
                                       id="time_limit_minutes" name="time_limit_minutes" value="{{ old('time_limit_minutes') }}" 
                                       min="1" placeholder="No limit">
                                @error('time_limit_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-check"></i> Create Quiz
                        </button>
                        <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Course Information</h4>
                <hr>
                <p class="mb-2"><strong>Course:</strong></p>
                <p class="text-muted mb-3">{{ $lesson->module->course->title ?? 'N/A' }}</p>
                
                <p class="mb-2"><strong>Module:</strong></p>
                <p class="text-muted mb-3">{{ $lesson->module->title ?? 'N/A' }}</p>
                
                <p class="mb-2"><strong>Lesson:</strong></p>
                <p class="text-muted mb-3">{{ $lesson->title }}</p>
                
                <p class="mb-2"><strong>Lesson Type:</strong></p>
                <p class="text-muted mb-3"><span class="badge badge-info">{{ ucfirst($lesson->type ?? 'N/A') }}</span></p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Quick Tips</h5>
                <ul class="list-unstyled">
                    <li><i class="icon-info text-info"></i> After creating the quiz, you can add questions.</li>
                    <li><i class="icon-info text-info"></i> Set an appropriate passing score based on difficulty.</li>
                    <li><i class="icon-info text-info"></i> Time limits help ensure fair assessment.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

