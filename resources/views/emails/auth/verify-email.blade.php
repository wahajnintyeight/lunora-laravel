@extends('emails.layout')

@section('title', 'Verify Your Email Address - Lunora')

@section('content')
    <h2>Welcome to Lunora, {{ $user->name }}!</h2>
    
    <p>Thank you for creating an account with Lunora. To complete your registration and start exploring our exquisite jewelry collection, please verify your email address.</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $verificationUrl }}" class="btn">
            Verify Email Address
        </a>
    </div>
    
    <div style="background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <h3 style="color: #92400e; margin-top: 0;">⏰ Important Notice</h3>
        <p style="margin-bottom: 0; color: #92400e;">This verification link will expire in {{ $expireTime }} minutes for your security.</p>
    </div>
    
    <h3>What happens after verification?</h3>
    <ul style="padding-left: 20px;">
        <li>Access to your personal account dashboard</li>
        <li>Ability to save items to your wishlist</li>
        <li>Faster checkout with saved addresses</li>
        <li>Order tracking and history</li>
        <li>Exclusive member offers and updates</li>
    </ul>
    
    <div style="background-color: #f3f4f6; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <h3 style="color: #374151; margin-top: 0;">Having trouble with the button?</h3>
        <p style="margin-bottom: 10px;">If the verification button doesn't work, copy and paste this link into your browser:</p>
        <p style="word-break: break-all; color: #047857; font-family: monospace; font-size: 14px; margin-bottom: 0;">
            {{ $verificationUrl }}
        </p>
    </div>
    
    <p>If you didn't create an account with Lunora, please ignore this email. Your email address will not be added to our system.</p>
    
    <p>Welcome to the Lunora family!<br>
    The Lunora Team</p>
@endsection