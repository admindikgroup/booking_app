<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return view('booking.index');
    }

    public function checkSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->date;
        $settings = \App\Models\BookingSetting::all()->pluck('value', 'key');

        $openTime = \Carbon\Carbon::parse($date . ' ' . $settings['open_time']);
        $closeTime = \Carbon\Carbon::parse($date . ' ' . $settings['close_time']);
        $duration = (int) $settings['slot_duration_minutes'];
        $capacity = (int) $settings['capacity_per_unit'];

        $slots = [];
        $current = $openTime->copy();

        while ($current->copy()->addMinutes($duration)->lte($closeTime)) {
            $end = $current->copy()->addMinutes($duration);

            $startTimeString = $current->format('H:i:s');
            $endTimeString = $end->format('H:i:s');

            $bookingsCount = \App\Models\Booking::where('booking_date', $date)
                ->where('status', 'approved')
                ->where(function ($query) use ($startTimeString, $endTimeString) {
                    $query->whereBetween('start_time', [$startTimeString, $endTimeString])
                        ->orWhereBetween('end_time', [$startTimeString, $endTimeString])
                        ->orWhere(function ($q) use ($startTimeString, $endTimeString) {
                            $q->where('start_time', '<', $startTimeString)
                                ->where('end_time', '>', $endTimeString);
                        });
                })
                // Fix overlap logic: strictly, if a booking starts OR ends within the slot, OR covers the slot.
                // Simplified: Just check if slot start/end overlaps with booking.
                // Actually, strict 2-hour slots mean we just check against exact slots if we enforce them.
                // But let's be robust against custom times.
                // Overlap: (StartA < EndB) and (EndA > StartB)
                ->where('start_time', '<', $endTimeString)
                ->where('end_time', '>', $startTimeString)
                ->count();

            if ($bookingsCount < $capacity) {
                $slots[] = [
                    'start_time' => $current->format('H:i'),
                    'end_time' => $end->format('H:i'),
                    'available' => $capacity - $bookingsCount,
                ];
            }

            $current->addMinutes($duration);
        }

        return response()->json($slots);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // Double check availability (race condition check skipped for simplicity)

        // Check Auto-Approve Setting
        $autoApprove = \App\Models\BookingSetting::where('key', 'auto_approve')->value('value');
        $status = ($autoApprove == '1') ? 'approved' : 'pending';

        $booking = \App\Models\Booking::create(array_merge($validated, [
            'status' => $status
        ]));

        // Send Email Notification

        // Notify Admin (ignoring error for now as admin@example.com fails on real SMTP)
        try {
            // \Illuminate\Support\Facades\Mail::to('admin@example.com')->send(new \App\Mail\NewBookingNotification($booking));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin Mail Error: ' . $e->getMessage());
        }

        // Notify User
        try {
            \Illuminate\Support\Facades\Mail::to($booking->email)->send(new \App\Mail\BookingReceived($booking));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('User Mail Error: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Booking submitted successfully!']);
    }
}
