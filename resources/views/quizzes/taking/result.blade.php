@extends('layouts.skydash')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12">
                <h3 class="font-weight-bold">Quiz Results</h3>
                <h6 class="font-weight-normal mb-0">{{ $quiz->title }}</h6>
            </div>
        </div>
    </div>
</div>

<!-- Result Summary -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card" style="background: linear-gradient(135deg, {{ $attempt->is_passed ? '#10b981' : '#ef4444' }} 0%, {{ $attempt->is_passed ? '#059669' : '#dc2626' }} 100%); color: white; border: none;">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="icon-{{ $attempt->is_passed ? 'check-circle' : 'close-circle' }}" style="font-size: 80px; opacity: 0.9;"></i>
                </div>
                <h2 class="mb-3">{{ $attempt->is_passed ? 'Congratulations!' : 'Quiz Completed' }}</h2>
                <div class="result-score mb-4">
                    <h1 class="mb-0" style="font-size: 4rem; font-weight: 700;">
                        {{ $attempt->score }} / {{ $attempt->total_points }}
                    </h1>
                    <h3 class="mb-0">{{ number_format($attempt->score_percentage, 1) }}%</h3>
                </div>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <p class="mb-1" style="opacity: 0.9;">Passing Score</p>
                        <h4>{{ $quiz->passing_score }}%</h4>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1" style="opacity: 0.9;">Your Score</p>
                        <h4>{{ number_format($attempt->score_percentage, 1) }}%</h4>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1" style="opacity: 0.9;">Status</p>
                        <h4>
                            @if($attempt->is_passed)
                                <span style="background: rgba(255,255,255,0.3); padding: 5px 15px; border-radius: 20px;">PASSED</span>
                            @else
                                <span style="background: rgba(255,255,255,0.3); padding: 5px 15px; border-radius: 20px;">FAILED</span>
                            @endif
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Results -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="icon-chart text-primary"></i> Detailed Results
                </h4>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <i class="icon-check text-success" style="font-size: 32px;"></i>
                            <h3 class="mt-2 mb-0">{{ $attempt->correct_answers ?? 0 }}</h3>
                            <p class="text-muted mb-0">Correct</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <i class="icon-close text-danger" style="font-size: 32px;"></i>
                            <h3 class="mt-2 mb-0">{{ $attempt->incorrect_answers ?? 0 }}</h3>
                            <p class="text-muted mb-0">Incorrect</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <i class="icon-question text-warning" style="font-size: 32px;"></i>
                            <h3 class="mt-2 mb-0">{{ $attempt->unanswered_questions ?? 0 }}</h3>
                            <p class="text-muted mb-0">Unanswered</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <i class="icon-clock text-info" style="font-size: 32px;"></i>
                            <h3 class="mt-2 mb-0">{{ $attempt->formatted_duration ?? '--' }}</h3>
                            <p class="text-muted mb-0">Duration</p>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Questions Review -->
                @if($quiz->show_correct_answers && isset($attempt->answers_review))
                    <h5 class="mb-4">Question Review</h5>
                    @php
                        $review = is_array($attempt->answers_review) ? $attempt->answers_review : json_decode($attempt->answers_review, true) ?? [];
                        $questions = $quiz->questions;
                    @endphp
                    
                    @foreach($questions as $index => $question)
                        @php
                            $qReview = $review[$question->id] ?? null;
                            $isCorrect = $qReview['is_correct'] ?? false;
                        @endphp
                        <div class="question-review-card mb-4 border rounded p-4 {{ $isCorrect ? 'border-success' : 'border-danger' }}">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge badge-{{ $isCorrect ? 'success' : 'danger' }} mb-2">
                                        Question {{ $index + 1 }} - {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                                    </span>
                                    <span class="badge badge-info">{{ $question->points }} Points</span>
                                </div>
                                <div>
                                    @if($isCorrect)
                                        <i class="icon-check-circle text-success" style="font-size: 32px;"></i>
                                    @else
                                        <i class="icon-close-circle text-danger" style="font-size: 32px;"></i>
                                    @endif
                                </div>
                            </div>
                            
                            <h6 class="font-weight-bold mb-3">{{ $question->question_text ?? $question->question }}</h6>
                            
                            <div class="answers-review">
                                @foreach($question->answers as $answer)
                                    @php
                                        $userSelected = $qReview && in_array($answer->id, ($qReview['user_answer'] ?? []));
                                        $isCorrectAnswer = $answer->is_correct;
                                    @endphp
                                    <div class="answer-review-item mb-2 p-2 rounded {{ $isCorrectAnswer ? 'bg-success' : ($userSelected ? 'bg-danger' : 'bg-light') }}" style="opacity: 0.7;">
                                        <div class="d-flex align-items-center">
                                            @if($isCorrectAnswer)
                                                <i class="icon-check text-white mr-2"></i>
                                                <span class="text-white font-weight-bold">{{ $answer->answer_text }}</span>
                                                <span class="badge badge-light ml-auto">Correct Answer</span>
                                            @elseif($userSelected)
                                                <i class="icon-close text-white mr-2"></i>
                                                <span class="text-white font-weight-bold">{{ $answer->answer_text }}</span>
                                                <span class="badge badge-light ml-auto">Your Answer</span>
                                            @else
                                                <span class="text-muted">{{ $answer->answer_text }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($question->explanation)
                                <div class="alert alert-info mt-3 mb-0">
                                    <i class="icon-info mr-2"></i>
                                    <strong>Explanation:</strong> {{ $question->explanation }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif

                <hr>

                <div class="text-center mt-4">
                    <a href="{{ $quiz->url }}" class="btn btn-primary mr-2">
                        <i class="icon-arrow-left mr-2"></i> Back to Quiz
                    </a>
                    @if($quiz->canUserAttempt(auth()->id()))
                        <a href="{{ route('quiz.taking.start', $quiz) }}" class="btn btn-success">
                            <i class="icon-refresh mr-2"></i> Try Again
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .result-score h1 {
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .question-review-card {
        transition: all 0.3s ease;
    }
    
    .question-review-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
@endpush
@endsection

