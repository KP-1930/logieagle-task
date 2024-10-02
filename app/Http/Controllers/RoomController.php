<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = Room::paginate(5);
        return view('rooms.index',compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_no' => 'required|unique:rooms',
            'room_type' => 'required',
            'max_occupancy' => 'required',
        ]);
        $data = $request->all();
        $data['is_bathtub'] = $request->has('is_bathtub');    
        $data['is_balcony'] = $request->has('is_balcony');    
        $data['is_mini_bar'] = $request->has('is_mini_bar');    
        Room::create($data);
        return redirect()->route('rooms.index')
            ->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.show',compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.edit',compact('room'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $request->validate([
            'room_no' => 'required|unique:rooms,room_no,' . $room->id, // Ensure the room_no is unique except for the current room
            'room_type' => 'required',
            'max_occupancy' => 'required',
        ]);
    
        $data = $request->all();
        $data['is_bathtub'] = $request->has('is_bathtub');    
        $data['is_balcony'] = $request->has('is_balcony');    
        $data['is_mini_bar'] = $request->has('is_mini_bar');    
    
        $room->update($data);    
        return redirect()->route('rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully');
    }
}
