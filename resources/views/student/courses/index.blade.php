@extends('layout.app')

@section('title', 'My Courses')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Learning Journey</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach($courses as $course)
                <div class="col-md-4">
                    <div class="card card-outline card-purple">
                        <div class="card-header">
                            <h3 class="card-title">{{ $course->title }}</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">By {{ $course->instructor->name }}</p>
                            <p>{{ Str::limit($course->description, 100) }}</p>
                            
                            <div class="progress mb-3" style="height: 5px;">
                                <div class="progress-bar bg-purple" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge {{ $course->pivot->status == 'approved' ? 'badge-success' : ($course->pivot->status == 'pending' ? 'badge-warning' : 'badge-danger') }}">
                                    {{ ucfirst($course->pivot->status) }}
                                </span>
                                @if($course->pivot->status == 'approved')
                                    <a href="{{ route('student.courses.lessons', $course->id) }}" class="btn btn-sm btn-primary">Continue Learning</a>
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled>Locked</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($courses->isEmpty())
                <div class="card p-5 text-center">
                    <p class="text-muted">You haven't joined any courses yet. Explore our catalog and start your learning today!</p>
                    <a href="#" class="btn btn-purple" style="background-color: #6f42c1; color: white;">Browse Courses</a>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
