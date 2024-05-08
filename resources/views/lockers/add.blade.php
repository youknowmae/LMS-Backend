@extends('layouts.app')

@section('content')
    <h1>Add Lockers</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="post" action="{{ route('lockers.store') }}">
        @csrf
        <div class="form-group">
            <label for="number_of_lockers">Number of Lockers:</label>
            <input type="number" name="number_of_lockers" id="number_of_lockers" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Lockers</button>
    </form>
@endsection
