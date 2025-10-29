@extends('layouts.skydash')

@section('content')
<div class="quiz-taking-container">
    <!-- Quiz Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card quiz-header-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">{{ $quiz->title }}</h3>
                            <p class="text-muted mb-0">{{ $quiz->description ?? '' }}</p>
                        </div>
                        <div class="text-right">
                            @if($quiz->time_limit_minutes)
                                <div id="timer" class="timer-display">
                                    <i class="icon-clock mr-2"></i>
                                    <span id="time-remaining">--:--</span>
                                </div>
                            @endif
                            <div class="mt-2">
                                <span class="badge badge-info">Question <span id="current-question">1</span> of {{ $questions->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="progress-quiz">
                <div class="progress" style="height: 8px; border-radius: 10px;">
                    <div id="progress-bar" class="progress-bar bg-gradient-primary" role="progressbar" style="width: 0%"></div>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <small class="text-muted">Answered: <span id="answered-count">0</span></small>
                    <small class="text-muted">Total: {{ $questions->count() }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Navigation (if enabled) -->
    @if($quiz->allow_navigation && $questions->count() > 1)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="question-navigation">
                        @foreach($questions as $index => $q)
                            <button type="button" 
                                    class="question-nav-btn" 
                                    data-question="{{ $index + 1 }}"
                                    data-question-id="{{ $q->id }}"
                                    title="Question {{ $index + 1 }}">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quiz Form -->
    <form id="quiz-form" action="{{ route('quiz.taking.submit', ['quiz' => $quiz, 'attempt' => $attempt]) }}" method="POST">
        @csrf
        
        <div class="questions-container">
            @foreach($questions as $index => $question)
            <div class="question-card card mb-4 {{ $index === 0 ? 'active' : 'd-none' }}" data-question-index="{{ $index + 1 }}" data-question-id="{{ $question->id }}">
                <div class="card-body">
                    <div class="question-header mb-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge badge-primary mb-2">Question {{ $index + 1 }}</span>
                                <span class="badge badge-warning">{{ $question->points }} Points</span>
                                @if($question->difficulty)
                                    <span class="badge badge-{{ $question->difficulty == 'easy' ? 'success' : ($question->difficulty == 'medium' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($question->difficulty) }}
                                    </span>
                                @endif
                            </div>
                            <div class="question-status">
                                <i class="icon-check text-success d-none saved-indicator"></i>
                            </div>
                        </div>
                    </div>

                    <div class="question-content mb-4">
                        <h5 class="font-weight-bold">{{ $question->question_text ?? $question->question }}</h5>
                    </div>

                    <div class="answers-container">
                        @php
                            $shuffledAnswers = $quiz->shuffle_answers ? $question->answers->shuffle() : $question->answers;
                            $userAnswerIds = isset($userAnswers[$question->id]) ? $userAnswers[$question->id] : [];
                            if (!is_array($userAnswerIds)) {
                                $userAnswerIds = [$userAnswerIds];
                            }
                        @endphp

                        @foreach($shuffledAnswers as $answer)
                        <div class="answer-option mb-3">
                            <div class="custom-control custom-{{ $question->type == 'multiple_choice' ? 'radio' : 'checkbox' }}">
                                <input type="{{ $question->type == 'multiple_choice' || $question->type == 'true_false' ? 'radio' : 'checkbox' }}"
                                       class="custom-control-input answer-input"
                                       name="answers[{{ $question->id }}][]"
                                       id="answer_{{ $answer->id }}"
                                       value="{{ $answer->id }}"
                                       data-question-id="{{ $question->id }}"
                                       {{ in_array($answer->id, $userAnswerIds) ? 'checked' : '' }}>
                                <label class="custom-control-label answer-label" for="answer_{{ $answer->id }}">
                                    {{ $answer->answer_text }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($question->explanation && $quiz->show_correct_answers)
                        <div class="alert alert-info mt-3 explanation-box">
                            <i class="icon-info mr-2"></i>
                            <strong>Explanation:</strong> {{ $question->explanation }}
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Navigation Buttons -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" id="prev-btn" class="btn btn-outline-primary" style="display: none;">
                                <i class="icon-arrow-left mr-2"></i> Previous
                            </button>
                            <div class="text-center">
                                <span class="text-muted" id="question-counter">Question 1 of {{ $questions->count() }}</span>
                            </div>
                            <div>
                                @if($questions->count() > 1)
                                    <button type="button" id="next-btn" class="btn btn-primary">
                                        Next <i class="icon-arrow-right ml-2"></i>
                                    </button>
                                @endif
                                <button type="button" id="submit-btn" class="btn btn-success ml-2" style="display: none;">
                                    <i class="icon-check mr-2"></i> Submit Quiz
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .quiz-taking-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .quiz-header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }

    .quiz-header-card h3,
    .quiz-header-card p {
        color: white !important;
    }

    .timer-display {
        font-size: 24px;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.2);
        padding: 10px 20px;
        border-radius: 8px;
        display: inline-block;
    }

    .timer-display.warning {
        background: rgba(255, 193, 7, 0.3);
        animation: pulse 1s infinite;
    }

    .timer-display.danger {
        background: rgba(220, 53, 69, 0.3);
        animation: pulse 0.5s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .progress-quiz {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .question-nav-btn {
        width: 40px;
        height: 40px;
        border: 2px solid #e3e6f0;
        background: white;
        border-radius: 50%;
        margin: 5px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
    }

    .question-nav-btn:hover {
        border-color: #667eea;
        transform: scale(1.1);
    }

    .question-nav-btn.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .question-nav-btn.answered {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    .question-card {
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
    }

    .question-card.active {
        border-color: #667eea;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
    }

    .answer-option {
        padding: 15px;
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        transition: all 0.3s;
        cursor: pointer;
    }

    .answer-option:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
        transform: translateX(5px);
    }

    .answer-input:checked + .answer-label {
        color: #667eea;
        font-weight: 600;
    }

    .answer-option:has(.answer-input:checked) {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.1);
    }

    .saved-indicator {
        font-size: 20px;
    }

    .question-navigation {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 5px;
    }

    .custom-control-input:checked ~ .custom-control-label::before {
        border-color: #667eea;
        background-color: #667eea;
    }

    @media (max-width: 768px) {
        .timer-display {
            font-size: 18px;
            padding: 8px 15px;
        }

        .question-nav-btn {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
let currentQuestion = 1;
let totalQuestions = {{ $questions->count() }};
let timeLimit = {{ $quiz->time_limit_minutes ? $quiz->time_limit_minutes * 60 : 0 }};
let timeRemaining = timeLimit;
let timerInterval = null;
const startTime = new Date('{{ $attempt->started_at->toIso8601String() }}');

@if($quiz->time_limit_minutes)
// Initialize timer
if (timeLimit > 0) {
    const elapsed = Math.floor((new Date() - startTime) / 1000);
    timeRemaining = Math.max(0, timeLimit - elapsed);
    
    updateTimerDisplay();
    timerInterval = setInterval(function() {
        if (timeRemaining > 0) {
            timeRemaining--;
            updateTimerDisplay();
            
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                alert('Time is up! Submitting quiz...');
                document.getElementById('quiz-form').submit();
            }
        }
    }, 1000);
}

function updateTimerDisplay() {
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = timeRemaining % 60;
    const timeString = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    
    document.getElementById('time-remaining').textContent = timeString;
    
    const timerEl = document.getElementById('timer');
    timerEl.classList.remove('warning', 'danger');
    
    if (timeRemaining <= 300) { // 5 minutes
        timerEl.classList.add('danger');
    } else if (timeRemaining <= 600) { // 10 minutes
        timerEl.classList.add('warning');
    }
}
@endif

// Question Navigation
function showQuestion(index) {
    // Hide all questions
    document.querySelectorAll('.question-card').forEach(card => {
        card.classList.remove('active');
        card.classList.add('d-none');
    });
    
    // Show selected question
    const questionCard = document.querySelector(`.question-card[data-question-index="${index}"]`);
    if (questionCard) {
        questionCard.classList.add('active');
        questionCard.classList.remove('d-none');
    }
    
    // Update navigation
    currentQuestion = index;
    document.getElementById('current-question').textContent = index;
    document.getElementById('question-counter').textContent = `Question ${index} of ${totalQuestions}`;
    
    // Update nav buttons
    updateNavButtons();
    updateProgress();
    
    // Update question nav
    document.querySelectorAll('.question-nav-btn').forEach(btn => {
        btn.classList.remove('active');
        if (parseInt(btn.dataset.question) === index) {
            btn.classList.add('active');
        }
    });
}

function updateNavButtons() {
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    
    prevBtn.style.display = currentQuestion > 1 ? 'block' : 'none';
    
    if (currentQuestion < totalQuestions) {
        nextBtn.style.display = 'block';
        submitBtn.style.display = 'none';
    } else {
        nextBtn.style.display = 'none';
        submitBtn.style.display = 'block';
    }
}

function updateProgress() {
    const answered = document.querySelectorAll('.answer-input:checked').length;
    const progress = (answered / totalQuestions) * 100;
    
    document.getElementById('progress-bar').style.width = progress + '%';
    document.getElementById('answered-count').textContent = answered;
}

// Navigation button clicks
document.getElementById('next-btn')?.addEventListener('click', function() {
    if (currentQuestion < totalQuestions) {
        showQuestion(currentQuestion + 1);
    }
});

document.getElementById('prev-btn')?.addEventListener('click', function() {
    if (currentQuestion > 1) {
        showQuestion(currentQuestion - 1);
    }
});

// Question nav buttons
document.querySelectorAll('.question-nav-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const questionIndex = parseInt(this.dataset.question);
        showQuestion(questionIndex);
    });
});

// Answer change - auto save
document.querySelectorAll('.answer-input').forEach(input => {
    input.addEventListener('change', function() {
        const questionId = this.dataset.questionId;
        const checkedAnswers = Array.from(document.querySelectorAll(`input[name="answers[${questionId}][]"]:checked`))
            .map(input => input.value);
        
        // Auto-save
        fetch('{{ route('quiz.taking.save', ['quiz' => $quiz, 'attempt' => $attempt]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                question_id: questionId,
                answer_ids: checkedAnswers
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show saved indicator
                const questionCard = this.closest('.question-card');
                const savedIndicator = questionCard.querySelector('.saved-indicator');
                savedIndicator.classList.remove('d-none');
                
                setTimeout(() => {
                    savedIndicator.classList.add('d-none');
                }, 2000);
                
                // Update nav button
                const navBtn = document.querySelector(`.question-nav-btn[data-question-id="${questionId}"]`);
                if (navBtn) {
                    navBtn.classList.add('answered');
                }
                
                updateProgress();
            }
        })
        .catch(error => {
            console.error('Error saving answer:', error);
        });
    });
});

