@extends('layout.app')

@section('title', 'Course Lessons')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $course->title }}</h1>
                    <p class="text-muted small">Course Content & Lessons</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.courses.index') }}">My Courses</a></li>
                        <li class="breadcrumb-item active">Lessons</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">{{ $course->title }}</h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="nav flex-column nav-pills p-2" id="curriculum-list">
                                @forelse($course->lessons as $index => $lesson)
                                <li class="nav-item">
                                    <a href="javascript:void(0)" class="nav-link lesson-link {{ $index == 0 ? 'active' : '' }}" 
                                       data-id="{{ $lesson->id }}" 
                                       data-index="{{ $index }}">
                                        <i class="fas {{ $lesson->content_type == 'video' ? 'fa-play-circle' : ($lesson->content_type == 'pdf' ? 'fa-file-pdf' : ($lesson->content_type == 'image' ? 'fa-image' : 'fa-file-alt')) }} mr-2"></i> 
                                        {{ $lesson->title }}
                                    </a>
                                </li>
                                @empty
                                <li class="text-center p-3 text-muted">No lessons uploaded yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    @if($course->lessons->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-play mr-2 text-primary"></i> Current Lesson: <strong id="current-lesson-title">{{ $course->lessons->first()->title }}</strong></h3>
                        </div>
                        <div class="card-body">
                            <div id="lesson-content-container" style="min-height: 400px; border-radius: 8px; overflow: hidden;" class="bg-dark mb-4">
                                {{-- Content will be rendered here by JS --}}
                                <div class="d-flex align-items-center justify-content-center h-100" id="content-loading">
                                    <i class="fas fa-spinner fa-spin fa-3x text-white-50"></i>
                                </div>
                            </div>

                            <div id="lesson-article-content" class="mb-4" style="display: none;">
                                <h5>About this lesson</h5>
                                <div id="article-body"></div>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between">
                                <button id="prev-lesson" class="btn btn-outline-secondary"><i class="fas fa-chevron-left mr-1"></i> Previous</button>
                                <button id="next-lesson" class="btn btn-primary">Next Lesson <i class="fas fa-chevron-right ml-1"></i></button>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="card p-5 text-center bg-light">
                        <i class="fas fa-hourglass-half fa-3x text-muted mb-3"></i>
                        <p class="text-muted">The instructor is still preparing the content for this course. Check back soon!</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
$(function() {
    const lessons = @json($course->lessons);
    const storageBase = "{{ asset('storage') }}";
    let currentIndex = 0;

    function renderLesson(index) {
        if (index < 0 || index >= lessons.length) return;
        
        currentIndex = index;
        const lesson = lessons[index];
        
        // Update UI
        $('#current-lesson-title').text(lesson.title);
        $('.lesson-link').removeClass('active');
        $(`.lesson-link[data-index="${index}"]`).addClass('active');
        
        // Handle Navigation Buttons
        $('#prev-lesson').prop('disabled', index === 0);
        $('#next-lesson').toggle(index < lessons.length - 1);
        
        const container = $('#lesson-content-container');
        const articleSection = $('#lesson-article-content');
        container.empty();
        articleSection.hide();

        if (lesson.content_type === 'video') {
            // Check if it's a URL (YouTube/Vimeo) or a local file
            if (lesson.content_data && (lesson.content_data.includes('youtube.com') || lesson.content_data.includes('youtu.be'))) {
                let videoId = '';
                if (lesson.content_data.includes('v=')) {
                    videoId = lesson.content_data.split('v=')[1].split('&')[0];
                } else {
                    videoId = lesson.content_data.split('/').pop();
                }
                container.html(`<div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/${videoId}" allowfullscreen></iframe>
                </div>`);
            } else {
                const videoUrl = lesson.content_data.startsWith('http') ? lesson.content_data : `${storageBase}/${lesson.content_data}`;
                container.html(`<video width="100%" height="auto" controls style="max-height: 500px; background: #000;" preload="metadata">
                    <source src="${videoUrl}" type="video/mp4">
                    <source src="${videoUrl}" type="video/ogg">
                    <source src="${videoUrl}" type="video/webm">
                    Your browser does not support the video tag.
                </video>`);
                
                // Add a small delay and try to play if they want autoplay, 
                // but usually user interaction is better.
            }
        } else if (lesson.content_type === 'pdf') {
            const pdfUrl = lesson.content_data.startsWith('http') ? lesson.content_data : `${storageBase}/${lesson.content_data}`;
            container.html(`<iframe src="${pdfUrl}" width="100%" height="600px" style="border: none;"></iframe>`);
        } else if (lesson.content_type === 'image') {
            const imgUrl = lesson.content_data.startsWith('http') ? lesson.content_data : `${storageBase}/${lesson.content_data}`;
            container.html(`<div class="text-center p-2"><img src="${imgUrl}" class="img-fluid" style="max-height: 600px; border-radius: 4px;"></div>`);
        } else if (lesson.content_type === 'article') {
            container.addClass('bg-light').html(`<div class="p-4" style="color: #333; font-size: 1.1rem; line-height: 1.6;">${lesson.content_data}</div>`);
        }
    }

    // Initialize first lesson
    if (lessons.length > 0) {
        renderLesson(0);
    }

    // Sidebar click
    $('.lesson-link').on('click', function() {
        renderLesson($(this).data('index'));
    });

    // Next/Prev buttons
    $('#next-lesson').on('click', function() {
        renderLesson(currentIndex + 1);
    });

    $('#prev-lesson').on('click', function() {
        renderLesson(currentIndex - 1);
    });
});
</script>
@endpush
@endsection
