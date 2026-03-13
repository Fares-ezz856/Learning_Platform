@extends('layout.app')

@section('title', 'Edit Course')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Course: {{ $course->title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('instructor.courses.index') }}">My Courses</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Update Course Information</h3>
                        </div>
                        <form action="{{ route('instructor.courses.update', $course->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Course Title</label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $course->title) }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description">Course Description</label>
                                    <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $course->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="price">Course Price ($)</label>
                                    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $course->price) }}" step="0.01" min="0" required>
                                    <small class="form-text text-muted">Set to 0 for a free course.</small>
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Current Status</label>
                                    <div>
                                        @if($course->status == 'approved')
                                            <span class="badge badge-success">Approved / Live</span>
                                        @elseif($course->status == 'pending')
                                            <span class="badge badge-warning">Pending Review</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">Status can only be changed by an administrator.</small>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('instructor.courses.index') }}" class="btn btn-default">Cancel</a>
                                <button type="submit" class="btn btn-info ml-2">Update Course</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
