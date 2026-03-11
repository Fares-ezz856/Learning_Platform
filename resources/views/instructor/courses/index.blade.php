@extends('layout.app')

@section('title', 'My Courses')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Courses</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> Create New Course</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">List of Courses You Manage</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Enrolled Students</th>
                                <th>Status</th>
                                <th>Rating</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td>
                                    <strong>{{ $course->title }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($course->description, 50) }}</small>
                                </td>
                                <td><span class="badge badge-info">{{ $course->students_count }} students</span></td>
                                <td>
                                    @if($course->status == 'approved')
                                        <span class="badge badge-success">Live</span>
                                    @elseif($course->status == 'pending')
                                        <span class="badge badge-warning">Awaiting Approval</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <i class="fas fa-star text-warning"></i> 4.5
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-default" title="View Details"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="btn btn-sm btn-info" title="Edit Course"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-warning" title="Manage Lessons"><i class="fas fa-list"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($courses->isEmpty())
                        <div class="p-5 text-center">
                            <p class="text-muted">You haven't created any courses yet.</p>
                            <a href="#" class="btn btn-info">Create Your First Course</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
