@extends('layouts.app')
@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Show Room Details</h2>
        </div>
        <div class="float-end">
            <a class="btn btn-primary" href="{{ route('rooms.index') }}"> Back</a>
        </div>
    </div>
</div>

<form>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Room No:</strong>
                <input type="text" name="room_no" class="form-control" placeholder="Room Number" value="{{$room->room_no}}" disabled>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Room Type:</strong>
                <select name="room_type" class="form-control" disabled>
                    @foreach(App\Models\Room::getRoomType() as $type)
                    <option value="{{ $type }}" {{ $room->room_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Amenities:</strong>
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="is_bathtub" id="bathtub" class="form-check-input" {{$room->is_bathtub ? 'checked' : '' }} disabled>
                    <label for="bathtub" class="form-check-label">Bathtub</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="is_balcony" id="balcony" class="form-check-input" {{$room->is_balcony ? 'checked' : '' }} disabled>
                    <label for="balcony" class="form-check-label">Balcony</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="is_mini_bar" id="mini_bar" class="form-check-input" {{$room->is_mini_bar ? 'checked' : '' }} disabled>
                    <label for="mini_bar" class="form-check-label">Mini Bar</label>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Max Occupancy:</strong>
                <input type="number" name="max_occupancy" class="form-control" placeholder="Max Occupancy" value="{{ $room->max_occupancy }}" disabled>
            </div>
        </div>
    </div>
</form>

@endsection
