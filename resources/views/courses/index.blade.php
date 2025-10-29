@extends('layouts.skydash')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
                    <h3 class="font-weight-bold">Courses Management</h3>
                    <h6 class="font-weight-normal mb-0">Manage and organize all your courses here</h6>
                @else
                    <h3 class="font-weight-bold">Available Courses</h3>
                    <h6 class="font-weight-normal mb-0">Browse and enroll in courses</h6>
                @endif
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
                        <a href="{{ route('courses.create') }}" class="btn btn-primary">
                            <i class="icon-plus"></i> Create New Course
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Search -->
@if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Advanced Search</h4>
                <form action="{{ route('courses.index') }}" method="GET" id="searchForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">Search</label>
                                <input type="text" class="form-control" name="search" id="search" 
                                       placeholder="Search courses..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="level">Level</label>
                                <select class="form-control" name="level" id="level">
                                    <option value="">All Levels</option>
                                    <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="published">Status</label>
                                <select class="form-control" name="published" id="published">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('published') == '1' ? 'selected' : '' }}>Published</option>
                                    <option value="0" {{ request('published') == '0' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="price_min">Min Price</label>
                                <input type="number" step="0.01" class="form-control" name="price_min" 
                                       id="price_min" placeholder="0.00" value="{{ request('price_min') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="price_max">Max Price</label>
                                <input type="number" step="0.01" class="form-control" name="price_max" 
                                       id="price_max" placeholder="9999.99" value="{{ request('price_max') }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="icon-magnifier"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button type="reset" class="btn btn-secondary btn-sm" onclick="window.location.href='{{ route('courses.index') }}'">
                                <i class="icon-refresh"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Courses DataTable -->
<div class="row mt-3">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="coursesTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Duration</th>
                                <th>Modules</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courses as $course)
                            <tr>
                                <td data-priority="1">
                                    <div class="d-flex align-items-center">
                                        @if($course->thumbnail)
                                            <img src="{{ Storage::url($course->thumbnail) }}" 
                                                 alt="{{ $course->title }}"
                                                 class="d-none d-md-block"
                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; margin-right: 12px; flex-shrink: 0;">
                                        @else
                                            <div class="d-none d-md-block" style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px; flex-shrink: 0;">
                                                <i class="icon-book text-white" style="font-size: 24px;"></i>
                                            </div>
                                        @endif
                                        <div style="min-width: 0;">
                                            <div class="font-weight-bold" style="word-break: break-word;">{{ $course->title }}</div>
                                            <small class="text-muted d-none d-md-block" style="word-break: break-word;">{{ Str::limit($course->description, 60) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td data-priority="4">
                                    <span class="badge badge-info">{{ ucfirst($course->level) }}</span>
                                </td>
                                <td data-priority="2">
                                    <span class="badge {{ $course->is_published ? 'badge-success' : 'badge-warning' }}">
                                        {{ $course->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td data-priority="5">
                                    <span class="text-muted">
                                        <i class="icon-clock mr-1"></i>{{ $course->duration_hours }}h
                                    </span>
                                </td>
                                <td data-priority="6">
                                    <span class="text-muted">
                                        <i class="icon-people mr-1"></i>{{ $course->modules->count() }}
                                    </span>
                                </td>
                                <td data-priority="3">
                                    @if($course->price == 0)
                                        <span class="badge badge-success">FREE</span>
                                    @else
                                        <span class="font-weight-bold text-primary">${{ number_format($course->price, 2) }}</span>
                                    @endif
                                </td>
                                <td data-priority="1">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ $course->url }}" 
                                           class="btn btn-info" 
                                           title="View">
                                            <i class="icon-eye"></i>
                                        </a>
                                        @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
                                            <a href="{{ route('courses.edit', $course) }}" 
                                               class="btn btn-primary" 
                                               title="Edit">
                                                <i class="icon-pencil"></i>
                                            </a>
                                            <form action="{{ route('courses.destroy', $course) }}" 
                                                  method="POST" 
                                                  class="d-inline" 
                                                  onsubmit="event.preventDefault(); confirmDelete(event);">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger" 
                                                        title="Delete">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-center py-5">
                                        <i class="icon-book" style="font-size: 64px; color: #ccc;"></i>
                                        <h4 class="mt-3">No courses found</h4>
                                        <p class="text-muted">
                                            @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
                                                Start by creating your first course!
                                            @else
                                                There are no courses available at the moment.
                                            @endif
                                        </p>
                                        @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'instructor']))
                                            <a href="{{ route('courses.create') }}" class="btn btn-primary mt-3">
                                                <i class="icon-plus"></i> Create Course
                                            </a>
                                        @endif
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
    #searchForm .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 5px;
    }
    
    #searchForm .form-control {
        border-radius: 8px;
    }
    
    #coursesTable_wrapper .dataTables_length,
    #coursesTable_wrapper .dataTables_filter {
        margin-bottom: 20px;
    }
    
    #coursesTable_wrapper .dataTables_filter {
        text-align: right;
    }
    
    #coursesTable_wrapper .dataTables_filter input {
        border-radius: 8px;
        padding: 8px 12px;
        border: 1px solid #ddd;
        margin-left: 10px;
        width: 250px;
    }
    
    #coursesTable_wrapper .dataTables_length select {
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
    
    .badge-success {
        background-color: #28a745;
        color: white;
    }
    
    .badge-warning {
        background-color: #ffc107;
        color: #333;
    }
    
    .badge-info {
        background-color: #17a2b8;
        color: white;
    }
    
    #coursesTable tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .btn-group .btn {
        margin-right: 5px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    @media (max-width: 768px) {
        #coursesTable_wrapper .dataTables_length,
        #coursesTable_wrapper .dataTables_filter {
            margin-bottom: 15px;
        }
        
        #coursesTable_wrapper .dataTables_filter {
            text-align: left;
            margin-top: 10px;
        }
        
        #coursesTable_wrapper .dataTables_filter input {
            width: 100%;
            margin-left: 0;
            margin-top: 10px;
        }
        
        #coursesTable_wrapper .dataTables_info,
        #coursesTable_wrapper .dataTables_paginate {
            margin-top: 15px;
            text-align: center !important;
        }
        
        #coursesTable_wrapper .dataTables_paginate .pagination {
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
        #coursesTable_wrapper .dataTables_filter input {
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
            $('#coursesTable').DataTable({
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
                    searchPlaceholder: "Search courses...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ courses",
                    infoEmpty: "No courses available",
                    infoFiltered: "(filtered from _MAX_ total courses)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    },
                    emptyTable: "No courses found"
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
            
            $('#coursesTable').each(function() {
                var datatable = $(this);
                var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                search_input.attr('placeholder', 'Search courses...');
                search_input.removeClass('form-control-sm');
                var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
                length_sel.removeClass('form-control-sm');
            });
        });
    })(jQuery);
</script>
@endpush
@endsection
