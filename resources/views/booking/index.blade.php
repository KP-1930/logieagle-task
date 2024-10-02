@extends('layouts.app')
@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Booking List</h2>
        </div>
        <div class="float-end m-2">
            <a class="btn btn-primary" href="{{ route('booking.create') }}"> Create Customer</a>
        </div>
    </div>

</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<table class="table table-bordered">
    <tr>
        <th>User</th>
        <th>book</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Total Cost</th>
    </tr>
    @if($bookings->isEmpty())
    <tr>
        <td colspan="5" class="text-center">No records found</td>
    </tr>
    @else
    @foreach ($bookings as $book)
    <tr>
        <td>{{ $book->getUserName->name }}</td>
        <td>{{ $book->getRoomId->room_no }}</td>
        <td>{{ $book->start_date }}</td>
        <td>{{ $book->end_date }}</td>
        <td>{{ $book->total_cost }}</td>
    </tr>
    @endforeach
    @endif
</table>

{!! $bookings->links() !!}

@endsection