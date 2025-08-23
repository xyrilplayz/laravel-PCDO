@extends('layout')

@section('title', 'Create Cooperative')

@section('content')
<div class="container">
    <h2>Create Cooperative</h2>

    {{-- Display success message --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cooperatives.post') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="program_id">Choose your Availed Program:</label>
            <select class="form-control" name="program_id" id="program_id" required>
                <option value="" disabled selected>-- Select Program --</option>
                <option value="1">USAD</option>
                <option value="2">LICAP</option>
                <option value="3">COPSE</option>
                <option value="4">SULONG</option>
                <option value="5">LIVELIHOOD</option>
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="name">Enter Cooperative Name:</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Enter cooperative name" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Go</button>
    </form>
</div>
@endsection
