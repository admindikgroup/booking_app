@extends('layouts.blankLayout')

@section('title', 'Book a Table')

@section('content')
  <div class="container-xxl container-p-y">
    <div class="row justify-content-center">
      <div class="col-12 text-end mb-3">
        <a href="{{ route('booking.login') }}" class="btn btn-outline-primary">My Booking</a>
      </div>
      <div class="col-md-8">
        <div class="card mb-4">
          <h5 class="card-header">Reservation</h5>
          <div class="card-body">
            <form id="bookingForm" onsubmit="submitBooking(event)">
              @csrf
              <div class="mb-3">
                <label for="booking_date" class="form-label">Select Date</label>
                <input type="date" class="form-control" id="booking_date" name="booking_date" required
                  min="{{ date('Y-m-d') }}" onchange="checkSlots()">
              </div>

              <div id="slotsContainer" class="mb-3" style="display:none;">
                <label class="form-label">Available Time Slots</label>
                <div id="slotsList" class="d-flex flex-wrap gap-2">
                  <!-- Slots will be injected here -->
                </div>
                <input type="hidden" id="start_time" name="start_time" required>
                <input type="hidden" id="end_time" name="end_time" required>
              </div>

              <div id="detailsContainer" style="display:none;">
                <div class="mb-3">
                  <label for="name" class="form-label">Full Name</label>
                  <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email Address</label>
                  <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                  <label for="phone" class="form-label">Phone Number</label>
                  <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="mb-3">
                  <label for="notes" class="form-label">Notes (Optional)</label>
                  <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary d-grid w-100">Confirm Booking</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
      async function checkSlots() {
        const date = document.getElementById('booking_date').value;
        if (!date) return;

        const container = document.getElementById('slotsContainer');
        const list = document.getElementById('slotsList');

        // Reset
        list.innerHTML = 'Loading...';
        container.style.display = 'block';

        try {
          const response = await fetch(`{{ route('booking.check') }}?date=${date}`);
          const slots = await response.json();

          list.innerHTML = '';

          if (slots.length === 0) {
            list.innerHTML = '<span class="text-danger">No slots available for this date.</span>';
            return;
          }

          slots.forEach(slot => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-outline-primary btn-sm';
            btn.textContent = `${slot.start_time} - ${slot.end_time} (${slot.available} left)`;
            btn.onclick = () => selectSlot(btn, slot.start_time, slot.end_time);
            list.appendChild(btn);
          });

        } catch (e) {
          console.error(e);
          list.innerHTML = '<span class="text-danger">Error loading slots.</span>';
        }
      }

      function selectSlot(btn, start, end) {
        // Styling
        document.querySelectorAll('#slotsList button').forEach(b => {
          b.classList.remove('btn-primary');
          b.classList.add('btn-outline-primary');
        });
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-primary');

        // Set values
        document.getElementById('start_time').value = start;
        document.getElementById('end_time').value = end;

        // Show details
        document.getElementById('detailsContainer').style.display = 'block';
      }

      async function submitBooking(e) {
        e.preventDefault();
        const form = document.getElementById('bookingForm');
        const formData = new FormData(form);
        const btn = form.querySelector('button[type="submit"]');

        btn.disabled = true;
        btn.textContent = 'Submitting...';

        try {
          const response = await fetch(`{{ route('booking.store') }}`, {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          });

          const result = await response.json();

          if (result.success) {
            alert(result.message);
            location.reload();
          } else {
            alert('Something went wrong. Please try again.');
          }
        } catch (e) {
          console.error(e);
          alert('Error submitting booking.');
        } finally {
          btn.disabled = false;
          btn.textContent = 'Confirm Booking';
        }
      }
    </script>
  </div>
@endsection