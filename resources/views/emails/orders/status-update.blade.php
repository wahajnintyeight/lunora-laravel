@extends('emails.layout')

@section('title', 'Order Update: ' . $statusTitle . ' - Lunora')

@section('content')
    <h2>{{ $statusTitle }}</h2>
    
    <p>Hello {{ $user->name }},</p>
    
    <p>We have an update on your order <strong>#{{ $order->order_number }}</strong>.</p>
    
    <div style="background-color: {{ $statusColor }}15; border: 2px solid {{ $statusColor }}; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center;">
        <h3 style="color: {{ $statusColor }}; margin-top: 0; font-size: 24px;">
            @switch($order->status)
                @case('pending')
                    📋 {{ $statusTitle }}
                    @break
                @case('processing')
                    ⚙️ {{ $statusTitle }}
                    @break
                @case('shipped')
                    🚚 {{ $statusTitle }}
                    @break
                @case('delivered')
                    ✅ {{ $statusTitle }}
                    @break
                @case('cancelled')
                    ❌ {{ $statusTitle }}
                    @break
                @case('refunded')
                    💰 {{ $statusTitle }}
                    @break
                @default
                    📦 {{ $statusTitle }}
            @endswitch
        </h3>
        <p style="color: {{ $statusColor }}; margin-bottom: 0; font-size: 16px; font-weight: 500;">
            {{ $statusMessage }}
        </p>
    </div>
    
    @if($order->status === 'shipped')
        <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h3 style="color: #065f46; margin-top: 0;">📦 Shipping Information</h3>
            <p style="color: #065f46; margin-bottom: 10px;">Your order is now on its way to you!</p>
            <ul style="color: #065f46; margin-bottom: 0; padding-left: 20px;">
                <li>Estimated delivery: 3-5 business days</li>
                <li>You'll receive tracking information shortly</li>
                <li>Someone will need to be available to sign for the package</li>
            </ul>
        </div>
    @endif
    
    @if($order->status === 'delivered')
        <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h3 style="color: #065f46; margin-top: 0;">🎉 Enjoy Your New Jewelry!</h3>
            <p style="color: #065f46; margin-bottom: 10px;">We hope you absolutely love your new pieces! Here are a few things to keep in mind:</p>
            <ul style="color: #065f46; margin-bottom: 0; padding-left: 20px;">
                <li>Take photos and share them with us on social media</li>
                <li>Follow the care instructions included with your jewelry</li>
                <li>Contact us if you have any questions or concerns</li>
                <li>Consider leaving a review to help other customers</li>
            </ul>
        </div>
    @endif
    
    @if(in_array($order->status, ['cancelled', 'refunded']))
        <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h3 style="color: #dc2626; margin-top: 0;">
                @if($order->status === 'cancelled')
                    Order Cancellation Details
                @else
                    Refund Information
                @endif
            </h3>
            @if($order->status === 'cancelled')
                <p style="color: #dc2626; margin-bottom: 10px;">Your order has been cancelled as requested. Here's what happens next:</p>
                <ul style="color: #dc2626; margin-bottom: 0; padding-left: 20px;">
                    <li>No charges will be processed for this order</li>
                    <li>Any pending authorizations will be released</li>
                    <li>You're welcome to place a new order anytime</li>
                </ul>
            @else
                <p style="color: #dc2626; margin-bottom: 10px;">Your refund has been processed successfully:</p>
                <ul style="color: #dc2626; margin-bottom: 0; padding-left: 20px;">
                    <li>Refund amount: PKR {{ number_format($order->total_pkr / 100, 2) }}</li>
                    <li>Processing time: 5-7 business days</li>
                    <li>The refund will appear on your original payment method</li>
                </ul>
            @endif
        </div>
    @endif
    
    <div class="order-summary">
        <h3 style="color: #065f46; margin-top: 0;">Order Details</h3>
        
        <table class="email-table">
            <tr>
                <td><strong>Order Number:</strong></td>
                <td>#{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td><strong>Order Date:</strong></td>
                <td>{{ $order->created_at->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td><strong>PKR {{ number_format($order->total_pkr / 100, 2) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Items:</strong></td>
                <td>{{ $order->items->sum('quantity') }} item(s)</td>
            </tr>
        </table>
    </div>
    
    @if($order->status !== 'cancelled' && $order->status !== 'refunded')
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('orders.show', $order) }}" class="btn">
                View Order Details
            </a>
        </div>
    @endif
    
    @if($order->status === 'processing')
        <div style="background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h3 style="color: #92400e; margin-top: 0;">⏰ What's Happening Now?</h3>
            <p style="color: #92400e; margin-bottom: 10px;">Our skilled artisans are working on your order:</p>
            <ul style="color: #92400e; margin-bottom: 0; padding-left: 20px;">
                <li>Quality materials are being selected</li>
                <li>Each piece is being carefully crafted</li>
                <li>Quality control checks are performed</li>
                <li>Your jewelry is being prepared for shipping</li>
            </ul>
        </div>
    @endif
    
    <p>If you have any questions about your order or need assistance, please don't hesitate to contact our customer service team. We're here to help!</p>
    
    @if($order->status === 'delivered')
        <p>Thank you for choosing Lunora! We hope your new jewelry brings you joy and confidence.</p>
    @else
        <p>Thank you for your patience and for choosing Lunora.</p>
    @endif
    
    <p>Best regards,<br>
    The Lunora Team</p>
@endsection