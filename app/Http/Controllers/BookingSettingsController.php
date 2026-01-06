<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingSetting;

class BookingSettingsController extends Controller
{
  public function index()
  {
    $settings = BookingSetting::pluck('value', 'key')->all();
    return view('content.pages.custom-booking', compact('settings'));
  }

  public function update(Request $request)
  {
    $data = $request->validate([
      'open_time' => 'required',
      'close_time' => 'required',
      'slot_duration_minutes' => 'required|integer',
      'auto_approve' => 'nullable',
    ]);

    // Checkbox doesn't send value if unchecked, so we default to 0
    $data['auto_approve'] = $request->has('auto_approve') ? '1' : '0';

    foreach ($data as $key => $value) {
      BookingSetting::updateOrCreate(
        ['key' => $key],
        ['value' => $value]
      );
    }

    return redirect()->back()->with('success', 'Settings updated successfully.');
  }
}
