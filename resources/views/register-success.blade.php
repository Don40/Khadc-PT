@extends('layout')

@section('content')
    <div style="text-align: center; padding: 50px;">
        <h2 style="color: green;">🎉 Registration Completed Successfully!</h2>
        <a href="{{ route('register.form') }}">← Go back to registration</a>
    </div>
@endsection
