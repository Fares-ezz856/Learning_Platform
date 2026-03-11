@extends('layout.app')

@section('title', 'My Students')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Students</h1>
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

            @foreach($courses as $course)
            <div class="card card-purple card-outline mb-4">
                <div class="card-header">
                    <h3 class="card-title">Students for: <strong>{{ $course->title }}</strong></h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Enrollment Status</th>
                                <th>Change Status</th>
                                <th>Action Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($course->students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    @if($student->pivot->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($student->pivot->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('instructor.students.update-status', $course->id) }}" method="POST" class="form-inline">
                                        @csrf
                                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                                        <select name="status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                                            <option value="pending" {{ $student->pivot->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ $student->pivot->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                            <option value="rejected" {{ $student->pivot->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $student->pivot->created_at ? $student->pivot->created_at->diffForHumans() : 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center p-4 text-muted">No students enrolled in this course yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
            
            @if($courses->isEmpty())
                <div class="card p-5 text-center">
                    <p class="text-muted">You have no active courses to manage students for.</p>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
