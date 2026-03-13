@extends('layout.app')

@section('title', 'Course Lessons')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Lessons for: {{ $course->title }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('instructor.course.lessons.create', $course->id) }}" class="btn btn-purple" style="background-color: #6f42c1; color: white;">
                        <i class="fas fa-plus"></i> Add New Lesson
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card card-purple card-outline">
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 40px">Order</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Added On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($course->lessons as $lesson)
                            <tr>
                                <td>{{ $lesson->order }}</td>
                                <td>{{ $lesson->title }}</td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst($lesson->content_type) }}</span>
                                </td>
                                <td>{{ $lesson->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('instructor.lessons.edit', $lesson->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('instructor.lessons.destroy', $lesson->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this lesson?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center p-4 text-muted">No lessons found for this course.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('instructor.courses.index') }}" class="btn btn-default">Back to Courses</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
