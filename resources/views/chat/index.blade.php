@extends('layout.app')

@section('title', 'Public Chat')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Public Chat Room</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <!-- DIRECT CHAT -->
                    <div class="card direct-chat direct-chat-primary">
                        <div class="card-header">
                            <h3 class="card-title">General Discussion</h3>
                            <div class="card-tools">
                                <span data-toggle="tooltip" title="Latest messages" class="badge badge-primary">{{ count($messages) }}</span>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Conversations are loaded here -->
                            <div class="direct-chat-messages" style="height: 500px;" id="chat-box">
                                @foreach($messages as $msg)
                                    @php
                                        $currentUser = Auth::guard('admin_web')->user() ?? Auth::guard('instructor_web')->user() ?? Auth::guard('student_web')->user();
                                        $isMe = ($msg->sender_id == $currentUser->id && $msg->sender_type == get_class($currentUser));
                                        
                                        $senderName = $msg->sender->name ?? 'Unknown';
                                        $role = 'User';
                                        if ($msg->sender_type == \App\Models\Admin::class) $role = 'Admin';
                                        elseif ($msg->sender_type == \App\Models\Instructor::class) $role = 'Instructor';
                                        elseif ($msg->sender_type == \App\Models\Student::class) $role = 'Student';
                                    @endphp

                                    <!-- Message. Default to the left -->
                                    <div class="direct-chat-msg {{ $isMe ? 'right' : '' }}">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name {{ $isMe ? 'float-right' : 'float-left' }}">{{ $senderName }} <small class="text-muted">({{ $role }})</small></span>
                                            <span class="direct-chat-timestamp {{ $isMe ? 'float-left' : 'float-right' }}">{{ $msg->created_at->format('d M h:i a') }}</span>
                                        </div>
                                        <!-- /.direct-chat-infos -->
                                        <img class="direct-chat-img" src="{{ asset('dist/img/user1-128x128.jpg') }}" alt="message user image">
                                        <!-- /.direct-chat-img -->
                                        <div class="direct-chat-text">
                                            {{ $msg->message }}
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                    <!-- /.direct-chat-msg -->
                                @endforeach
                            </div>
                            <!--/.direct-chat-messages-->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <form action="{{ route('chat.send') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="message" placeholder="Type Message ..." class="form-control" required autocomplete="off">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Send</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-footer-->
                    </div>
                    <!--/.direct-chat -->
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>
@endpush
@endsection
