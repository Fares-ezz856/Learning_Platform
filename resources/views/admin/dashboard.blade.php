@extends('layout.app')
@section('title')
    Dashboard
@endsection
@section('body')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Admin Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Admin Dashboard</li>
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
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Instructors</span>
                <span class="info-box-number">
                  {{ $instructor }}
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Students</span>
                <span class="info-box-number">{{ $student }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Courses</span>
                <span class="info-box-number">{{ $course }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Pending Courses</span>
                <span class="info-box-number">{{ $pending_course }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- First row of charts -->
        <div class="row mb-3">
          <div class="col-md-8">
            <!-- Student Registrations Chart -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Student Registration Trends</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="registrationChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
            </div>
          </div>
          <!-- /.col -->

          <div class="col-md-4">
            <!-- Course Distribution Chart -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Course Status Distribution</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas id="courseDistributionChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Second row of charts -->
        <div class="row">
          <div class="col-md-8">
            <!-- Enrollment Activity Chart -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Enrollment Activity</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="enrollmentChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
            </div>
          </div>
          <!-- /.col -->

          <div class="col-md-4">
            <!-- Instructor Trends Chart -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Instructor Trends</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas id="instructorChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

@push('scripts')
<script>
  $(function () {
    // 1. Student Registration Trends Line Chart
    var registrationChartCanvas = $('#registrationChart').get(0).getContext('2d')
    var registrationChartData = {
      labels  : @json($registrationLabels),
      datasets: [
        {
          label               : 'New Students',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : @json($registrationData)
        }
      ]
    }

    var registrationChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          },
          ticks: {
            padding: 10
          }
        }],
        yAxes: [{
          gridLines : {
            display : true,
            color: '#f4f4f4',
            zeroLineColor: '#f4f4f4'
          },
          ticks: {
            beginAtZero: true,
            precision: 0,
            maxTicksLimit: 7,
            padding: 10
          }
        }]
      }
    }

    new Chart(registrationChartCanvas, {
      type: 'line',
      data: registrationChartData,
      options: registrationChartOptions
    })

    // 2. Course Distribution Doughnut Chart
    var courseChartCanvas = $('#courseDistributionChart').get(0).getContext('2d')
    var courseData        = {
      labels: [
          'Approved',
          'Pending',
          'Rejected',
      ],
      datasets: [
        {
          data: [@json($courseDistribution['approved']), @json($courseDistribution['pending']), @json($courseDistribution['rejected'])],
          backgroundColor : ['#28a745', '#ffc107', '#dc3545'],
        }
      ]
    }
    var courseOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    new Chart(courseChartCanvas, {
      type: 'doughnut',
      data: courseData,
      options: courseOptions
    })

    // 3. Enrollment Activity Line Chart
    var enrollmentChartCanvas = $('#enrollmentChart').get(0).getContext('2d')
    var enrollmentChartData = {
      labels  : @json($registrationLabels),
      datasets: [
        {
          label               : 'Enrollments',
          backgroundColor     : 'rgba(40, 167, 69, 0.2)',
          borderColor         : 'rgba(40, 167, 69, 1)',
          borderWidth         : 2,
          pointRadius         : 3,
          pointBackgroundColor: 'rgba(40, 167, 69, 1)',
          data                : @json($enrollmentData)
        }
      ]
    }
    new Chart(enrollmentChartCanvas, {
      type: 'line',
      data: enrollmentChartData,
      options: registrationChartOptions // Reuse same clean scaling options
    })

    // 4. Instructor Trends Bar Chart
    var instructorChartCanvas = $('#instructorChart').get(0).getContext('2d')
    var instructorChartData = {
      labels  : @json($registrationLabels),
      datasets: [
        {
          label               : 'New Instructors',
          backgroundColor     : '#17a2b8',
          data                : @json($instructorData)
        }
      ]
    }
    new Chart(instructorChartCanvas, {
      type: 'bar',
      data: instructorChartData,
      options: {
        maintainAspectRatio : false,
        responsive : true,
        legend: { display: false },
        scales: {
          yAxes: [{ 
            ticks: { 
              beginAtZero: true, 
              precision: 0,
              maxTicksLimit: 7 
            } 
          }]
        }
      }
    })
  })
</script>
@endpush
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection
