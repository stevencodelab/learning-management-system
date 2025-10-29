@extends('layouts.skydash')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-1">{{ $quiz->title }}</h3>
                        <span class="badge badge-{{ $quiz->status == 'published' ? 'success' : ($quiz->status == 'draft' ? 'warning' : 'secondary') }} mr-2">
                            {{ ucfirst($quiz->status) }}
                        </span>
                        @if($quiz->isAvailable())
                            <span class="badge badge-success">Available</span>
                        @else
                            <span class="badge badge-secondary">Not Available</span>
                        @endif
                    </div>
                </div>
                <nav aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb" style="background: none; padding: 0; margin: 5px 0;">
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}" class="text-muted">Course</a></li>
                        <li class="breadcrumb-item text-muted">{{ $quiz->lesson->module->course->title ?? 'N/A' }}</li>
                        <li class="breadcrumb-item text-muted">›</li>
                        <li class="breadcrumb-item text-muted">{{ $quiz->lesson->module->title ?? 'N/A' }}</li>
                        <li class="breadcrumb-item text-muted">›</li>
                        <li class="breadcrumb-item text-muted">{{ $quiz->lesson->title ?? 'N/A' }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
                        <a href="{{ route('quiz.questions.index', $quiz) }}" class="btn btn-info mr-2">
                            <i class="icon-question"></i> Manage Questions
                        </a>
                        <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-primary mr-2">
                            <i class="icon-pencil"></i> Edit
                        </a>
                    @endif
                    @if(auth()->check() && !auth()->user()->hasAnyRole(['admin', 'instructor']))
                        @if($quiz->isAvailable() && $quiz->canUserAttempt(auth()->id()))
                            <a href="{{ route('quiz.taking.start', $quiz) }}" class="btn btn-success mr-2">
                                <i class="icon-control-play"></i> Start Quiz
                            </a>
                        @endif
                    @endif
                    <a href="{{ route('quizzes.index') }}" class="btn btn-light">
                        <i class="icon-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert for description -->
@if($quiz->description)
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            <i class="icon-info mr-2"></i>
            <strong>Description:</strong> {{ $quiz->description }}
        </div>
    </div>
</div>
@endif

<!-- Instructions -->
@if($quiz->instructions)
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-light border">
            <i class="icon-note mr-2"></i>
            <strong>Instructions:</strong> {{ $quiz->instructions }}
        </div>
    </div>
</div>
@endif

<div class="row">
    <!-- Left Column -->
    <div class="col-md-8 grid-margin">
        
        <!-- Key Information Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="icon-target" style="font-size: 40px; color: #667eea;"></i>
                        <h3 class="mt-3 mb-0">{{ $quiz->passing_score }}%</h3>
                        <p class="text-muted mb-0">Passing Score</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="icon-clock" style="font-size: 40px; color: #f5576c;"></i>
                        <h3 class="mt-3 mb-0">{{ $quiz->time_limit_minutes ? $quiz->time_limit_minutes . 'm' : '∞' }}</h3>
                        <p class="text-muted mb-0">Time Limit</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="icon-question" style="font-size: 40px; color: #fa709a;"></i>
                        <h3 class="mt-3 mb-0">{{ $quiz->questions->count() }}</h3>
                        <p class="text-muted mb-0">Questions</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="icon-people" style="font-size: 40px; color: #4facfe;"></i>
                        <h3 class="mt-3 mb-0">{{ $quiz->attempts->count() }}</h3>
                        <p class="text-muted mb-0">Attempts</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Settings -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="icon-settings text-primary"></i> Quiz Settings
                </h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="setting-item">
                            <i class="icon-check text-success mr-2"></i>
                            <strong>Multiple Attempts:</strong>
                            <span class="badge badge-{{ $quiz->allow_multiple_attempts ? 'success' : 'secondary' }}">
                                {{ $quiz->allow_multiple_attempts ? 'Yes' : 'No' }}
                            </span>
                            @if($quiz->allow_multiple_attempts && $quiz->max_attempts)
                                <span class="text-muted">(Max: {{ $quiz->max_attempts }})</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="setting-item">
                            <i class="icon-{{ $quiz->shuffle_questions ? 'refresh' : 'pin' }} text-info mr-2"></i>
                            <strong>Shuffle Questions:</strong>
                            <span class="badge badge-{{ $quiz->shuffle_questions ? 'info' : 'secondary' }}">
                                {{ $quiz->shuffle_questions ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="setting-item">
                            <i class="icon-{{ $quiz->shuffle_answers ? 'refresh' : 'check' }} text-warning mr-2"></i>
                            <strong>Shuffle Answers:</strong>
                            <span class="badge badge-{{ $quiz->shuffle_answers ? 'warning' : 'secondary' }}">
                                {{ $quiz->shuffle_answers ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="setting-item">
                            <i class="icon-eye text-primary mr-2"></i>
                            <strong>Show Correct Answers:</strong>
                            <span class="badge badge-{{ $quiz->show_correct_answers ? 'success' : 'secondary' }}">
                                {{ $quiz->show_correct_answers ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="setting-item">
                            <i class="icon-minus text-danger mr-2"></i>
                            <strong>Negative Marking:</strong>
                            <span class="badge badge-{{ $quiz->negative_marking ? 'danger' : 'secondary' }}">
                                {{ $quiz->negative_marking ? 'Yes' : 'No' }}
                            </span>
                            @if($quiz->negative_marking)
                                <span class="text-muted">({{ ($quiz->negative_mark_value ?? 0.25) * 100 }}%)</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="setting-item">
                            <i class="icon-arrow-left-right text-info mr-2"></i>
                            <strong>Allow Navigation:</strong>
                            <span class="badge badge-{{ $quiz->allow_navigation ? 'success' : 'danger' }}">
                                {{ $quiz->allow_navigation ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Information -->
        @if($quiz->start_date || $quiz->end_date)
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="icon-calendar text-info"></i> Schedule
                </h5>
                <div class="row">
                    @if($quiz->start_date)
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Start Date:</strong></p>
                        <p class="text-muted">{{ $quiz->start_date->format('F d, Y H:i') }}</p>
                    </div>
                    @endif
                    
                    @if($quiz->end_date)
                    <div class="col-md-6">
                        <p class="mb-1"><strong>End Date:</strong></p>
                        <p class="text-muted">{{ $quiz->end_date->format('F d, Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        
        <!-- Questions Section -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">
                        <i class="icon-question text-danger"></i> Questions ({{ $quiz->questions->count() }})
                    </h5>
                    <a href="{{ route('quiz.questions.index', $quiz) }}" class="btn btn-sm btn-outline-primary">
                        <i class="icon-plus"></i> Add Question
                    </a>
                </div>
                
                @if($quiz->questions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Question</th>
                                <th>Type</th>
                                <th>Points</th>
                                <th>Difficulty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quiz->questions as $question)
                            <tr>
                                <td><span class="badge badge-primary">{{ $question->order ?? $loop->iteration }}</span></td>
                                <td>
                                    <strong>{{ Str::limit($question->question_text ?? $question->question, 60) }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $question->type ?? 'Multiple Choice')) }}</span>
                                </td>
                                <td><strong>{{ $question->points }}</strong></td>
                                <td>
                                    @if($question->difficulty)
                                        @php
                                            $difficultyColors = [
                                                'easy' => 'success',
                                                'medium' => 'warning',
                                                'hard' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $difficultyColors[$question->difficulty] ?? 'secondary' }}">
                                            {{ ucfirst($question->difficulty) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="icon-circle-lg bg-light d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="icon-question text-muted" style="font-size: 48px;"></i>
                    </div>
                    <h5 class="text-muted">No questions yet</h5>
                    <p class="text-muted mb-3">Add questions to get started</p>
                    <a href="{{ route('quiz.questions.create', $quiz) }}" class="btn btn-primary">
                        <i class="icon-plus"></i> Add First Question
                    </a>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Recent Attempts -->
        @if($quiz->attempts->count() > 0)
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="icon-people text-success"></i> Recent Attempts ({{ $quiz->attempts->count() }})
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Attempt</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quiz->attempts->take(10) as $attempt)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle-sm bg-gradient-primary text-white mr-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                                            {{ strtoupper(substr($attempt->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span>{{ $attempt->user->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">#{{ $attempt->attempt_number ?? 1 }}</span>
                                </td>
                                <td>
                                    <strong>{{ $attempt->score ?? 0 }} / {{ $attempt->total_points ?? $quiz->questions->sum('points') }}</strong>
                                    @if($attempt->total_points > 0)
                                        <span class="text-muted">({{ round(($attempt->score / $attempt->total_points) * 100) }}%)</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attempt->is_passed)
                                        <span class="badge badge-success">Passed</span>
                                    @elseif($attempt->submitted)
                                        <span class="badge badge-danger">Failed</span>
                                    @else
                                        <span class="badge badge-warning">In Progress</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $attempt->created_at->format('M d, Y') }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Right Sidebar -->
    <div class="col-md-4 grid-margin">
        <!-- Course Info Card -->
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="icon-book text-primary"></i> Course Context
                </h5>
                <hr>
                <div class="mb-3">
                    <p class="mb-1 text-muted"><i class="icon-graduation mr-2 text-primary"></i><strong>Course:</strong></p>
                    <p class="mb-0">{{ $quiz->lesson->module->course->title ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <p class="mb-1 text-muted"><i class="icon-folder mr-2 text-info"></i><strong>Module:</strong></p>
                    <p class="mb-0">{{ $quiz->lesson->module->title ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <p class="mb-1 text-muted"><i class="icon-note mr-2 text-warning"></i><strong>Lesson:</strong></p>
                    <p class="mb-0">{{ $quiz->lesson->title ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Statistics Card -->
        @if($quiz->attempts->count() > 0)
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="icon-chart text-info"></i> Statistics
                </h5>
                <hr>
                @php
                    $completed = $quiz->attempts->where('submitted', true)->count();
                    $passed = $quiz->attempts->where('is_passed', true)->count();
                    $totalQuestions = $quiz->questions->sum('points');
                    $avgScore = $quiz->attempts->where('submitted', true)->avg('score') ?? 0;
                    $passRate = $completed > 0 ? ($passed / $completed) * 100 : 0;
                @endphp
                
                <div class="stat-item">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="icon-question mr-2"></i>Total Points</span>
                        <strong class="text-primary">{{ $totalQuestions }}</strong>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="icon-people mr-2"></i>Completed</span>
                        <strong class="text-success">{{ $completed }}</strong>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="icon-target mr-2"></i>Avg Score</span>
                        <strong class="text-warning">{{ number_format($avgScore, 1) }}</strong>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="icon-check mr-2"></i>Pass Rate</span>
                        <strong class="text-success">{{ number_format($passRate, 1) }}%</strong>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Actions Card - Admin/Instructor Only -->
        @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="icon-settings"></i> Quick Actions
                </h5>
                <div class="d-flex flex-column">
                    <a href="{{ route('quiz.questions.index', $quiz) }}" class="btn btn-info mb-2">
                        <i class="icon-question"></i> Manage Questions ({{ $quiz->questions->count() }})
                    </a>
                    <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-primary mb-2">
                        <i class="icon-pencil"></i> Edit Settings
                    </a>
                    <a href="{{ route('quizzes.index') }}" class="btn btn-light mb-2">
                        <i class="icon-arrow-left"></i> Back to List
                    </a>
                    <hr>
                    <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this quiz? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="icon-trash"></i> Delete Quiz
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Student Actions -->
        @if(auth()->check() && !auth()->user()->hasAnyRole(['admin', 'instructor']))
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="icon-control-play"></i> Take Quiz
                </h5>
                
                @if($quiz->isAvailable())
                    @if($quiz->canUserAttempt(auth()->id()))
                        <a href="{{ route('quiz.taking.start', $quiz) }}" class="btn btn-success btn-block btn-lg">
                            <i class="icon-play mr-2"></i> Start Quiz
                        </a>
                        
                        @if($userAttempts && $userAttempts->count() > 0)
                            <hr>
                            <h6 class="mb-3">Your Attempts ({{ $userAttempts->count() }})</h6>
                            @foreach($userAttempts->take(5) as $attempt)
                                <div class="attempt-item mb-2 p-2 border rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">Attempt #{{ $attempt->attempt_number }}</small>
                                            <div>
                                                <strong>{{ $attempt->score ?? 0 }}/{{ $attempt->total_points ?? $quiz->questions->sum('points') }}</strong>
                                                @if($attempt->total_points > 0)
                                                    ({{ round(($attempt->score / $attempt->total_points) * 100) }}%)
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            @if($attempt->is_passed)
                                                <span class="badge badge-success">Passed</span>
                                            @elseif($attempt->submitted)
                                                <span class="badge badge-danger">Failed</span>
                                            @else
                                                <a href="{{ route('quiz.taking.show', ['quiz' => $quiz, 'attempt' => $attempt]) }}" class="btn btn-sm btn-primary">
                                                    Continue
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    @if($attempt->completed_at)
                                        <small class="text-muted">{{ $attempt->completed_at->format('M d, Y') }}</small>
                                    @endif
                                </div>
                            @endforeach
                            
                            @if($quiz->allow_multiple_attempts && (!$quiz->max_attempts || $userAttempts->count() < $quiz->max_attempts))
                                <p class="text-muted small mt-2">
                                    You can take this quiz {{ $quiz->max_attempts ? ($quiz->max_attempts - $userAttempts->count()) . ' more times' : 'multiple times' }}
                                </p>
                            @endif
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="icon-info mr-2"></i>
                            @if(!$quiz->allow_multiple_attempts)
                                You have already attempted this quiz.
                            @elseif($quiz->max_attempts)
                                You have reached the maximum number of attempts ({{ $quiz->max_attempts }}).
                            @endif
                        </div>
                        
                        @if($bestAttempt)
                            <div class="text-center">
                                <p class="text-muted mb-2">Best Score</p>
                                <h3 class="text-primary">{{ $bestAttempt->score }}/{{ $bestAttempt->total_points }}</h3>
                                <p class="text-muted">({{ round(($bestAttempt->score / $bestAttempt->total_points) * 100) }}%)</p>
                            </div>
                        @endif
                    @endif
                @else
                    <div class="alert alert-info">
                        <i class="icon-info mr-2"></i>
                        @if($quiz->status !== 'published')
                            This quiz is not published yet.
                        @elseif($quiz->start_date && now() < $quiz->start_date)
                            This quiz will be available starting {{ $quiz->start_date->format('M d, Y') }}.
                        @elseif($quiz->end_date && now() > $quiz->end_date)
                            This quiz has ended.
                        @endif
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Status Info -->
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="mb-3"><i class="icon-info"></i> Status Information</h6>
                <p class="mb-2"><small class="text-muted">Status:</small><br>
                   <span class="badge badge-{{ $quiz->status == 'published' ? 'success' : ($quiz->status == 'draft' ? 'warning' : 'secondary') }}">
                       {{ ucfirst($quiz->status) }}
                   </span>
                </p>
                <p class="mb-2"><small class="text-muted">Created:</small><br>
                   <small>{{ $quiz->created_at->format('M d, Y') }}</small>
                </p>
                <p class="mb-0"><small class="text-muted">Last Updated:</small><br>
                   <small>{{ $quiz->updated_at->format('M d, Y') }}</small>
                </p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .icon-circle-lg {
        width: 120px;
        height: 120px;
        border-radius: 50%;
    }
    
    .stats-card {
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        height: 100%;
    }
    
    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .setting-item {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .setting-item:last-child {
        border-bottom: none;
    }
    
    .stat-item {
        padding: 10px 0;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    @media (max-width: 991px) {
        .sticky-top {
            position: relative !important;
        }
    }
</style>
@endpush
@endsection
