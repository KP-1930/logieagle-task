<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomRent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BookingController extends Controller
{
    public function create()
    {
        $roomTypes = ['Deluxe', 'Luxury', 'Royal'];
        $amenities = ['Bathtub', 'Balcony', 'Mini bar'];

        return view('booking.create', compact('roomTypes', 'amenities'));
    }

    public function availableRooms(Request $request)
    {

        $rooms = Room::where('room_type', $request->room_type)
            ->when($request->is_bathtub, fn($query) => $query->where('is_bathtub', true))
            ->when($request->is_balcony, fn($query) => $query->where('is_balcony', true))
            ->when($request->is_mini_bar, fn($query) => $query->where('is_mini_bar', true))
            ->when(Room::has('bookings')->exists(), function ($query) use ($request) {
                $query->whereDoesntHave('bookings', function ($query) use ($request) {
                    $query->where(function ($query) use ($request) {
                        $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                            ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                            ->orWhere(function ($query) use ($request) {
                                $query->where('start_date', '<=', $request->start_date)
                                    ->where('end_date', '>=', $request->end_date);
                            });
                    });
                });
            })
            ->get();

        // dd($rooms);

        return response()->json($rooms);
    }

    public function calculateCost(Request $request)
    {
        $roomId = $request->input('room_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $room = Room::find($roomId);
        // dd($room);
        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        // Calculate the number of days for the stay
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = $end->diffInDays($start);

        // dd($startDate,$endDate,$days);
        $roomRent = [
            'room_id' => $roomId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'room_rent' => 1000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        $roomRent =  DB::table('room_rents')->insert($roomRent);
        $data = DB::table('room_rents')->latest()->first();
        $RentPerDay = $data->room_rent;

        $baseCost = $RentPerDay * $days;

        // Return total cost
        return response()->json([
            'total_cost' => $baseCost
        ]);
    }


    public function store(Request $request)
    {

        $request->validate([
            'email' => 'required|unique:users'
        ]);

        // Store customer details
        $hashPwd = Hash::make($request->password);
        $phone = $request->phone;
        $customer = User::create(array_merge(
            $request->only('name', 'email'),
            ['password' => $hashPwd, 'phone' => $phone]
        ));

        // Calculate cost based on room and stay duration
        $roomRent = RoomRent::where('room_id', $request->room_id)
            ->where('start_date', '<=', $request->start_date)
            ->where('end_date', '>=', $request->end_date)
            ->first();

        $days = (new \DateTime($request->end_date))->diff(new \DateTime($request->start_date))->days;
        // $totalCost = $roomRent->room_rent * $days;
        $data = DB::table('room_rents')->latest()->first();
        $cus = DB::table('users')->latest()->first();
        $RentPerDay = $data->room_rent;

        $totalCost = $RentPerDay * $days;

        // Store booking
        Booking::create([
            'user_id' => $cus->id,
            'room_id' => $request->room_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_cost' => $totalCost,
        ]);

        return redirect()->route('booking.list')->with('success', 'Booking has been successfully created!');
    }

    public function bookingList()
    {
        $bookings = Booking::with(['getUserName','getRoomId'])->paginate(5);
        return view('booking.index',compact('bookings'));
    }
}
