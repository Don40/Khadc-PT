<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Registration;

class OtpController extends Controller
{
    // Send OTP
    public function sendOtp(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'mobile' => 'required|regex:/^[0-9]{10}$/',
        'email' => 'required|email',
        'file' => 'required|file|max:2048',
         'monthly_salary' => 'required|numeric',
    ]);

    $formData = $request->only(['name', 'mobile', 'email','monthly_salary']);
    $filePath = $request->file('file')->store('uploads', 'public');

    Session::put('registration_data', $formData);
    Session::put('file_path', $filePath);

    $apiKey = env('2FACTOR_API_KEY');
    $mobile = $formData['mobile'];

    try {
        $response = Http::get("https://2factor.in/API/V1/$apiKey/SMS/$mobile/AUTOGEN");

        if ($response->successful() && isset($response['Details'])) {
            Session::put('session_id', $response['Details']);
            return redirect()->route('otp.verify.form');
        }
    } catch (\Exception $e) {
        \Log::error("OTP sending failed: " . $e->getMessage());
    }

    return back()->with('error', 'OTP sending failed. Please try again.');
}


    // Show OTP input form
    public function showVerifyForm()
    {
        return view('otp-verify');
    }

    // Verify OTP and Save User
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $session_id = Session::get('session_id');
        $apiKey = env('2FACTOR_API_KEY');
        $otp = $request->input('otp');

        $response = Http::get("https://2factor.in/API/V1/$apiKey/SMS/VERIFY/$session_id/$otp");

        if ($response->successful() && $response['Details'] === 'OTP Matched') {
            $data = Session::get('registration_data');
            $filePath = Session::get('file_path');

            // ✅ Define monthly_salary and ten_percent
            $monthlySalary = $data['monthly_salary'];
            $tenPercent = $monthlySalary * 0.10;

            Registration::create([
                'name' => $data['name'],
                'mobile' => $data['mobile'],
                'email' => $data['email'],
                'file_path' => $filePath,
                 'monthly_salary' => $monthlySalary,
                 'ten_percent' => $tenPercent, 
            ]);

            Session::forget(['session_id', 'registration_data', 'file_path']);
             Session::flash('success', 'Registration Successful!');
            return redirect()->route('register.success');
        }

        return back()->with('error', 'Invalid OTP.');
    }
}
