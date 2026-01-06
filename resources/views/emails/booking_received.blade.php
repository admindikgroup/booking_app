<!DOCTYPE html>
<html>

<head>
  <title>Booking Confirmation</title>
</head>

<body>
  <h1>Booking Received</h1>
  <p>Dear {{ $booking->name }},</p>
  <p>Your booking has been received by the tenant.</p>
  <p><strong>Details:</strong></p>
  <ul>
    <li>Date: {{ $booking->booking_date }}</li>
    <li>Time: {{ $booking->start_time }} - {{ $booking->end_time }}</li>
  </ul>
  <p>Thank you!</p>
</body>

</html>