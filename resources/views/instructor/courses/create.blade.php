@extends('layout.app')

@section('title', 'Create New Course')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create New Course</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('instructor.courses.index') }}">My Courses</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Course Details</h3>
                        </div>
                        <form action="{{ route('instructor.courses.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Course Title</label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Enter course title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description">Course Description</label>
                                    <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="Describe what students will learn" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="price">Course Price ($)</label>
                                    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" placeholder="0.00 for free course" value="{{ old('price', '0.00') }}" step="0.01" min="0" required>
                                    <small class="form-text text-muted">Set to 0 for a free course.</small>
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('instructor.courses.index') }}" class="btn btn-default">Cancel</a>
                                <button type="submit" class="btn btn-primary ml-2">Submit for Approval</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
