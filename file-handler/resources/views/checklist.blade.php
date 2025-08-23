@extends('layout')

@section('title', 'Checklist Upload')

@section('content')
    <div class="container">
        <h2>Checklist for Cooperative: {{ $cooperative->name }}</h2>
        <p>Program: {{ $cooperative->program->name }}</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @foreach($checklistItems as $item)
            <div class="card my-3 p-3">
                <h5>{{ $item->name }}</h5>

                {{-- Upload form --}}
                <form action="{{ route('checklist.upload', $cooperative->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="checklist_item_id" value="{{ $item->id }}">
                    <input type="file" name="file" required>
                    <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                </form>


                {{-- If already uploaded, show download --}}
                @if($item->upload)
                    <a href="{{ route('checklist.download', $item->upload->id) }}" class="btn btn-success btn-sm mt-2">
                        Download {{ $item->upload->file_name }}
                    </a>
                @endif
            </div>
        @endforeach


        <a href="{{ route('uploads.search', $cooperative->id) }}" class="btn btn-secondary mt-3">
            View All Uploaded Files
        </a>
    </div>
@endsection