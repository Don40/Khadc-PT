@extends('layout') {{-- or whatever layout you're using --}}

@section('content')
   <form method="POST" action="{{ route('otp.verify') }}">
    @csrf
    <h2>Verify OTP</h2>

    <input type="text" name="otp" placeholder="Enter OTP" required>

    <input type="submit" value="Verify OTP">

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
@endsection
