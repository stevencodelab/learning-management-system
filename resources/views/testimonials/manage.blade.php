@extends('layouts.skydash')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Manage Testimonials</h3>
                <h6 class="font-weight-normal mb-0">Approve, reject, and manage student testimonials</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('testimonials.index') }}" class="btn btn-info" target="_blank">
                        <i class="icon-eye"></i> View Public Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-title mb-0">Total Testimonials</p>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="icon-lg bg-primary text-white rounded">
                        <i class="mdi mdi-comment-multiple-outline"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-title mb-0">Approved</p>
                        <h3 class="mb-0 text-success">{{ $stats['approved'] }}</h3>
                    </div>
                    <div class="icon-lg bg-success text-white rounded">
                        <i class="mdi mdi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-title mb-0">Pending</p>
                        <h3 class="mb-0 text-warning">{{ $stats['pending'] }}</h3>
                    </div>
                    <div class="icon-lg bg-warning text-white rounded">
                        <i class="mdi mdi-clock-outline"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-title mb-0">Featured</p>
                        <h3 class="mb-0 text-info">{{ $stats['featured'] }}</h3>
                    </div>
                    <div class="icon-lg bg-info text-white rounded">
                        <i class="mdi mdi-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('testimonials.manage') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Search testimonials..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <select name="featured" class="form-control">
                                <option value="">All Testimonials</option>
                                <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured Only</option>
                                <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Not Featured</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="icon-magnifier"></i> Search
                            </button>
                        </div>
                    </div>
                    @if(request()->hasAny(['search', 'status', 'featured']))
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('testimonials.manage') }}" class="btn btn-secondary btn-sm">
                                <i class="icon-close"></i> Clear Filters
                            </a>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Table -->
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Testimonial</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testimonials as $testimonial)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($testimonial->user->avatar)
                                            <img src="{{ asset('storage/' . $testimonial->user->avatar) }}" 
                                                 alt="{{ $testimonial->user->name }}" 
                                                 class="mr-2 rounded-circle"
                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <img src="{{ $testimonial->user->avatar_url }}" 
                                                 alt="{{ $testimonial->user->name }}" 
                                                 class="mr-2 rounded-circle"
                                                 style="width: 32px; height: 32px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('images/landing/testimonials_user.jpg') }}'">
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $testimonial->user->name }}</div>
                                            <small class="text-muted">{{ $testimonial->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($testimonial->course)
                                        <span class="badge badge-info">{{ Str::limit($testimonial->course->title, 30) }}</span>
                                    @else
                                        <span class="text-muted">General</span>
                                    @endif
                                </td>
                                <td style="max-width: 300px;">
                                    <div style="max-height: 60px; overflow: hidden; text-overflow: ellipsis;">
                                        "{{ Str::limit($testimonial->testimonial_text, 100) }}"
                                    </div>
                                </td>
                                <td>
                                    @if($testimonial->rating)
                                        <div style="color: #ffb606;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $testimonial->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <small class="text-muted ml-1">({{ $testimonial->rating }}/5)</small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($testimonial->is_approved)
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($testimonial->is_approved)
                                        @if($testimonial->is_featured)
                                            <span class="badge badge-info">
                                                <i class="mdi mdi-star"></i> Featured
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $testimonial->created_at->format('M d, Y') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if(!$testimonial->is_approved)
                                        <form action="{{ route('testimonials.approve', $testimonial) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" title="Approve">
                                                <i class="mdi mdi-check"></i>
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('testimonials.reject', $testimonial) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-warning btn-sm" title="Reject">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                        </form>
                                        @endif

                                        @if($testimonial->is_approved)
                                        <form action="{{ route('testimonials.toggle-featured', $testimonial) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-{{ $testimonial->is_featured ? 'info' : 'secondary' }} btn-sm" title="{{ $testimonial->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}">
                                                <i class="mdi mdi-star{{ $testimonial->is_featured ? '' : '-outline' }}"></i>
                                            </button>
                                        </form>
                                        @endif

                                        <a href="{{ route('testimonials.show', $testimonial) }}" 
                                           class="btn btn-info btn-sm" 
                                           target="_blank"
                                           title="View">
                                            <i class="mdi mdi-eye"></i>
                                        </a>

                                        <form action="{{ route('testimonials.destroy', $testimonial) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="mdi mdi-comment-remove-outline" style="font-size: 48px; color: #ccc;"></i>
                                    <h5 class="mt-3">No testimonials found</h5>
                                    <p class="text-muted">Try adjusting your search criteria.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $testimonials->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Show success message if any
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif
</script>
@endpush
@endsection

