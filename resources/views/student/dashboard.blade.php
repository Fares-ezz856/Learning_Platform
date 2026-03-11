@extends('layout.app')

@section('title', 'Student Dashboard')

@section('body')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">My Learning Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book-open"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">My Courses</span>
                <span class="info-box-number">{{ $course_count }}</span>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-play-circle"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Available Lessons</span>
                <span class="info-box-number">{{ $lesson_count }}</span>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-certificate"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Achievements</span>
                <span class="info-box-number">0</span>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">My Recent Courses</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>Course ID</th>
                      <th>Title</th>
                      <th>Instructor</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($enrolled_courses as $course)
                    <tr>
                      <td><a href="#">#{{ $course->id }}</a></td>
                      <td>{{ $course->title }}</td>
                      <td>{{ $course->instructor->name }}</td>
                      <td>
                        @if($course->pivot->status == 'approved')
                          <span class="badge badge-success">Approved</span>
                        @elseif($course->pivot->status == 'pending')
                          <span class="badge badge-warning">Pending</span>
                        @else
                          <span class="badge badge-danger">Rejected</span>
                        @endif
                      </td>
                      <td>
                        <a href="#" class="btn btn-sm btn-primary">Go to Lessons</a>
                      </td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
                @if($enrolled_courses->isEmpty())
                <div class="p-4 text-center">
                  <p class="text-muted">You haven't joined any courses yet. <a href="#">Browse courses</a></p>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection
