@extends('layout.app')

@section('title', 'All Lessons')

@section('body')
<a href="{{ route('instructor.courses.index') }}" class="btn btn-primary">Back to Courses</a>
@if(isset($courses) && $courses->count() > 0)
    <div class="mb-3 ml-3">
        <a href="{{ route('instructor.lessons.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Lesson
        </a>
    </div>
@endif

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>All My Lessons</h1>
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
                                <th>Course</th>
                                <th>Lesson Title</th>
                                <th>Type</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lessons as $lesson)
                            <tr>
                                <td>{{ $lesson->course->title }}</td>
                                <td>{{ $lesson->title }}</td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst($lesson->content_type) }}</span>
                                </td>
                                <td>{{ $lesson->order }}</td>
                                <td>
                                    <a href="{{ route('instructor.lessons.edit', $lesson->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('instructor.lessons.destroy', $lesson->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
                                <td colspan="5" class="text-center p-4 text-muted">No lessons found across your courses.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
