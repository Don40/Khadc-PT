@extends('layout')

@section('content')
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('css/style1.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

    @auth
    <div style="display: flex; flex-direction: column; align-items: center;">
        <!-- Logout Button -->
        <form action="/logout" method="POST" style="align-self: flex-end; margin: 10px;">
            @csrf
            <button>Log out</button>
        </form>

        <!-- Registration Form -->
        <form method="POST" enctype="multipart/form-data" action="{{ route('register.submit') }}">
            @csrf
            <h3>KHADC Professional Tax Registration Form</h3>

            <!-- Logo and View Link -->
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="{{ asset('images/khadc.jpg') }}" alt="Logo" style="max-width: 100%;">
                <a href="{{ route('registrations.viewown') }}" class="btn btn-info">View Employee/Enterprise</a>
            </div>

            <!-- Logged-in User Info -->
           @foreach ($registrations as $registration)
            @if ($loop->first)
                <h5>User ID: {{ $registration['user_id'] }} of {{ $registration->user->name }}</h5>
            @endif
          @endforeach


            <!-- Form Inputs -->
            <div class="form-group">
                <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <input type="text" name="mobile" placeholder="Mobile" value="{{ old('mobile') }}" required>
            </div>

            <div class="form-group">
                <input  type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label for="monthly_salary" class="form-label fw-semibold">Monthly Salary</label>
                <input type="number" name="monthly_salary"id="monthly_salary" class="form-control form-control-lg" 
                placeholder="Enter monthly salary" required style="height: 30px; font-size: 1.2rem;">
            </div>

            <div class="form-group">
                <input type="text" id="ten_percent_salary" placeholder="10% of Salary (Auto-Calculated)" readonly>
            </div>

            <div class="form-group">
                <input type="file" name="file" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Register">
            </div>

            <!-- Feedback Messages -->
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

        <!-- Salary Script -->
        <script>
            const salaryInput = document.getElementById('monthly_salary');
            const tenPercentOutput = document.getElementById('ten_percent_salary');
            salaryInput.addEventListener('input', () => {
                const salary = parseFloat(salaryInput.value) || 0;
                tenPercentOutput.value = (salary * 0.10).toFixed(2);
            });
        </script>
    </div>
    @else
    <!-- Guest View -->
    <div style="display: flex; flex-direction: column; align-items: center;">
       
         @if ($errors->any())
                  <div class="alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                  </div>
          @endif
    
       
        <div class="auth-box">
            <h3 class="login-title">Register as Employee/Enterprise</h3>
            <form action="/register" method="POST">
                @csrf
                <input name="name" type="text" placeholder="name">
                <input name="email" type="text" placeholder="email">
                <input name="password" type="password" placeholder="password">
                <button>Register</button>
            </form>
        </div>

        <div class="auth-box">
        <h3 class="login-title">Login as Employee/Enterprise</h3>
            <form action="/login" method="POST">
                @csrf
                <input name="loginname" type="text" placeholder="name">
                <input name="loginpassword" type="password" placeholder="password">
                <button>Log in</button>
            </form>
        </div>
    </div>
    @endauth
</body>
</html>
@endsection
