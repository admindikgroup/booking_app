<!DOCTYPE html>
<html>

<head>
  <title>New Booking Notification</title>
</head>

<body>
  <h1>New Booking Received</h1>
  <p><strong>Name:</strong> {{ $booking->name }}</p>
  <p><strong>Email:</strong> {{ $booking->email }}</p>
  <p><strong>Phone:</strong> {{ $booking->phone }}</p>
  <p><strong>Date:</strong> {{ $booking->booking_date }}</p>
  <p><strong>Time:</strong> {{ $booking->start_time }} - {{ $booking->end_time }}</p>
  <p><strong>Notes:</strong> {{ $booking->notes }}</p>

  <p><a href="{{ route('admin.index') }}">Manage Bookings</a></p>
</body>

</html>