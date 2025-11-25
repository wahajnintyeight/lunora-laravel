@extends('emails.layout')

@section('title', 'Email Configuration Test - Lunora')

@section('content')
    <h2>Email Configuration Test</h2>
    
    <p>Hello!</p>
    
    <p>This is a test email to verify that your Lunora email configuration is working correctly.</p>
    
    <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <h3 style="color: #065f46; margin-top: 0;">✅ Configuration Status</h3>
        <p style="margin-bottom: 0;">If you're reading this email, your SMTP configuration is working properly!</p>
    </div>
    
    <p>Here are some details about this test:</p>
    
    <table class="email-table">
        <tr>
            <td><strong>Test Date:</strong></td>
            <td>{{ now()->format('F j, Y \a\t g:i A') }}</td>
        </tr>
        <tr>
            <td><strong>Application:</strong></td>
            <td>{{ config('app.name') }}</td>
        </tr>
        <tr>
            <td><strong>Environment:</strong></td>
            <td>{{ config('app.env') }}</td>
        </tr>
        <tr>
            <td><strong>Mail Driver:</strong></td>
            <td>{{ config('mail.default') }}</td>
        </tr>
    </table>
    
    <p>If you continue to receive test emails, please check your email configuration settings.</p>
    
    <p>Best regards,<br>
    The Lunora Team</p>
@endsection