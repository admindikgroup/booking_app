@extends('layouts.blankLayout')

@section('title', 'Booking Dashboard')

@section('content')
  <div class="container-xxl container-p-y">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">My Booking History</h5>
            <form action="{{ route('booking.logout') }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
            </form>
          </div>
          <div class="card-body">
            @if($bookings->isEmpty())
              <p>No bookings found.</p>
            @else
              <div class="list-group">
                @foreach($bookings as $booking)
                  <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                    aria-current="true">
                    <div>
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">{{ $booking->booking_date }} | {{ $booking->start_time }} - {{ $booking->end_time }}
                        </h6>
                      </div>
                      <p class="mb-1"><strong>Status:</strong>
                        <span
                          class="badge bg-{{ $booking->status == 'approved' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'secondary') }}">
                          {{ ucfirst($booking->status) }}
                        </span>
                      </p>
                      <div class="row mb-2">
                        <div class="col-md-12">
                          <small class="text-muted d-block">Name: {{ $booking->name }}</small>
                          <small class="text-muted d-block">Email: {{ $booking->email }}</small>
                          <small class="text-muted d-block">Phone: {{ $booking->phone }}</small>
                        </div>
                      </div>
                      @if($booking->notes)
                        <small>Note: {{ $booking->notes }}</small>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection