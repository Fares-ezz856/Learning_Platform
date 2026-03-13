@extends('layout.app')

@section('title', 'Browse Courses')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Available Courses</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Browse</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('info'))
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fas fa-info"></i> {{ session('info') }}
                </div>
            @endif

            <div class="row">
                @foreach($courses as $course)
                <div class="col-md-4">
                    <div class="card card-outline card-primary h-100">
                        <div class="card-header">
                            <h3 class="card-title">{{ $course->title }}</h3>
                            <div class="card-tools">
                                @if($course->isFree())
                                    <span class="badge badge-success">FREE</span>
                                @else
                                    <span class="badge badge-warning">${{ number_format($course->price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ Str::limit($course->description, 150) }}</p>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-chalkboard-teacher text-info mr-1"></i> {{ $course->instructor->name }}
                                </div>
                                <span class="badge badge-info">{{ $course->students_count }} students</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <form action="{{ route('student.courses.join', $course->id) }}" method="POST">
                                @csrf
                                @if($course->isFree())
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-plus-circle mr-1"></i> Join Free
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-shopping-cart mr-1"></i> Enroll Now - ${{ number_format($course->price, 2) }}
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($courses->isEmpty())
                <div class="col-12">
                    <div class="alert alert-warning">
                        <h5 class="mb-0">No new courses available to join at this moment.</h5>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
