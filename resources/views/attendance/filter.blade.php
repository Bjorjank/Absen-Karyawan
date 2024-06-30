@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Filter Attendance</h1>
    <form action="{{ route('attendance.filter') }}" method="GET">
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date">
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date">
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
    @if(isset($attendances))
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->employee->name }}</td>
                        <td>{{ $attendance->check_in }}</td>
                        <td>{{ $attendance->check_out }}</td>
                        <td>{{ $attendance->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
