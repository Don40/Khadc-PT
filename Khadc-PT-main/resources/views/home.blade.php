@extends('layout')

@section('content')
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> 
    {{-- <link rel="stylesheet" href="{{ asset('css/auth.css') }}"> --}}
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

           <!-- Range of Annual Income (Static Dropdown) -->
            <div class="form-group">
                <label for="income_range" class="form-label fw-semibold">Range of Annual Income</label>
                <select id="income_range" name="income_range" class="form-control form-control-lg" required style="height: 40px; font-size: 1rem;">
                    <option value="">Select Income Range</option>
                    <option value="0-50000">Does Not Exceeds ₹50,000</option>
                    <option value="50001-75000"> Exceeds ₹50,000 - ₹75,000</option>
                    <option value="75001-100000">Exceeds ₹75,000 - ₹1,00,000</option>
                    <option value="100001-150000">Exceeds ₹1,00,000 - ₹1,50,000</option>
                    <option value="150001-200000">Exceeds ₹1,50,000 - ₹2,00,000</option>
                    <option value="200001-250000">Exceeds ₹2,00,000 - ₹2,50,000</option>
                    <option value="250001-300000">Exceeds ₹2,50,000 - ₹3,00,000</option>
                    <option value="300001-350000">Exceeds ₹3,00,000 - ₹3,50,000</option>
                    <option value="350001-400000">Exceeds ₹3,50,000 - ₹4,00,000</option>
                    <option value="400001-450000">Exceeds ₹4,00,000 - ₹4,50,000</option>
                    <option value="450001-500000">Exceeds ₹4,50,00 ₹5,00,000</option>
                     <option value="500001">Exceeds ₹5,00,000</option>
                   
                </select>
            </div>

            <!-- Tax Amount (Auto-calculated based on selected range) -->
            <div class="form-group">
            <label for="tax_amount" class="form-label fw-semibold">Tax Amount</label>
                <input type="text" id="tax_amount" name="tax_amount" class="form-control" placeholder="Tax Amount" readonly>
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
             const incomeRange = document.getElementById('income_range');
            const taxAmount = document.getElementById('tax_amount');

            incomeRange.addEventListener('change', () => {
                const range = incomeRange.value;
                let tax = 0;

                switch (range) {
                    case "0-50000":
                        tax = 0;
                        break;
                    case "50001-75000":
                        tax = 200;
                        break;
                    case "75001-100000":
                        tax = 300;
                        break;
                    case "100001-150000":
                        tax = 500;
                        break;
                    case "150001-200000":
                        tax = 750;
                        break;
                   case "200001-250000":
                        tax = 1000;
                        break;
                        case "250001-300000":
                        tax = 1250;
                        break;
                        case "300001-350000":
                        tax = 1500;
                        break;
                        case "350001-400000":
                        tax = 1800;
                        break;
                        case "400001-450000":
                        tax = 2100;
                        break;
                        case "450001-500000":
                        tax = 2400;
                        break;
                        case "500001":
                        tax = 2500;
                        break;
                        
                    default:
                        tax = 0;
                }

                taxAmount.value = `₹${tax.toFixed()}`;
            });
        </script>
           
    </div>
     @else
    <!-- Guest View -->
   <div class="container py-4">
    <!-- Logo Image -->
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ asset('images/khadc.jpg') }}" alt="Logo" style="max-width: 100%;">
    </div>

    <!-- Registration for Guest -->
    <div class="auth-box">
        <h3 class="login-title">Register as Employee/Enterprise</h3>
        <form action="/register" method="POST">
            @csrf
            <input name="name" type="text" placeholder="Name" value="{{ old('name') }}">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror

            <input name="email" type="text" placeholder="Email" value="{{ old('email') }}">
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror

            <input name="password" type="password" placeholder="Password">
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror

            <button>Register</button>
        </form>
    </div>

    <!-- Login for Guest -->
    <div class="auth-box">
        <h3 class="login-title">Login as Employee/Enterprise</h3>
     <form action="/login" method="POST">
            @csrf
           <input name="loginname" type="text" placeholder="Name" value="{{ old('loginname') }}">
    @error('loginname')
        <div class="error">{{ $message }}</div>
    @enderror

    <input name="loginpassword" type="password" placeholder="Password">
    @error('loginpassword')
        <div class="error">{{ $message }}</div>
    @enderror

    @if($errors->has('general'))
        <div class="error">{{ $errors->first('general') }}</div>
    @endif

    <button>Log in</button>
</form>
        </div>
    </div>
    @endauth
</body>
</html>
@endsection
