@extends('layouts.blankLayout')

@section('title', 'Verify OTP')

@section('content')
  <div class="container-xxl container-p-y">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card mb-4">
          <h5 class="card-header">Verify OTP</h5>
          <div class="card-body">
            <p>We have sent an OTP code to your email.</p>

            @if($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form action="{{ route('booking.verify-otp') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="otp_code" class="form-label">OTP Code</label>
                <input type="text" class="form-control" id="otp_code" name="otp_code" required placeholder="123456">
              </div>
              <button type="submit" class="btn btn-primary d-grid w-100">Verify</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection