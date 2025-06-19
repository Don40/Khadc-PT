@extends('layout')

@section('content')
{{-- <form method="POST" enctype="multipart/form-data" action="{{ route('otp.send') }}"> --}}
    <form method="POST" enctype="multipart/form-data" action="{{ route('register.submit') }}">

    @csrf
    <h3>KHADC Professional Tax Registration Form</h3>

    <!-- Logo Image -->
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ asset('images/khadc.jpg') }}" alt="Logo" style="max-width: 100%;">
        <a href="{{ route('registrations.viewonly') }}" class="btn btn-info">View Only List</a>
    </div>

    <div class="form-group">
        <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required>
    </div>

    <div class="form-group">
        <input type="text" name="mobile" placeholder="Mobile" value="{{ old('mobile') }}" required>
    </div>

    <div class="form-group">
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
    </div>

    <!-- Monthly Salary Input -->
   <div class="mb-3">
    <label for="monthly_salary" class="form-label fw-semibold">Monthly Salary</label>
    <input 
        type="number" 
        name="monthly_salary" 
        id="monthly_salary" 
        class="form-control form-control-lg" 
        {{-- step="50000"  --}}
        placeholder="Enter monthly salary" 
        required
        style="height: 30px; font-size: 1.2rem;"
    >
</div>



    <!-- 10% Calculation Display (Read-only) -->
    <div class="form-group">
        <input type="text" id="ten_percent_salary" placeholder="10% of Salary (Auto-Calculated)" readonly>
    </div>

    <div class="form-group">
        <input type="file" name="file" required>
    </div>

    <div class="form-group">
        <input type="submit" value="Register"> <!-- Changed from "Send OTP" -->
    </div>

    <div class="message">
        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        @foreach ($errors->all() as $error)
            <div class="error">{{ $error }}</div>
        @endforeach
    </div>
</form>

<!-- Live 10% Calculation Script -->
<script>
    const salaryInput = document.getElementById('monthly_salary');
    const tenPercentOutput = document.getElementById('ten_percent_salary');

    salaryInput.addEventListener('input', () => {
        const salary = parseFloat(salaryInput.value) || 0;
        tenPercentOutput.value = (salary * 0.10).toFixed(2);
    });
</script>
@endsection
