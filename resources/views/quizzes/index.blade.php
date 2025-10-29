@extends('layouts.skydash')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
                    <h3 class="font-weight-bold">Quizzes Management</h3>
                    <h6 class="font-weight-normal mb-0">Manage all quizzes for your lessons</h6>
                @else
                    <h3 class="font-weight-bold">Available Quizzes</h3>
                    <h6 class="font-weight-normal mb-0">Test your knowledge with these quizzes</h6>
                @endif
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
                        @if(isset($lessons) && $lessons && $lessons->count() > 0)
                            <a href="{{ url('/lessons/' . $lessons->first()->id . '/quizzes/create') }}" class="btn btn-primary mr-2">
                                <i class="icon-plus"></i> Create Quiz
                            </a>
                        @else
                            <button type="button" class="btn btn-primary mr-2" disabled title="No lessons available">
                                <i class="icon-plus"></i> Create Quiz
                            </button>
                        @endif
                    @endif
                    <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">
                        <i class="icon-refresh"></i> Refresh
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
@if($quizzes->count() > 0)
<div class="row">
    @php
        $totalQuizzes = $quizzes->total();
        $publishedQuizzes = $quizzes->filter(fn($q) => $q->status == 'published')->count();
        $totalQuestions = $quizzes->sum(fn($q) => $q->questions->count());
        $totalAttempts = $quizzes->sum(fn($q) => $q->attempts->count());
    @endphp
    
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="icon-note" style="font-size: 40px; color: #667eea;"></i>
                <h3 class="mt-3 mb-0">{{ $totalQuizzes }}</h3>
                <p class="text-muted mb-0">Total Quizzes</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="icon-check" style="font-size: 40px; color: #06beb6;"></i>
                <h3 class="mt-3 mb-0">{{ $publishedQuizzes }}</h3>
                <p class="text-muted mb-0">Published</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="icon-question" style="font-size: 40px; color: #f5576c;"></i>
                <h3 class="mt-3 mb-0">{{ $totalQuestions }}</h3>
                <p class="text-muted mb-0">Total Questions</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="icon-people" style="font-size: 40px; color: #4facfe;"></i>
                <h3 class="mt-3 mb-0">{{ $totalAttempts }}</h3>
                <p class="text-muted mb-0">Total Attempts</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Quizzes DataTable -->
<div class="row mt-3">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="quizzesTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Quiz Title</th>
                                <th>Course</th>
                                <th>Lesson</th>
                                <th>Status</th>
                                <th>Score/Time</th>
                                <th>Questions</th>
                                <th>Attempts</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($quizzes as $quiz)
                            <tr>
                                <td data-priority="1">
                                    <div>
                                        <div class="font-weight-bold" style="word-break: break-word;">{{ $quiz->title }}</div>
                                        @if($quiz->description)
                                            <small class="text-muted d-none d-md-block" style="word-break: break-word;">{{ Str::limit($quiz->description, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td data-priority="3">
                                    <span class="text-muted d-none d-lg-block">{{ Str::limit($quiz->lesson->module->course->title ?? 'N/A', 30) }}</span>
                                    <span class="text-muted d-lg-none">{{ Str::limit($quiz->lesson->module->course->title ?? 'N/A', 20) }}</span>
                                </td>
                                <td data-priority="4">
                                    <span class="text-muted d-none d-lg-block">{{ Str::limit($quiz->lesson->title ?? 'N/A', 30) }}</span>
                                    <span class="text-muted d-lg-none">{{ Str::limit($quiz->lesson->title ?? 'N/A', 20) }}</span>
                                </td>
                                <td data-priority="2">
                                    @php
                                        $statusColors = [
                                            'draft' => 'warning',
                                            'published' => 'success',
                                            'archived' => 'secondary'
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $statusColors[$quiz->status] ?? 'secondary' }}">
                                        {{ ucfirst($quiz->status) }}
                                    </span>
                                    @if($quiz->isAvailable())
                                        <br><small class="text-success d-none d-md-inline"><i class="icon-check"></i> Available</small>
                                    @endif
                                </td>
                                <td data-priority="5">
                                    <div>
                                        <small class="text-muted">Pass:</small>
                                        <span class="badge badge-info">{{ $quiz->passing_score }}%</span>
                                    </div>
                                    <div class="mt-1">
                                        <small class="text-muted">Time:</small>
                                        <span class="text-muted">{{ $quiz->time_limit_minutes ? $quiz->time_limit_minutes . 'm' : '∞' }}</span>
                                    </div>
                                </td>
                                <td data-priority="6">
                                    <span class="badge badge-primary">
                                        <i class="icon-question mr-1"></i>{{ $quiz->questions->count() }}
                                    </span>
                                </td>
                                <td data-priority="7">
                                    <span class="badge badge-success">
                                        <i class="icon-people mr-1"></i>{{ $quiz->attempts->count() }}
                                    </span>
                                </td>
                                <td data-priority="1">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ $quiz->url }}" 
                                           class="btn btn-info" 
                                           title="View Details">
                                            <i class="icon-eye"></i>
                                        </a>
                                        @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
                                            <a href="{{ route('quizzes.edit', $quiz) }}" 
                                               class="btn btn-primary" 
                                               title="Edit">
                                                <i class="icon-pencil"></i>
                                            </a>
                                            <a href="{{ route('quiz.questions.index', $quiz) }}" 
                                               class="btn btn-warning" 
                                               title="Manage Questions">
                                                <i class="icon-question"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    onclick="confirmDelete('{{ route('quizzes.destroy', $quiz) }}')" 
                                                    title="Delete">
                                                <i class="icon-trash"></i>
                                            </button>
                                        @elseif(auth()->check() && !auth()->user()->hasAnyRole(['admin', 'instructor']))
                                            @if($quiz->isAvailable() && $quiz->canUserAttempt(auth()->id()))
                                                <a href="{{ route('quiz.taking.start', $quiz) }}" 
                                                   class="btn btn-success" 
                                                   title="Start Quiz">
                                                    <i class="icon-control-play"></i>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-center py-5">
                                        <i class="icon-note" style="font-size: 64px; color: #ccc;"></i>
                                        <h4 class="mt-3">No quizzes found</h4>
                                        <p class="text-muted">
                                            @if(request('search') || request('status') || request('lesson_id'))
                                                Try adjusting your search criteria.
                                            @else
                                                No quizzes have been created yet.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
