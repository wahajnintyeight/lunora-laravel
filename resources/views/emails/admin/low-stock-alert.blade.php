@extends('emails.layout')

@section('title', 'Low Stock Alert - Lunora Admin')

@section('content')
    <h2>⚠️ Low Stock Alert</h2>
    
    <p>Hello {{ $admin->name }},</p>
    
    <p>This is an automated alert to inform you that <strong>{{ $productCount }}</strong> product(s) in your inventory have stock levels below the threshold of {{ $threshold }} units.</p>
    
    <div style="background-color: #fef3c7; border: 2px solid #f59e0b; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <h3 style="color: #92400e; margin-top: 0;">📊 Inventory Alert Summary</h3>
        <table class="email-table" style="margin: 0;">
            <tr>
                <td><strong>Products Below Threshold:</strong></td>
                <td>{{ $productCount }}</td>
            </tr>
            <tr>
                <td><strong>Stock Threshold:</strong></td>
                <td>{{ $threshold }} units</td>
            </tr>
            <tr>
                <td><strong>Alert Date:</strong></td>
                <td>{{ now()->format('F j, Y \a\t g:i A') }}</td>
            </tr>
        </table>
    </div>
    
    <h3>Products Requiring Attention</h3>
    
    @foreach($lowStockProducts as $product)
        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; margin: 10px 0; background-color: #fafafa;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="flex: 1;">
                    <h4 style="margin: 0 0 5px 0; color: #374151;">{{ $product->name }}</h4>
                    <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 14px;">
                        <strong>SKU:</strong> {{ $product->sku }}
                    </p>
                    <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 14px;">
                        <strong>Category:</strong> {{ $product->category->name ?? 'Uncategorized' }}
                    </p>
                    @if($product->hasVariants())
                        <p style="margin: 0; color: #6b7280; font-size: 14px;">
                            <strong>Note:</strong> This product has variants - check individual variant stock levels
                        </p>
                    @endif
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0; font-weight: 600; color: {{ $product->stock <= 0 ? '#dc2626' : ($product->stock <= 2 ? '#f59e0b' : '#6b7280') }};">
                        @if($product->stock <= 0)
                            OUT OF STOCK
                        @else
                            {{ $product->stock }} units left
                        @endif
                    </p>
                    <p style="margin: 5px 0 0 0; font-size: 14px; color: #6b7280;">
                        PKR {{ number_format($product->price_pkr / 100, 2) }}
                    </p>
                </div>
            </div>
            
            @if($product->hasVariants() && $product->variants->count() > 0)
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                    <h5 style="margin: 0 0 10px 0; color: #374151; font-size: 14px;">Variant Stock Levels:</h5>
                    @foreach($product->variants as $variant)
                        @if($variant->stock <= $threshold)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 5px 0;">
                                <span style="font-size: 13px; color: #6b7280;">
                                    @foreach(json_decode($variant->options_json, true) as $option => $value)
                                        {{ $option }}: {{ $value }}@if(!$loop->last), @endif
                                    @endforeach
                                </span>
                                <span style="font-size: 13px; font-weight: 600; color: {{ $variant->stock <= 0 ? '#dc2626' : ($variant->stock <= 2 ? '#f59e0b' : '#6b7280') }};">
                                    @if($variant->stock <= 0)
                                        OUT OF STOCK
                                    @else
                                        {{ $variant->stock }} units
                                    @endif
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('admin.products.index') }}?filter=low_stock" class="btn">
            Manage Inventory
        </a>
    </div>
    
    <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <h3 style="color: #065f46; margin-top: 0;">💡 Recommended Actions</h3>
        <ul style="color: #065f46; margin-bottom: 0; padding-left: 20px;">
            <li><strong>Reorder Stock:</strong> Contact suppliers to replenish low-stock items</li>
            <li><strong>Update Product Status:</strong> Consider marking out-of-stock items as inactive</li>
            <li><strong>Review Sales Data:</strong> Analyze which products are selling faster than expected</li>
            <li><strong>Adjust Pricing:</strong> Consider promotional pricing for slow-moving items</li>
            <li><strong>Update Threshold:</strong> Adjust stock alert thresholds based on sales velocity</li>
        </ul>
    </div>
    
    <div style="background-color: #f3f4f6; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <h3 style="color: #374151; margin-top: 0;">📈 Inventory Management Tips</h3>
        <ul style="color: #374151; margin-bottom: 0; padding-left: 20px;">
            <li>Set up automatic reorder points for popular items</li>
            <li>Monitor seasonal trends to anticipate demand</li>
            <li>Keep safety stock for best-selling products</li>
            <li>Review and update stock thresholds regularly</li>
            <li>Consider dropshipping for slow-moving items</li>
        </ul>
    </div>
    
    <p><strong>Note:</strong> This alert is sent automatically when products fall below the configured stock threshold. You can adjust these settings in the admin panel.</p>
    
    <p>Best regards,<br>
    Lunora Inventory Management System</p>
@endsection