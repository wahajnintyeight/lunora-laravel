@extends('emails.layout')

@section('title', 'Order Confirmation #' . $order->order_number . ' - Lunora')

@section('content')
    <h2>Thank You for Your Order!</h2>
    
    <p>Hello {{ $user->name }},</p>
    
    <p>We're excited to confirm that we've received your order and it's being processed. Here are the details:</p>
    
    <div class="order-summary">
        <h3 style="color: #065f46; margin-top: 0;">Order Summary</h3>
        
        <table class="email-table">
            <tr>
                <td><strong>Order Number:</strong></td>
                <td>#{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td><strong>Order Date:</strong></td>
                <td>{{ $order->created_at->format('F j, Y \a\t g:i A') }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>
                    <span style="background-color: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </td>
            </tr>
            @if($order->email)
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $order->email }}</td>
            </tr>
            @endif
            @if($order->phone)
            <tr>
                <td><strong>Phone:</strong></td>
                <td>{{ $order->phone }}</td>
            </tr>
            @endif
        </table>
    </div>
    
    <h3>Items Ordered</h3>
    
    @foreach($order->items as $item)
        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; margin: 10px 0; background-color: #fafafa;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="flex: 1;">
                    <h4 style="margin: 0 0 5px 0; color: #374151;">{{ $item->name }}</h4>
                    @if($item->variant_options)
                        <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 14px;">
                            @foreach(json_decode($item->variant_options, true) as $option => $value)
                                {{ $option }}: {{ $value }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                    @endif
                    @if($item->customizations)
                        <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 14px;">
                            <strong>Customizations:</strong> {{ $item->customizations }}
                        </p>
                    @endif
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">
                        Quantity: {{ $item->quantity }} × PKR {{ number_format($item->price_pkr / 100, 2) }}
                    </p>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0; font-weight: 600; color: #065f46;">
                        PKR {{ number_format(($item->quantity * $item->price_pkr) / 100, 2) }}
                    </p>
                </div>
            </div>
        </div>
    @endforeach
    
    <div class="order-summary">
        <h3 style="color: #065f46; margin-top: 0;">Order Total</h3>
        
        <div class="order-item">
            <span>Subtotal:</span>
            <span>PKR {{ number_format($order->subtotal_pkr / 100, 2) }}</span>
        </div>
        
        @if($order->discount_pkr > 0)
            <div class="order-item">
                <span>Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif:</span>
                <span style="color: #dc2626;">-PKR {{ number_format($order->discount_pkr / 100, 2) }}</span>
            </div>
        @endif
        
        @if($order->shipping_pkr > 0)
            <div class="order-item">
                <span>Shipping:</span>
                <span>PKR {{ number_format($order->shipping_pkr / 100, 2) }}</span>
            </div>
        @endif
        
        <div class="order-item">
            <span><strong>Total:</strong></span>
            <span><strong>PKR {{ number_format($order->total_pkr / 100, 2) }}</strong></span>
        </div>
    </div>
    
    @if($order->addresses->where('type', 'shipping')->first())
        @php $shippingAddress = $order->addresses->where('type', 'shipping')->first(); @endphp
        <h3>Shipping Address</h3>
        <div style="background-color: #f9fafb; border-radius: 8px; padding: 15px; margin: 10px 0;">
            <p style="margin: 0;">
                {{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}<br>
                {{ $shippingAddress->address_line_1 }}<br>
                @if($shippingAddress->address_line_2)
                    {{ $shippingAddress->address_line_2 }}<br>
                @endif
                {{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->postal_code }}<br>
                {{ $shippingAddress->country }}
                @if($shippingAddress->phone)
                    <br>Phone: {{ $shippingAddress->phone }}
                @endif
            </p>
        </div>
    @endif
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('orders.show', $order) }}" class="btn">
            Track Your Order
        </a>
    </div>
    
    <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <h3 style="color: #065f46; margin-top: 0;">What's Next?</h3>
        <ul style="color: #065f46; margin-bottom: 0; padding-left: 20px;">
            <li>We'll send you updates as your order is processed</li>
            <li>Your jewelry will be carefully crafted and quality checked</li>
            <li>You'll receive tracking information once shipped</li>
            <li>Estimated delivery: 5-7 business days</li>
        </ul>
    </div>
    
    @if($order->notes)
        <h3>Order Notes</h3>
        <div style="background-color: #f9fafb; border-radius: 8px; padding: 15px; margin: 10px 0;">
            <p style="margin: 0; font-style: italic;">{{ $order->notes }}</p>
        </div>
    @endif
    
    <p>If you have any questions about your order, please don't hesitate to contact our customer service team. We're here to help!</p>
    
    <p>Thank you for choosing Lunora for your jewelry needs. We can't wait for you to receive your beautiful pieces!</p>
    
    <p>With love and sparkle,<br>
    The Lunora Team</p>
@endsection