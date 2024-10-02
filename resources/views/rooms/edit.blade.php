@extends('layouts.app')
@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Room Details</h2>
        </div>
        <div class="float-end">
            <a class="btn btn-primary" href="{{ route('rooms.index') }}"> Back</a>
        </div>
    </div>
</div>

<form action="{{route('rooms.update',$room->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Room No:</strong>
                <input type="text" name="room_no" class="form-control" placeholder="Room Number" value="{{$room->room_no}}">
                @error('room_no')
                <span style='color : red'>{{$message}}</span>
                @enderror
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Room Type:</strong>
                <select name="room_type" class="form-control">
                    @foreach(App\Models\Room::getRoomType() as $type)
                    <option value="{{ $type }}" {{ $room->room_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                @error('room_type')
                <span style='color : red'>{{$message}}</span>
                @enderror
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Amenities:</strong>
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="is_bathtub" id="bathtub" class="form-check-input" {{$room->is_bathtub ? 'checked' : '' }}>
                    <label for="bathtub" class="form-check-label">Bathtub</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="is_balcony" id="balcony" class="form-check-input" {{$room->is_balcony ? 'checked' : '' }}>
                    <label for="balcony" class="form-check-label">Balcony</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="is_mini_bar" id="mini_bar" class="form-check-input" {{$room->is_mini_bar ? 'checked' : '' }}>
                    <label for="mini_bar" class="form-check-label">Mini Bar</label>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Max Occupancy:</strong>
                <input type="number" name="max_occupancy" class="form-control" placeholder="Max Occupancy" value="{{ $room->max_occupancy }}">
                @error('max_occupancy')
                <span style='color : red'>{{$message}}</span>
                @enderror
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 text-center m-2">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>

@endsection