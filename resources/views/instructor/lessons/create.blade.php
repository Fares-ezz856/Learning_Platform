@extends('layout.app')

@section('title', 'Add Lesson')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add New Lesson</h1>
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

            <div class="card card-purple card-outline">
                <form action="{{ route('instructor.createlessons') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="course_id">Course</label>
                            <select name="course_id" id="course_id" class="form-control @error('course_id') is-invalid @enderror" required>
                                <option value="">-- Select Course --</option>
                                @foreach($courses as $course_item)
                                    <option value="{{ $course_item->id }}" {{ old('course_id') == $course_item->id || (isset($course) && $course->id == $course_item->id) ? 'selected' : '' }}>
                                        {{ $course_item->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">

                            <label for="title">Lesson Title</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Enter lesson title" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content_type">Content Type</label>
                            <select name="content_type" id="content_type" class="form-control @error('content_type') is-invalid @enderror" required onchange="toggleContentInput()">
                                <option value="article" {{ old('content_type') == 'article' ? 'selected' : '' }}>Article / Text</option>
                                <option value="video" {{ old('content_type') == 'video' ? 'selected' : '' }}>Video File</option>
                                <option value="pdf" {{ old('content_type') == 'pdf' ? 'selected' : '' }}>PDF Document</option>
                                <option value="image" {{ old('content_type') == 'image' ? 'selected' : '' }}>Image</option>
                            </select>
                            @error('content_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" id="text_content_group">
                            <label for="content_data_text">Article Content</label>
                            <textarea name="content_data" id="content_data_text" class="form-control @error('content_data') is-invalid @enderror" rows="10" placeholder="Enter lesson content...">{{ old('content_data') }}</textarea>
                            @error('content_data')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group d-none" id="file_content_group">
                            <label for="content_data_file">Upload File</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="content_data" class="custom-file-input @error('content_data') is-invalid @enderror" id="content_data_file" disabled>
                                    <label class="custom-file-label" for="content_data_file">Choose file</label>
                                </div>
                                @error('content_data')
                                    <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="text-muted">Max file size depends on your server configuration.</small>
                        </div>

                        <div class="form-group">
                            <label for="order">Sequence / Order</label>
                            <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 1) }}" min="1">
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('instructor.courses.index') }}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-purple" style="background-color: #6f42c1; color: white;">Create Lesson</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    function toggleContentInput() {
        var type = document.getElementById('content_type').value;
        var textGroup = document.getElementById('text_content_group');
        var fileGroup = document.getElementById('file_content_group');
        var textInput = document.getElementById('content_data_text');
        var fileInput = document.getElementById('content_data_file');

        if (type === 'article') {
            textGroup.classList.remove('d-none');
            fileGroup.classList.add('d-none');
            textInput.disabled = false;
            fileInput.disabled = true;
        } else {
            textGroup.classList.add('d-none');
            fileGroup.classList.remove('d-none');
            textInput.disabled = true;
            fileInput.disabled = false;
        }
    }
    
    // Initialize
    toggleContentInput();
</script>
@endpush
@endsection

