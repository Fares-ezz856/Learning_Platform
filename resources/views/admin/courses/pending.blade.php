@extends('layout.app')

@section('title', 'Pending Courses')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pending Courses</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Pending Courses</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                     <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Courses awaiting approval</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Instructor</th>
                                        <th>Description</th>
                                        <th>Submitted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($courses as $course)
                                    <tr>
                                        <td>{{ $course->id }}</td>
                                        <td>{{ $course->title }}</td>
                                        <td>{{ $course->instructor ? $course->instructor->name : 'N/A' }}</td>
                                        <td>{{ Str::limit($course->description, 80) }}</td>
                                        <td>{{ $course->created_at->format('M d, Y') }}</td>
                                        <td style="white-space: nowrap;">
                                            <form action="{{ route('admin.courses.approve', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to approve this course?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success mr-1"><i class="fas fa-check"></i> Approve</button>
                                            </form>
                                            
                                            <form action="{{ route('admin.courses.reject', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to reject this course?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning mr-1"><i class="fas fa-times"></i> Reject</button>
                                            </form>

                                            <form action="{{ route('admin.courses.delete', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to completely delete this course?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($courses->isEmpty())
                                <p class="text-center mt-3 text-muted">No pending courses found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
