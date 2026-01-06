@extends('layouts.blankLayout')

@section('title', 'My Booking')

@section('content')
  <div class="container-xxl container-p-y">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card mb-4">
          <h5 class="card-header">Check Booking Status</h5>
          <div class="card-body">
            @if($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form action="{{ route('booking.send-otp') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
              </div>
              <button type="submit" class="btn btn-primary d-grid w-100">Send OTP</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection