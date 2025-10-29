@extends('layouts.skydash')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Edit Quiz</h3>
                <h6 class="font-weight-normal mb-0">Update quiz information</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ $quiz->url }}" class="btn btn-secondary">
                        <i class="icon-arrow-left"></i> Back
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
                <form action="{{ route('quizzes.update', $quiz) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <h5 class="mb-4"><i class="icon-note text-primary"></i> Basic Information</h5>
                    
                    <div class="form-group">
                        <label for="title" class="form-label">Quiz Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $quiz->title) }}" 
                               placeholder="Enter quiz title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Enter quiz description (optional)">{{ old('description', $quiz->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="instructions" class="form-label">Instructions</label>
                        <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                  id="instructions" name="instructions" rows="3" 
                                  placeholder="Instructions for students taking this quiz">{{ old('instructions', $quiz->instructions) }}</textarea>
                        @error('instructions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr class="my-4">

                    <!-- Assessment Settings -->
                    <h5 class="mb-4"><i class="icon-target text-success"></i> Assessment Settings</h5>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="passing_score" class="form-label">Passing Score (%) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('passing_score') is-invalid @enderror" 
                                       id="passing_score" name="passing_score" value="{{ old('passing_score', $quiz->passing_score) }}" 
                                       min="0" max="100" required>
                                @error('passing_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="time_limit_minutes" class="form-label">Time Limit (Minutes)</label>
                                <input type="number" class="form-control @error('time_limit_minutes') is-invalid @enderror" 
                                       id="time_limit_minutes" name="time_limit_minutes" value="{{ old('time_limit_minutes', $quiz->time_limit_minutes) }}" 
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
                                    <option value="draft" {{ old('status', $quiz->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $quiz->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status', $quiz->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" 
                                       value="{{ old('start_date', $quiz->start_date ? $quiz->start_date->format('Y-m-d\TH:i') : '') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" 
                                       value="{{ old('end_date', $quiz->end_date ? $quiz->end_date->format('Y-m-d\TH:i') : '') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Attempts Settings -->
                    <h5 class="mb-4"><i class="icon-refresh text-info"></i> Attempts Settings</h5>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="allow_multiple_attempts" 
                                       id="allow_multiple_attempts" value="1" 
                                       {{ old('allow_multiple_attempts', $quiz->allow_multiple_attempts ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="allow_multiple_attempts">
                                    <strong>Allow Multiple Attempts</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="max_attempts_container" style="{{ old('allow_multiple_attempts', $quiz->allow_multiple_attempts ?? true) ? '' : 'display:none;' }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="max_attempts" class="form-label">Maximum Attempts</label>
                                <input type="number" class="form-control @error('max_attempts') is-invalid @enderror" 
                                       id="max_attempts" name="max_attempts" 
                                       value="{{ old('max_attempts', $quiz->max_attempts) }}" 
                                       min="1" placeholder="Unlimited">
                                @error('max_attempts')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty for unlimited attempts</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Quiz Display Settings -->
                    <h5 class="mb-4"><i class="icon-screen-desktop text-warning"></i> Display Settings</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="questions_per_page" class="form-label">Questions Per Page</label>
                                <input type="number" class="form-control @error('questions_per_page') is-invalid @enderror" 
                                       id="questions_per_page" name="questions_per_page" 
                                       value="{{ old('questions_per_page', $quiz->questions_per_page ?? 10) }}" 
                                       min="1">
                                @error('questions_per_page')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="random_question_count" class="form-label">Random Questions Count</label>
                                <input type="number" class="form-control @error('random_question_count') is-invalid @enderror" 
                                       id="random_question_count" name="random_question_count" 
                                       value="{{ old('random_question_count', $quiz->random_question_count) }}" 
                                       min="1" placeholder="Use all questions">
                                @error('random_question_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Select this many random questions per attempt</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="shuffle_questions" 
                                       id="shuffle_questions" value="1" 
                                       {{ old('shuffle_questions', $quiz->shuffle_questions ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="shuffle_questions">
                                    <strong>Shuffle Questions</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="shuffle_answers" 
                                       id="shuffle_answers" value="1" 
                                       {{ old('shuffle_answers', $quiz->shuffle_answers ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="shuffle_answers">
                                    <strong>Shuffle Answer Options</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="allow_navigation" 
                                       id="allow_navigation" value="1" 
                                       {{ old('allow_navigation', $quiz->allow_navigation ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="allow_navigation">
                                    <strong>Allow Navigation Between Questions</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Results Settings -->
                    <h5 class="mb-4"><i class="icon-chart text-danger"></i> Results Settings</h5>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="show_results_immediately" 
                                       id="show_results_immediately" value="1" 
                                       {{ old('show_results_immediately', $quiz->show_results_immediately ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_results_immediately">
                                    <strong>Show Results Immediately</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="show_correct_answers" 
                                       id="show_correct_answers" value="1" 
                                       {{ old('show_correct_answers', $quiz->show_correct_answers ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_correct_answers">
                                    <strong>Show Correct Answers</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Negative Marking -->
                    <h5 class="mb-4"><i class="icon-minus text-secondary"></i> Negative Marking</h5>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="negative_marking" 
                                       id="negative_marking" value="1" 
                                       {{ old('negative_marking', $quiz->negative_marking ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="negative_marking">
                                    <strong>Enable Negative Marking</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="negative_mark_container" style="{{ old('negative_marking', $quiz->negative_marking ?? false) ? '' : 'display:none;' }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="negative_mark_value" class="form-label">Negative Mark Value</label>
                                <input type="number" class="form-control @error('negative_mark_value') is-invalid @enderror" 
                                       id="negative_mark_value" name="negative_mark_value" 
                                       value="{{ old('negative_mark_value', $quiz->negative_mark_value ?? 0.25) }}" 
                                       step="0.01" min="0" max="1">
                                @error('negative_mark_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Fraction of points to deduct (e.g., 0.25 = 25%)</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Messages -->
                    <h5 class="mb-4"><i class="icon-bubble text-info"></i> Custom Messages</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pass_message" class="form-label">Pass Message</label>
                                <textarea class="form-control" id="pass_message" name="pass_message" 
                                          rows="2" placeholder="Congratulations! You passed.">{{ old('pass_message', $quiz->pass_message) }}</textarea>
                                <small class="form-text text-muted">Message shown when student passes</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fail_message" class="form-label">Fail Message</label>
                                <textarea class="form-control" id="fail_message" name="fail_message" 
                                          rows="2" placeholder="Unfortunately, you did not pass. Try again!">{{ old('fail_message', $quiz->fail_message) }}</textarea>
                                <small class="form-text text-muted">Message shown when student fails</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-check"></i> Update Quiz
                        </button>
                        <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-secondary ml-2">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
            </div>
        </div>
        
@push('scripts')
<script>
document.getElementById('allow_multiple_attempts').addEventListener('change', function() {
    document.getElementById('max_attempts_container').style.display = this.checked ? '' : 'none';
});

document.getElementById('negative_marking').addEventListener('change', function() {
    document.getElementById('negative_mark_container').style.display = this.checked ? '' : 'none';
});
</script>
@endpush
@endsection
