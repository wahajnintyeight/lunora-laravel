<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lunora Jewelry')</title>
    <style>
        /* Reset styles */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* Base styles */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            min-width: 100%;
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #374151;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .email-header h1 {
            color: #ffffff;
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 2px;
        }

        .email-header p {
            color: #d1fae5;
            font-size: 16px;
            margin: 10px 0 0 0;
        }

        /* Content */
        .email-content {
            padding: 40px 30px;
        }

        .email-content h2 {
            color: #065f46;
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 20px 0;
        }

        .email-content h3 {
            color: #047857;
            font-size: 20px;
            font-weight: 600;
            margin: 30px 0 15px 0;
        }

        .email-content p {
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 14px 28px;
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151 !important;
            border: 2px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        /* Tables */
        .email-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .email-table th,
        .email-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .email-table th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        /* Order summary styles */
        .order-summary {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-item:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 18px;
            color: #065f46;
        }

        /* Footer */
        .email-footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .email-footer p {
            font-size: 14px;
            color: #6b7280;
            margin: 5px 0;
        }

        .email-footer a {
            color: #047857;
            text-decoration: none;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }

        /* Social links */
        .social-links {
            margin: 20px 0;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #6b7280;
            text-decoration: none;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
            
            .email-header,
            .email-content,
            .email-footer {
                padding: 20px !important;
            }
            
            .email-header h1 {
                font-size: 24px !important;
            }
            
            .btn {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .email-container {
                background-color: #1f2937 !important;
            }
            
            .email-content {
                color: #f9fafb !important;
            }
            
            .email-content h2,
            .email-content h3 {
                color: #10b981 !important;
            }
            
            .order-summary {
                background-color: #374151 !important;
            }
            
            .email-table th {
                background-color: #374151 !important;
                color: #f9fafb !important;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>LUNORA</h1>
            <p>Exquisite Jewelry Collection</p>
        </div>

        <!-- Content -->
        <div class="email-content">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>Lunora Jewelry</strong></p>
            <p>Thank you for choosing Lunora for your jewelry needs.</p>
            
            <div class="social-links">
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">Twitter</a>
            </div>
            
            <p>
                <a href="{{ config('app.url') }}">Visit our website</a> |
                <a href="{{ config('app.url') }}/contact">Contact us</a> |
                <a href="#">Unsubscribe</a>
            </p>
            
            <p style="font-size: 12px; color: #9ca3af; margin-top: 20px;">
                This email was sent from Lunora Jewelry. If you have any questions, please contact our customer service team.
            </p>
        </div>
    </div>
</body>
</html>