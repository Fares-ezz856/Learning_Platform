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
          <div class="col-md-7">
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
                      <th>Title</th>
                      <th>Instructor</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($enrolled_courses as $course)
                    <tr>
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
                        <button type="button" class="btn btn-sm btn-info contact-instructor-btn" 
                                data-instructor-id="{{ $course->instructor_id }}" 
                                data-instructor-name="{{ $course->instructor->name }}">
                          Contact
                        </button>
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
          <div class="col-md-5">
             <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Available Lessons</h3>
                <div class="card-tools">
                   <span class="badge badge-info">{{ $lessons->count() }} Recent</span>
                </div>
              </div>
              <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                  @foreach($lessons as $lesson)
                  <li class="item">
                    <div class="product-img">
                      <span class="badge bg-primary p-2">
                        @if($lesson->content_type == 'video')
                          <i class="fas fa-play"></i>
                        @elseif($lesson->content_type == 'pdf')
                          <i class="fas fa-file-pdf"></i>
                        @else
                          <i class="fas fa-file-alt"></i>
                        @endif
                      </span>
                    </div>
                    <div class="product-info">
                      <a href="{{ route('student.courses.lessons', $lesson->course_id) }}" class="product-title">{{ $lesson->title }}
                        <span class="badge badge-success float-right">View</span></a>
                      <span class="product-description">
                        Course: {{ $lesson->course->title }}
                      </span>
                    </div>
                  </li>
                  @endforeach
                </ul>
                @if($lessons->isEmpty())
                <div class="p-4 text-center text-muted">
                  <p>No lessons available yet. Join courses to see lessons!</p>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="row">
           <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Enrollment Status Overview</h3>
                 <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                     <p class="text-center">
                      <strong>Course Status Distribution</strong>
                    </p>
                    <div class="chart-responsive">
                      <canvas id="statusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <ul class="chart-legend clearfix">
                      <li><i class="far fa-circle text-success"></i> Approved</li>
                      <li><i class="far fa-circle text-warning"></i> Pending</li>
                      <li><i class="far fa-circle text-danger"></i> Rejected</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{-- Contact Form --}}
        <div class="row mt-4">
          <div class="col-md-6">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-envelope mr-2"></i>Contact Us</h3>
              </div>
              <form action="{{ route('contact') }}" method="POST">
                @csrf
                <div class="card-body">
                  @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <h5><i class="icon fas fa-check"></i> Success!</h5>
                      {{ session('success') }}
                    </div>
                  @endif

                  <input type="hidden" name="instructor_id" id="contact_instructor_id">
                  <div id="instructor-info-alert" class="alert alert-info d-none">
                      Contacting Instructor: <strong id="selected-instructor-name"></strong>
                      <button type="button" class="close" id="clear-instructor-selection">&times;</button>
                  </div>

                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ $student->name }}" required>
                  </div>
                  <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" name="email" class="form-control" id="email" value="{{ $student->email }}" required>
                  </div>
                  <div class="form-group">
                    <label for="phone">Phone (Optional)</label>
                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter phone number">
                  </div>
                  <div class="form-group">
                    <label for="message">Message</label>
                    <textarea name="message" class="form-control" id="message" rows="4" placeholder="How can we help you?" required></textarea>
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane mr-1"></i> Send Message
                  </button>
                </div>
              </form>
            </div>
          </div>
          
          {{-- Quick Support Info --}}
          <div class="col-md-6">
            <div class="card card-info card-outline">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Quick Support</h3>
              </div>
              <div class="card-body">
                <p>Have questions about your courses, payments, or the platform? Our support team is here to help you.</p>
                <ul class="list-unstyled">
                  <li class="mb-2"><i class="fas fa-clock mr-2 text-info"></i> Response time: Within 24 hours</li>
                  <li class="mb-2"><i class="fas fa-headset mr-2 text-info"></i> Available: Mon - Fri (9 AM - 6 PM)</li>
                </ul>
                <div class="alert alert-light border">
                  <small class="text-muted">For immediate automated assistance, you can also use our <strong>AI Study Assistant</strong> powered by Gemini just below this section!</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- AI Study Assistant --}}
        <div class="row mt-4">
          <div class="col-12">
            <div class="card card-outline card-info collapsed-card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-robot mr-2"></i>AI Study Assistant
                  <small class="text-muted ml-2">Powered by Gemini</small>
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body" style="display: none;">
                {{-- Chat Messages Area --}}
                <div id="ai-chat-messages" style="height: 350px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; background: #f4f6f9; margin-bottom: 15px;">
                  <div class="ai-message">
                    <div class="d-flex align-items-start mb-3">
                      <div class="mr-2">
                        <span class="badge badge-info p-2"><i class="fas fa-robot"></i></span>
                      </div>
                      <div class="bg-white p-3 rounded shadow-sm" style="max-width: 85%;">
                        <p class="mb-0">Hello <strong>{{ $student->name }}</strong>! 👋 I'm your AI study assistant. Ask me anything about your courses, study tips, or any concept you need help with!</p>
                      </div>
                    </div>
                  </div>
                </div>

                {{-- Quick Suggestions --}}
                <div class="mb-3" id="ai-suggestions">
                  <small class="text-muted d-block mb-2">Quick questions:</small>
                  <button class="btn btn-sm btn-outline-info mr-1 mb-1 ai-suggestion" data-prompt="Give me study tips for my courses">📚 Study Tips</button>
                  <button class="btn btn-sm btn-outline-info mr-1 mb-1 ai-suggestion" data-prompt="Create a study plan for this week">📅 Study Plan</button>
                  <button class="btn btn-sm btn-outline-info mr-1 mb-1 ai-suggestion" data-prompt="Explain a key concept from my courses in simple terms">💡 Explain a Concept</button>
                  <button class="btn btn-sm btn-outline-info mr-1 mb-1 ai-suggestion" data-prompt="How can I stay motivated while studying?">🎯 Stay Motivated</button>
                </div>

                {{-- Input Area --}}
                <form id="ai-chat-form">
                  @csrf
                  <div class="input-group">
                    <input type="text" id="ai-prompt" class="form-control" placeholder="Ask me anything about your studies..." maxlength="2000" autocomplete="off">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-info" id="ai-send-btn">
                        <i class="fas fa-paper-plane"></i> Ask
                      </button>
                    </div>
                  </div>
                </form>
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
  // Status Chart
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

  // AI Chat functionality
  var $chatMessages = $('#ai-chat-messages');
  var $form = $('#ai-chat-form');
  var $prompt = $('#ai-prompt');
  var $sendBtn = $('#ai-send-btn');
  var isWaiting = false;

  function scrollToBottom() {
    $chatMessages.scrollTop($chatMessages[0].scrollHeight);
  }

  function addUserMessage(text) {
    var html = '<div class="d-flex justify-content-end mb-3">' +
      '<div class="bg-info text-white p-3 rounded shadow-sm" style="max-width: 85%;">' +
      '<p class="mb-0">' + escapeHtml(text) + '</p>' +
      '</div>' +
      '<div class="ml-2"><span class="badge badge-secondary p-2"><i class="fas fa-user"></i></span></div>' +
      '</div>';
    $chatMessages.append(html);
    scrollToBottom();
  }

  function addAIMessage(text) {
    // Basic formatting: convert **bold**, *italic*, and newlines
    var formatted = escapeHtml(text)
      .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
      .replace(/\*(.*?)\*/g, '<em>$1</em>')
      .replace(/\n/g, '<br>');

    var html = '<div class="d-flex align-items-start mb-3">' +
      '<div class="mr-2"><span class="badge badge-info p-2"><i class="fas fa-robot"></i></span></div>' +
      '<div class="bg-white p-3 rounded shadow-sm" style="max-width: 85%;">' +
      '<p class="mb-0">' + formatted + '</p>' +
      '</div></div>';
    $chatMessages.append(html);
    scrollToBottom();
  }

  function showTypingIndicator() {
    var html = '<div class="d-flex align-items-start mb-3" id="typing-indicator">' +
      '<div class="mr-2"><span class="badge badge-info p-2"><i class="fas fa-robot"></i></span></div>' +
      '<div class="bg-white p-3 rounded shadow-sm">' +
      '<p class="mb-0 text-muted"><i class="fas fa-circle-notch fa-spin mr-1"></i> Thinking...</p>' +
      '</div></div>';
    $chatMessages.append(html);
    scrollToBottom();
  }

  function removeTypingIndicator() {
    $('#typing-indicator').remove();
  }

  function escapeHtml(text) {
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
  }

  function sendMessage(promptText) {
    if (isWaiting || !promptText.trim()) return;

    isWaiting = true;
    $sendBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
    $prompt.prop('disabled', true);

    addUserMessage(promptText);
    showTypingIndicator();

    $.ajax({
      url: '{{ route("student.ask-ai") }}',
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        prompt: promptText
      },
      success: function(response) {
        removeTypingIndicator();
        if (response.success) {
          addAIMessage(response.answer);
        } else {
          addAIMessage('Sorry, something went wrong. Please try again.');
        }
      },
      error: function() {
        removeTypingIndicator();
        addAIMessage('Sorry, I could not process your request right now. Please try again later.');
      },
      complete: function() {
        isWaiting = false;
        $sendBtn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Ask');
        $prompt.prop('disabled', false).val('').focus();
      }
    });
  }

  $form.on('submit', function(e) {
    e.preventDefault();
    sendMessage($prompt.val());
  });

  // Quick suggestion buttons
  $('.ai-suggestion').on('click', function() {
    var promptText = $(this).data('prompt');
    $prompt.val(promptText);
    sendMessage(promptText);
  });

  // Contact Instructor Button Logic
  $('.contact-instructor-btn').on('click', function() {
    var instructorId = $(this).data('instructor-id');
    var instructorName = $(this).data('instructor-name');
    
    $('#contact_instructor_id').val(instructorId);
    $('#selected-instructor-name').text(instructorName);
    $('#instructor-info-alert').removeClass('d-none');
    
    // Scroll to contact form
    $('html, body').animate({
      scrollTop: $("#name").offset().top - 100
    }, 500);
  });

  $('#clear-instructor-selection').on('click', function() {
    $('#contact_instructor_id').val('');
    $('#instructor-info-alert').addClass('d-none');
  });
});
</script>
@endpush
@endsection
