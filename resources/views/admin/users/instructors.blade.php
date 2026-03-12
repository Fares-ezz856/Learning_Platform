@extends('layout.app')

@section('title', 'Instructors Management')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Instructors</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Instructors</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Instructors</span>
                            <span class="info-box-number">{{ $instructors->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-book"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Courses</span>
                            <span class="info-box-number">{{ $instructors->sum('courses_count') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box bg-warning">
                      <span class="info-box-icon"><i class="fas fa-star"></i></span>
                      <div class="info-box-content">
                          <span class="info-box-text">Average Reviews</span>
                          <span class="info-box-number">{{ number_format($instructors->avg('reviews_count'), 1) }}</span>
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

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Comprehensive Instructor List</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search Instructor..." id="instructorSearch">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-head-fixed text-nowrap" id="instructorTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Stats</th>
                                <th>Contact Info</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instructors as $instructor)
                            <tr>
                                <td>
                                    <div class="user-block">
                                        <img class="img-circle img-bordered-sm" src="https://ui-avatars.com/api/?name={{ urlencode($instructor->name) }}&background=random" alt="user image">
                                        <span class="username">
                                            <a href="#">{{ $instructor->name }}</a>
                                        </span>
                                        <span class="description">ID: #{{ $instructor->id }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-primary mr-1" title="Courses Created">
                                        <i class="fas fa-book mr-1"></i> {{ $instructor->courses_count }}
                                    </span>
                                    <span class="badge badge-warning" title="Reviews Received">
                                        <i class="fas fa-comment mr-1"></i> {{ $instructor->reviews_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <i class="fas fa-envelope mr-1"></i> {{ $instructor->email }}<br>
                                        <i class="fas fa-phone mr-1"></i> {{ $instructor->phone ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>{{ $instructor->created_at->diffForHumans() }}</td>
                                <td>
                                    <div class="btn-group">
                                        <form action="{{ route('admin.instructors.delete', $instructor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this instructor? This action is irreversible.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-user-slash mr-1"></i> Remove</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($instructors->isEmpty())
                        <div class="p-5 text-center">
                            <i class="fas fa-users-slash fa-3x text-gray-300"></i>
                            <p class="mt-3">No instructors registered yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.getElementById('instructorSearch').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelector("#instructorTable tbody").rows;

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
