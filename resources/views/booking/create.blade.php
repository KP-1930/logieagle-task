@extends('layouts.app')

@section('content')

<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('booking.store') }}" class="row g-3">
        @csrf
        <!-- Customer Details -->
        <h4 class="mb-3">Customer Details</h4>
        <div class="col-md-4">
            <label for="name" class="form-label">Customer Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Customer Name" value="{{ old('name') }}" required>
        </div>
        <div class="col-md-4">
            <label for="email" class="form-label">Customer Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Customer Email" value="{{ old('email') }}" required>
        </div>
        <div class="col-md-4">
            <label for="phone" class="form-label">Customer Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" placeholder="Customer Phone" value="{{ old('phone') }}" required>
        </div>

        <!-- Stay Dates -->
        <h4 class="mt-4 mb-3">Stay Details</h4>
        <div class="col-md-6">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}" required>
        </div>
        <div class="col-md-6">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}" required>
        </div>

        <!-- Room Type and Amenities -->
        <h4 class="mt-4 mb-3">Room Preferences</h4>
        <div class="col-md-6">
            <label for="room_type" class="form-label">Room Type</label>
            <select name="room_type" id="room_type" class="form-select" required>
                @foreach($roomTypes as $type)
                <option value="{{ $type }}" {{ old('room_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label d-block">Amenities</label>
            <div class="form-check form-check-inline">
                <input type="checkbox" name="is_bathtub" id="bathtub" class="form-check-input" {{ old('is_bathtub') ? 'checked' : '' }}>
                <label for="bathtub" class="form-check-label">Bathtub</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" name="is_balcony" id="balcony" class="form-check-input" {{ old('is_balcony') ? 'checked' : '' }}>
                <label for="balcony" class="form-check-label">Balcony</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" name="is_mini_bar" id="mini_bar" class="form-check-input" {{ old('is_mini_bar') ? 'checked' : '' }}>
                <label for="mini_bar" class="form-check-label">Mini Bar</label>
            </div>
        </div>
        <input type="hidden" name="password" value="123456">

        <!-- Vacant Rooms -->
        <h4 class="mt-4 mb-3">Available Rooms</h4>
        <div class="col-md-6">
            <label for="available_rooms" class="form-label">Select Room</label>
            <select name="room_id" id="available_rooms" class="form-select" required>
                <!-- Rooms will be loaded dynamically via AJAX -->
            </select>
        </div>

        <!-- Cost of Stay -->
        <h4 class="mt-4 mb-3">Total Cost</h4>
        <div class="col-md-6">
            <p id="total_cost" class="form-control-plaintext">Select a room to calculate cost</p>
        </div>

        <div class="col-12 mt-4">
            <button type="submit" class="btn btn-primary">Book Room</button>
        </div>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#room_type, #start_date, #end_date, #bathtub, #balcony, #mini_bar').on('change', function() {
            let roomType = $('#room_type').val();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            let amenities = {
                bathtub: $('#bathtub').is(':checked'),
                balcony: $('#balcony').is(':checked'),
                mini_bar: $('#mini_bar').is(':checked'),
            };

            if (roomType && startDate && endDate) {
                $.ajax({
                    url: '{{ route("booking.availableRooms") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        room_type: roomType,
                        start_date: startDate,
                        end_date: endDate,
                        bathtub: amenities.bathtub,
                        balcony: amenities.balcony,
                        mini_bar: amenities.mini_bar,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        let availableRooms = $('#available_rooms');
                        availableRooms.empty();
                        if (data.length > 0) {
                            data.forEach(room => {
                                availableRooms.append(`<option value="${room.id}">Room No: ${room.room_no}</option>`);
                            });
                        } else {
                            availableRooms.append('<option>No rooms available</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", error);
                    }
                });
            }
        });

        $(document).on('change', '#available_rooms', function() {
            let roomId = $(this).val();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();

            if (roomId && startDate && endDate) {
                $.ajax({
                    url: '{{ route("booking.calculateCost") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        room_id: roomId,
                        start_date: startDate,
                        end_date: endDate,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.total_cost) {
                            $('#total_cost').text('Total Cost: INR' + data.total_cost);
                        } else {
                            $('#total_cost').text('Unable to calculate cost');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", error);
                        $('#total_cost').text('Error calculating cost');
                    }
                });
            }
        });
    });
</script>

@endsection