<!-- Available Quizzes Section -->
<div class="quizzes page_section" id="quizzes">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="section_title text-center">
                    <h1>Interactive Quizzes</h1>
                    <p class="lead">Test your knowledge with our comprehensive quizzes</p>
                </div>
            </div>
        </div>
        
        @if($quizzes->count() > 0)
        <div class="row quiz-cards-row quiz-cards-compact">
            @foreach($quizzes as $quiz)
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100 quiz-card quiz-card-compact d-flex flex-column">
                    <div class="card-body d-flex flex-column flex-grow-1 quiz-body-compact">
                        <h5 class="card-title quiz-title-compact mb-2">
                            <a href="{{ route('quizzes.detail', $quiz) }}">{{ Str::limit($quiz->title, 50) }}</a>
                        </h5>
                        <p class="card-text flex-grow-1 quiz-text-compact mb-2">{{ Str::limit($quiz->description ?? 'Test your knowledge with this comprehensive quiz', 80) }}</p>
                        <div class="quiz-badges quiz-badges-compact mb-2">
                            <span class="badge badge-primary badge-xs">{{ $quiz->questions_count ?? 0 }} Questions</span>
                            @if($quiz->passing_score)
                            <span class="badge badge-success badge-xs">{{ $quiz->passing_score }}% to Pass</span>
                            @endif
                            @if($quiz->time_limit_minutes)
                            <span class="badge badge-info badge-xs">{{ $quiz->time_limit_minutes }} Min</span>
                            @endif
                        </div>
                        <div class="card-text mt-auto quiz-course-compact">
                            <small class="text-muted">
                                <i class="fas fa-book"></i> {{ Str::limit(optional($quiz->lesson)->module->course->title ?? 'Course Quiz', 25) }}
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top quiz-footer-compact">
                        @auth
                            <a href="{{ route('quizzes.detail', $quiz) }}" class="btn btn-outline-info btn-xs w-100 mb-1">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-primary btn-xs w-100">
                                <i class="fas fa-play-circle"></i> Take Quiz
                            </a>
                        @else
                            <a href="{{ route('quizzes.detail', $quiz) }}" class="btn btn-outline-info btn-xs w-100 mb-1">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-xs w-100">
                                <i class="fas fa-sign-in-alt"></i> Login to Take Quiz
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if(method_exists($quizzes, 'hasPages') && $quizzes->hasPages())
        <div class="row mt-4">
            <div class="col">
                <div class="d-flex justify-content-center">
                    {{ $quizzes->links() }}
                </div>
            </div>
        </div>
        @else
        <div class="row mt-3">
            <div class="col text-center">
                <a href="{{ route('landing.quizzes') }}" class="button button_1">
                    <span>View All Quizzes</span>
                </a>
            </div>
        </div>
        @endif
        @else
        <div class="row">
            <div class="col text-center">
                <p class="lead text-muted">No quizzes available at the moment. Enroll in courses to access quizzes.</p>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .quizzes {
        padding-top: 80px;
        padding-bottom: 80px;
    }
    
    .quiz-cards-row {
        display: flex;
        flex-wrap: wrap;
    }
    
    .quiz-cards-row > [class*='col-'] {
        display: flex;
    }
    
    .quiz-card {
        transition: all 0.3s ease;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        width: 100%;
        min-height: 100%;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
    
    .quiz-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    /* Compact Quiz Cards */
    .quiz-card-compact {
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
    
    .quiz-card-compact:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.12);
    }
    
    .quiz-body-compact {
        padding: 1rem !important;
    }
    
    .quiz-title-compact {
        min-height: 2.5rem;
        margin-bottom: 0.5rem;
    }
    
    .quiz-title-compact a {
        font-size: 0.95rem !important;
        line-height: 1.3;
    }
    
    .quiz-text-compact {
        font-size: 0.8rem !important;
        line-height: 1.4;
        min-height: 2.5rem;
        color: #6c757d;
    }
    
    .quiz-badges-compact {
        gap: 0.35rem;
        margin-bottom: 0.5rem;
    }
    
    .quiz-badges-compact .badge-xs {
        font-size: 0.65rem;
        padding: 0.25em 0.5em;
        font-weight: 500;
    }
    
    .quiz-course-compact {
        font-size: 0.75rem;
    }
    
    .quiz-footer-compact {
        padding: 0.75rem 1rem !important;
    }
    
    .quiz-footer-compact .btn-xs {
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
        line-height: 1.4;
    }
    
    .quiz-card .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 1.5rem;
    }
    
    .quiz-card .card-title {
        min-height: 3rem;
        margin-bottom: 1rem;
    }
    
    .quiz-card .card-title a {
        color: #1e40af;
        font-weight: 600;
        text-decoration: none;
        font-size: 1.1rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .quiz-card .card-title a:hover {
        color: #3b82f6;
        text-decoration: none;
    }
    
    .quiz-card .card-text {
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.6;
        min-height: 4.5rem;
    }
    
    .quiz-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .quiz-badges .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
    
    .quiz-card .card-footer {
        margin-top: auto;
        padding: 1rem 1.5rem;
        background-color: #f8f9fa;
    }
    
    .quiz-card .card-footer .btn {
        border-radius: 4px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .quiz-card .card-footer .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .pagination {
        justify-content: center;
    }
    
    .pagination .page-link {
        color: #1e40af;
        border-color: #1e40af;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #1e40af;
        border-color: #1e40af;
    }
    
    /* Ensure equal height on all cards */
    @media (min-width: 992px) {
        .quiz-cards-row {
            align-items: stretch;
        }
    }
</style>
@endpush