// Submit button
document.getElementById('submit-btn')?.addEventListener('click', function() {
    const answered = document.querySelectorAll('.answer-input:checked').length;
    
    if (answered === 0) {
        if (!confirm('You haven\'t answered any questions. Are you sure you want to submit?')) {
            return;
        }
    } else if (answered < totalQuestions) {
        if (!confirm(`You have answered ${answered} out of ${totalQuestions} questions. Are you sure you want to submit?`)) {
            return;
        }
    } else {
        if (!confirm('Are you sure you want to submit your quiz? This action cannot be undone.')) {
            return;
        }
    }
    
    // Clear timer
    if (timerInterval) {
        clearInterval(timerInterval);
    }
    
    // Disable form
    document.getElementById('quiz-form').querySelectorAll('input, button').forEach(el => {
        el.disabled = true;
    });
    
    // Submit form
    document.getElementById('quiz-form').submit();
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft' && currentQuestion > 1) {
        showQuestion(currentQuestion - 1);
    } else if (e.key === 'ArrowRight' && currentQuestion < totalQuestions) {
        showQuestion(currentQuestion + 1);
    }
});

// Prevent accidental page leave
window.addEventListener('beforeunload', function(e) {
    const answered = document.querySelectorAll('.answer-input:checked').length;
    if (answered > 0) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Initialize
updateNavButtons();
updateProgress();

// Mark answered questions in nav
document.querySelectorAll('.answer-input:checked').forEach(input => {
    const questionId = input.dataset.questionId;
    const navBtn = document.querySelector(`.question-nav-btn[data-question-id="${questionId}"]`);
    if (navBtn) {
        navBtn.classList.add('answered');
    }
});
</script>
@endpush
@endsection

