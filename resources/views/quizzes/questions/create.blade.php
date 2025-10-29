@extends('layouts.skydash')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Add New Question</h3>
                <h6 class="font-weight-normal mb-0">{{ $quiz->title }}</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('quiz.questions.index', $quiz) }}" class="btn btn-secondary">
                        <i class="icon-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('quiz.questions.store', $quiz) }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="question_text" class="form-label">Question Text <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('question_text') is-invalid @enderror" 
                                  id="question_text" name="question_text" rows="3" required
                                  placeholder="Enter the question...">{{ old('question_text') }}</textarea>
                        @error('question_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('type') is-invalid @enderror" 
                                        id="type" name="type" required>
                                    <option value="">Select type...</option>
                                    @foreach($questionTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="points" class="form-label">Points <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('points') is-invalid @enderror" 
                                       id="points" name="points" value="{{ old('points', 1) }}" 
                                       min="1" required>
                                @error('points')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="difficulty" class="form-label">Difficulty</label>
                                <select class="form-control @error('difficulty') is-invalid @enderror" 
                                        id="difficulty" name="difficulty">
                                    <option value="">Select difficulty...</option>
                                    <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                                    <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                                </select>
                                @error('difficulty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="explanation" class="form-label">Explanation</label>
                        <textarea class="form-control @error('explanation') is-invalid @enderror" 
                                  id="explanation" name="explanation" rows="2"
                                  placeholder="Optional explanation for the correct answer">{{ old('explanation') }}</textarea>
                        @error('explanation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <!-- Answers Section -->
                    <h5 class="mb-3"><i class="icon-options text-success"></i> Answers</h5>
                    <div id="answers-container">
                        <div class="answer-item card mb-3" data-index="0">
                            <div class="card-body">
                                <div class="form-row align-items-center">
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="answers[0][text]" 
                                               placeholder="Enter answer option..." required>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="answers[0][is_correct]" 
                                                   value="1" id="correct_0">
                                            <label class="form-check-label" for="correct_0">
                                                Correct Answer
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-sm btn-danger remove-answer" disabled>
                                            <i class="icon-close"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="answer-item card mb-3" data-index="1">
                            <div class="card-body">
                                <div class="form-row align-items-center">
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="answers[1][text]" 
                                               placeholder="Enter answer option..." required>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="answers[1][is_correct]" 
                                                   value="1" id="correct_1">
                                            <label class="form-check-label" for="correct_1">
                                                Correct Answer
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-sm btn-danger remove-answer">
                                            <i class="icon-close"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-success" id="add-answer">
                        <i class="icon-plus"></i> Add Answer
                    </button>

                    <hr class="my-4">

                    <div class="form-group">
                        <label for="order" class="form-label">Question Order</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" 
                               id="order" name="order" value="{{ old('order', $quiz->questions->count() + 1) }}" 
                               min="1">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Leave empty to add at the end</small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-check"></i> Create Question
                        </button>
                        <a href="{{ route('quiz.questions.index', $quiz) }}" class="btn btn-secondary ml-2">
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
let answerIndex = 2;

document.getElementById('add-answer').addEventListener('click', function() {
    const container = document.getElementById('answers-container');
    const newAnswer = document.createElement('div');
    newAnswer.className = 'answer-item card mb-3';
    newAnswer.dataset.index = answerIndex;
    newAnswer.innerHTML = `
        <div class="card-body">
            <div class="form-row align-items-center">
                <div class="col-md-8">
                    <input type="text" class="form-control" name="answers[${answerIndex}][text]" 
                           placeholder="Enter answer option..." required>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answers[${answerIndex}][is_correct]" 
                               value="1" id="correct_${answerIndex}">
                        <label class="form-check-label" for="correct_${answerIndex}">
                            Correct Answer
                        </label>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-danger remove-answer">
                        <i class="icon-close"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.appendChild(newAnswer);
    updateRemoveButtons();
    answerIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-answer')) {
        const answerItem = e.target.closest('.answer-item');
        const allItems = document.querySelectorAll('.answer-item');
        if (allItems.length > 2) {
            answerItem.remove();
            updateRemoveButtons();
        }
    }
});

function updateRemoveButtons() {
    const items = document.querySelectorAll('.answer-item');
    items.forEach(item => {
        const btn = item.querySelector('.remove-answer');
        if (items.length > 2) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    });
}
</script>
@endpush
@endsection

