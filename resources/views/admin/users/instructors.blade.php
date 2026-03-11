@extends('layout.app')

@section('title', 'Instructors View')

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
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif
             @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                     <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">List of all registered instructors</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Joined On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($instructors as $instructor)
                                    <tr>
                                        <td>{{ $instructor->id }}</td>
                                        <td>{{ $instructor->name }}</td>
                                        <td>{{ $instructor->email }}</td>
                                        <td>{{ $instructor->phone ?? 'N/A' }}</td>
                                        <td>{{ $instructor->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <form action="{{ route('admin.instructors.delete', $instructor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this instructor?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($instructors->isEmpty())
                                <p class="text-center mt-3">No instructors found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
