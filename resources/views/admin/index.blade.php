@extends('layouts.contentNavbarLayout')

@section('title', 'Admin Dashboard')

@section('content')
  <div class="card">
    <div class="d-flex justify-content-between align-items-center card-header">
      <h5 class="m-0">Bookings</h5>
      <div>
        <!-- Search Form -->
        <form action="{{ route('admin.search') }}" method="GET" class="d-flex gap-2 align-items-center">
          <div class="position-relative">
            <input type="text" name="search" class="form-control pe-5" placeholder="Search bookings..."
              value="{{ request('search') }}">

            @if(request('search'))
              <a href="{{ route('admin.search') }}"
                class="btn btn-sm btn-light position-absolute top-50 end-0 translate-middle-y me-1" title="Reset search"
                style="border: none;">
                &times;
              </a>
            @endif
          </div>
          <button type="submit" class="btn btn-primary">Search</button>
        </form>
      </div>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach($bookings as $booking)
            <tr>
              <td>#{{ $booking->id }}</td>
              <td>
                <strong>{{ $booking->name }}</strong><br>
                <small class="text-muted">{{ $booking->phone }}</small><br>
                <small class="text-muted">{{ $booking->email }}</small>
              </td>
              <td>{{ $booking->booking_date }}</td>
              <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
              <td>
                @if($booking->status == 'pending')
                  <span class="badge bg-label-warning me-1">Pending</span>
                @elseif($booking->status == 'approved')
                  <span class="badge bg-label-success me-1">Approved</span>
                @else
                  <span class="badge bg-label-danger me-1">Rejected</span>
                @endif
              </td>
              <td>
                @if($booking->status == 'pending')
                  <div class="d-flex gap-2">
                    <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="status" value="approved">
                      <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    </form>
                    <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="status" value="rejected">
                      <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                    </form>
                  </div>
                @else
                  <span class="text-muted">Processed</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  @if(session('success'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div class="toast show bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <strong class="me-auto">Success</strong>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          {{ session('success') }}
        </div>
      </div>
    </div>
  @endif

  @if(session('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div class="toast show bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <strong class="me-auto">Error</strong>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          {{ session('error') }}
        </div>
      </div>
    </div>
  @endif

@endsection