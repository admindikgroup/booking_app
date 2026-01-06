<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingAuthController extends Controller
{
    public function loginForm()
    {
        return view('booking.login');
    }

    public function sendOtp(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        $booking = \App\Models\Booking::where('email', $request->email)
            ->where('phone', $request->phone)
            ->latest()
            ->first();

        if (!$booking) {
            return back()->withErrors(['email' => 'No booking found with these details.']);
        }

        $otp = rand(100000, 999999);
        $booking->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send Email
        try {
            \Illuminate\Support\Facades\Mail::to($booking->email)->send(new \App\Mail\BookingOTP($otp));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('OTP Mail Error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again.']);
        }

        session(['otp_booking_id' => $booking->id]);
        return redirect()->route('booking.verify');
    }

    public function verifyForm()
    {
        if (!session('otp_booking_id')) {
            return redirect()->route('booking.login');
        }
        return view('booking.verify');
    }

    public function verifyOtp(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'otp_code' => 'required|numeric',
        ]);

        $bookingId = session('otp_booking_id');
        if (!$bookingId) {
            return redirect()->route('booking.login');
        }

        $booking = \App\Models\Booking::find($bookingId);

        if (!$booking || $booking->otp_code != $request->otp_code || now()->gt($booking->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Invalid or expired OTP.']);
        }

        // Clear OTP
        $booking->update(['otp_code' => null, 'otp_expires_at' => null]);

        // Login User
        session(['booking_user_id' => $booking->id]);
        session()->forget('otp_booking_id');

        return redirect()->route('booking.dashboard');
    }

    public function dashboard()
    {
        $bookingId = session('booking_user_id');
        if (!$bookingId) {
            return redirect()->route('booking.login');
        }

        $currentBooking = \App\Models\Booking::find($bookingId);

        $bookings = \App\Models\Booking::where('email', $currentBooking->email)
            ->where('phone', $currentBooking->phone)
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('booking.dashboard', compact('bookings'));
    }

    public function logout()
    {
        session()->forget('booking_user_id');
        return redirect()->route('booking.login');
    }
}
