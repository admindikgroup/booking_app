<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $bookings = \App\Models\Booking::latest()->get();
        return view('admin.index', compact('bookings'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $booking = \App\Models\Booking::findOrFail($id);

        if ($request->status == 'approved') {
            // Check capacity again just in case (optional but good)
            $settings = \App\Models\BookingSetting::all()->pluck('value', 'key');
            $capacity = (int) $settings['capacity_per_unit'];

            $bookingsCount = \App\Models\Booking::where('booking_date', $booking->booking_date)
                ->where('status', 'approved')
                ->where('start_time', '<', $booking->end_time)
                ->where('end_time', '>', $booking->start_time)
                ->count();

            if ($bookingsCount >= $capacity) {
                return back()->with('error', 'Capacity full for this slot!');
            }
        }

        $booking->update(['status' => $request->status]);

        // TODO: Send email to user (optional)

        return back()->with('success', 'Booking status updated!');
    }

    public function search(Request $request)
    {
        $bookings = \App\Models\Booking::where('name', 'like', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%')
            ->orWhere('phone', 'like', '%' . $request->search . '%')
            ->orWhere('booking_date', 'like', '%' . $request->search . '%')
            ->orWhere('start_time', 'like', '%' . $request->search . '%')
            ->orWhere('end_time', 'like', '%' . $request->search . '%')
            ->orWhere('status', 'like', '%' . $request->search . '%')
            ->latest()
            ->get();

        return view('admin.index', compact('bookings'));
    }
}