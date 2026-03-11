@extends('layout.app')

@section('title', 'Course Lessons')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $course->title }}</h1>
                    <p class="text-muted small">Course Content & Lessons</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.courses.index') }}">My Courses</a></li>
                        <li class="breadcrumb-item active">Lessons</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Curriculum</h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="nav flex-column nav-pills p-2">
                                @forelse($course->lessons as $index => $lesson)
                                <li class="nav-item">
                                    <a href="#" class="nav-link {{ $index == 0 ? 'active' : '' }}">
                                        <i class="fas fa-play-circle mr-2"></i> {{ $lesson->title }}
                                        <span class="float-right text-sm text-muted">10:00</span>
                                    </a>
                                </li>
                                @empty
                                <li class="text-center p-3 text-muted">No lessons uploaded yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    @if($course->lessons->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-play mr-2 text-primary"></i> Current Lesson: <strong>{{ $course->lessons->first()->title }}</strong></h3>
                        </div>
                        <div class="card-body">
                            <div class="embed-responsive embed-responsive-16by9 bg-dark mb-4" style="border-radius: 8px;">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-play fa-4x text-white-50"></i>
                                </div>
                            </div>
                            <h5>About this lesson</h5>
                            <p>This is a placeholder for lesson content. In a full implementation, you would render the video player or document viewer here base on the lesson type.</p>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-outline-secondary" disabled><i class="fas fa-chevron-left mr-1"></i> Previous</button>
                                <button class="btn btn-primary">Next Lesson <i class="fas fa-chevron-right ml-1"></i></button>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="card p-5 text-center bg-light">
                        <i class="fas fa-hourglass-half fa-3x text-muted mb-3"></i>
                        <p class="text-muted">The instructor is still preparing the content for this course. Check back soon!</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
