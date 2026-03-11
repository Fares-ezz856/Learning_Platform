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
          <div class="col-md-8">
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">My Recent Courses</h3>
                <div class="card-tools">
                  <a href="{{ route('student.courses.index') }}" class="btn btn-sm btn-primary">View All</a>
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
                      <td>#{{ $course->id }}</td>
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
                        @if($course->pivot->status == 'approved')
                          <a href="{{ route('student.courses.lessons', $course->id) }}" class="btn btn-sm btn-primary">Lessons</a>
                        @else
                          <span class="text-muted small">Awaiting Access</span>
                        @endif
                      </td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
                @if($enrolled_courses->isEmpty())
                <div class="p-4 text-center">
                  <p class="text-muted">You haven't joined any courses yet. <a href="{{ route('student.courses.browse') }}">Browse available courses</a></p>
                </div>
                @endif
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Enrollment Status</h3>
              </div>
              <div class="card-body">
                <canvas id="statusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div><!--/. container-fluid -->
    </section>
</div>

@push('scripts')
<script>
$(function () {
  var statusChartCanvas = $('#statusChart').get(0).getContext('2d');
  var statusData = {
    labels: ['Approved', 'Pending', 'Rejected'],
    datasets: [{
      data: [{{ $statusDistribution['approved'] }}, {{ $statusDistribution['pending'] }}, {{ $statusDistribution['rejected'] }}],
      backgroundColor : ['#28a745', '#ffc107', '#dc3545'],
    }]
  };
  new Chart(statusChartCanvas, {
    type: 'pie',
    data: statusData,
    options: {
      maintainAspectRatio : false,
      responsive : true,
    }
  });
});
</script>
@endpush
@endsection