<style>
    .stats-card {
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        height: 100%;
    }
    
    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    #quizzesTable_wrapper .dataTables_length,
    #quizzesTable_wrapper .dataTables_filter {
        margin-bottom: 20px;
    }
    
    #quizzesTable_wrapper .dataTables_filter {
        text-align: right;
    }
    
    #quizzesTable_wrapper .dataTables_filter input {
        border-radius: 8px;
        padding: 8px 12px;
        border: 1px solid #ddd;
        margin-left: 10px;
        width: 250px;
    }
    
    #quizzesTable_wrapper .dataTables_length select {
        border-radius: 8px;
        padding: 8px 12px;
        border: 1px solid #ddd;
        margin: 0 5px;
    }
    
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }
    
    #quizzesTable tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .btn-group .btn {
        margin-right: 5px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    @media (max-width: 768px) {
        #quizzesTable_wrapper .dataTables_length,
        #quizzesTable_wrapper .dataTables_filter {
            margin-bottom: 15px;
        }
        
        #quizzesTable_wrapper .dataTables_filter {
            text-align: left;
            margin-top: 10px;
        }
        
        #quizzesTable_wrapper .dataTables_filter input {
            width: 100%;
            margin-left: 0;
            margin-top: 10px;
        }
        
        #quizzesTable_wrapper .dataTables_info,
        #quizzesTable_wrapper .dataTables_paginate {
            margin-top: 15px;
            text-align: center !important;
        }
        
        #quizzesTable_wrapper .dataTables_paginate .pagination {
            justify-content: center;
        }
        
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.child,
        table.dataTable.dtr-inline.collapsed > tbody > tr > th.child {
            padding: 0.5rem;
        }
        
        .dtr-details {
            list-style: none;
            padding: 0;
        }
        
        .dtr-details li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .dtr-details li:last-child {
            border-bottom: none;
        }
        
        .dtr-title {
            font-weight: 600;
            color: #333;
            margin-right: 10px;
            min-width: 120px;
            display: inline-block;
        }
        
        .dtr-data {
            color: #666;
        }
    }
    
    @media (min-width: 769px) and (max-width: 1024px) {
        #quizzesTable_wrapper .dataTables_filter input {
            width: 200px;
        }
    }
    
    table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child:before,
    table.dataTable.dtr-inline.collapsed > tbody > tr > th:first-child:before {
        background-color: #667eea;
        border: 2px solid white;
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
        top: 50%;
        transform: translateY(-50%);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script>
    (function($) {
        'use strict';
        $(document).ready(function() {
            $('#quizzesTable').DataTable({
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[0, 'asc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search quizzes...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ quizzes",
                    infoEmpty: "No quizzes available",
                    infoFiltered: "(filtered from _MAX_ total quizzes)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    },
                    emptyTable: "No quizzes found"
                },
                columnDefs: [
                    {
                        targets: -1,
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1
                    }
                ],
                autoWidth: false
            });
            
            $('#quizzesTable').each(function() {
                var datatable = $(this);
                var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                search_input.attr('placeholder', 'Search quizzes...');
                search_input.removeClass('form-control-sm');
                var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
                length_sel.removeClass('form-control-sm');
            });
        });
    })(jQuery);
    
    function confirmDelete(url) {
        if (confirm('Are you sure you want to delete this quiz? This action cannot be undone.')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            
            var csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            var method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection
