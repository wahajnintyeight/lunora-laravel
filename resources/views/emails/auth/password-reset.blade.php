@extends('emails.layout')

@section('title', 'Reset Your Password - Lunora')

@section('content')
    <h2>Password Reset Request</h2>
    
    <p>Hello {{ $user->name }},</p>
    
    <p>We received a request to reset the password for your Lunora account. If you made this request, click the button below to create a new password.</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $resetUrl }}" class="btn">
            Reset Password
        </a>
    </div>
    
    <div style="background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <h3 style="color: #92400e; margin-top: 0;">⏰ Time Sensitive</h3>
        <p style="margin-bottom: 0; color: #92400e;">This password reset link will expire in {{ $expireTime }} minutes for your security.</p>
    </div>
    
    <div style="background-color: #fef2f2; border: 1px solid #f87171; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <h3 style="color: #dc2626; margin-top: 0;">🔒 Security Notice</h3>
        <p style="color: #dc2626; margin-bottom: 10px;">If you didn't request a password reset, please:</p>
        <ul style="color: #dc2626; margin-bottom: 0;">
            <li>Ignore this email - your password will remain unchanged</li>
            <li>Consider changing your password if you suspect unauthorized access</li>
            <li>Contact our support team if you have concerns</li>
        </ul>
    </div>
    
    <div style="background-color: #f3f4f6; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <h3 style="color: #374151; margin-top: 0;">Having trouble with the button?</h3>
        <p style="margin-bottom: 10px;">If the reset button doesn't work, copy and paste this link into your browser:</p>
        <p style="word-break: break-all; color: #047857; font-family: monospace; font-size: 14px; margin-bottom: 0;">
            {{ $resetUrl }}
        </p>
    </div>
    
    <h3>Password Security Tips:</h3>
    <ul style="padding-left: 20px;">
        <li>Use a combination of letters, numbers, and special characters</li>
        <li>Make your password at least 8 characters long</li>
        <li>Don't use personal information like your name or birthday</li>
        <li>Consider using a password manager</li>
        <li>Don't reuse passwords from other accounts</li>
    </ul>
    
    <p>If you continue to have trouble accessing your account, please contact our customer support team.</p>
    
    <p>Best regards,<br>
    The Lunora Security Team</p>
@endsection