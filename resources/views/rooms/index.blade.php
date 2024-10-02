@extends('layouts.app')
@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Room List</h2>
        </div>
        <div class="float-end m-2">
            <a class="btn btn-primary" href="{{ route('rooms.create') }}"> Create New Room</a>
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
        <th>Room No</th>
        <th>Room Type</th>
        <th>BathType</th>
        <th>Balcony</th>
        <th>Minibar</th>
        <th>Max occupancy</th>
        <th width="280px">Action</th>
    </tr>
    @if($rooms->isEmpty())
    <tr>
        <td colspan="7" class="text-center">No records found</td>
    </tr>
    @else
    @foreach ($rooms as $room)
    <tr>
        <td>{{ $room->room_no }}</td>
        <td>{{ $room->room_type }}</td>
        <td>
            @if($room->is_bathtub)
            <span class="badge bg-success">Yes</span>
            @else
            <span class="badge bg-danger">No</span>
            @endif
        </td>

        <td>
            @if($room->is_balcony)
            <span class="badge bg-success">Yes</span>
            @else
            <span class="badge bg-danger">No</span>
            @endif
        </td>

        <td>
            @if($room->is_mini_bar)
            <span class="badge bg-success">Yes</span>
            @else
            <span class="badge bg-danger">No</span>
            @endif
        </td>

        <td>{{ $room->max_occupancy }}</td>
        <td>
            <form action="{{ route('rooms.destroy',$room->id) }}" method="POST">
                <a href="{{ route('rooms.show',$room->id) }}"><i class="fa fa-eye"></i></a>
                <a href="{{ route('rooms.edit',$room->id) }}"><i class="fa fa-edit"></i></a>
                @csrf
                @method('DELETE')
                <button type="submit"><i class="fa fa-trash"></i></button>
            </form>
        </td>
    </tr>
    @endforeach
    @endif
</table>

{!! $rooms->links() !!}

@endsection