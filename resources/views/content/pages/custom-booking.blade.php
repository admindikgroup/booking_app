@extends('layouts/contentNavbarLayout')

@section('title', 'Custom Booking Settings')

@section('content')
  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Custom Booking Slots</h5>
    </div>
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      <form method="POST" action="{{ route('booking-settings.update') }}">
        @csrf
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="open_time">Open Time</label>
          <div class="col-sm-10">
            <input type="time" class="form-control" id="open_time" name="open_time"
              value="{{ $settings['open_time'] ?? '10:00' }}" />
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="close_time">Close Time</label>
          <div class="col-sm-10">
            <input type="time" class="form-control" id="close_time" name="close_time"
              value="{{ $settings['close_time'] ?? '22:00' }}" />
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="slot_duration_minutes">Slot Duration</label>
          <div class="col-sm-10">
            <select class="form-select" id="slot_duration_minutes" name="slot_duration_minutes">
              <option value="60" {{ ($settings['slot_duration_minutes'] ?? '') == 60 ? 'selected' : '' }}>1 Hour</option>
              <option value="120" {{ ($settings['slot_duration_minutes'] ?? '') == 120 ? 'selected' : '' }}>2 Hours</option>
              <option value="180" {{ ($settings['slot_duration_minutes'] ?? '') == 180 ? 'selected' : '' }}>3 Hours</option>
            </select>
          </div>
        </div>
        <div class="row justify-content-end">
          <div class="col-sm-10">
            <button type="submit" class="btn btn-primary">Save Settings</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection