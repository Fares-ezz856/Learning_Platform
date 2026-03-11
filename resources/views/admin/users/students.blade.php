@extends('layout.app')

@section('title', 'Students Management')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Students</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Students</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-primary">
                        <span class="info-box-icon"><i class="fas fa-user-graduate"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Students</span>
                            <span class="info-box-number">{{ $students->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-purple" style="background-color: #6f42c1 !important; color: white !important;">
                        <span class="info-box-icon"><i class="fas fa-graduation-cap"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Enrollments</span>
                            <span class="info-box-number">{{ $students->sum('courses_count') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-orange" style="background-color: #fd7e14 !important; color: white !important;">
                        <span class="info-box-icon"><i class="fas fa-comment-dots"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Reviews</span>
                            <span class="info-box-number">{{ $students->sum('reviews_count') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card card-indigo card-outline">
                <div class="card-header">
                    <h3 class="card-title">Comprehensive Student List</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search Student..." id="studentSearch">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-head-fixed text-nowrap" id="studentTable">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Activity</th>
                                <th>Contact</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>
                                    <div class="user-block">
                                        <img class="img-circle img-bordered-sm" src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=6f42c1&color=fff" alt="user image">
                                        <span class="username">
                                            <a href="#">{{ $student->name }}</a>
                                        </span>
                                        <span class="description">Member ID: #{{ $student->id }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info mr-1" title="Courses Enrolled">
                                        <i class="fas fa-book-reader mr-1"></i> {{ $student->courses_count }}
                                    </span>
                                    <span class="badge badge-secondary" title="Reviews Left">
                                        <i class="fas fa-pen mr-1"></i> {{ $student->reviews_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <i class="fas fa-envelope mr-1 text-muted"></i> {{ $student->email }}<br>
                                        <i class="fas fa-phone mr-1 text-muted"></i> {{ $student->phone ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>{{ $student->created_at->format('M d, Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.students.delete', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this student record? This will remove all associated enrollment data.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($students->isEmpty())
                        <div class="p-5 text-center">
                            <i class="fas fa-user-slash fa-3x text-gray-300"></i>
                            <p class="mt-3 text-muted">No students found in the system.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.getElementById('studentSearch').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelector("#studentTable tbody").rows;
        
        for (let i = 0; i < rows.length; i++) {
            let name = rows[i].cells[0].textContent.toUpperCase();
            let email = rows[i].cells[2].textContent.toUpperCase();
            if (name.includes(filter) || email.includes(filter)) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    });
</script>
@endpush
@endsection
