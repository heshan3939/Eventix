<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Receipt</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            width: 100% !important;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        .wrapper {
            background-color: #f3f4f6;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .header p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 16px;
            margin: 10px 0 0 0;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-text {
            font-size: 16px;
            color: #374151;
            line-height: 1.6;
            margin-top: 0;
            margin-bottom: 24px;
        }
        .receipt-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 30px;
        }
        .receipt-card-header {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 16px;
            margin-bottom: 16px;
        }
        .receipt-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            font-weight: 700;
            margin: 0;
        }
        .receipt-value {
            font-size: 20px;
            color: #0f172a;
            font-weight: 800;
            margin: 4px 0 0 0;
        }
        .item-table th {
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .item-table td {
            padding: 16px 0;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
        }
        .item-name {
            font-weight: 600;
            color: #0f172a;
        }
        .item-subtext {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
        .total-row td {
            border-bottom: none;
            padding-top: 24px;
        }
        .total-label {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }
        .total-value {
            font-size: 22px;
            font-weight: 800;
            color: #4f46e5;
            text-align: right;
        }
        .btn-container {
            text-align: center;
            margin: 32px 0 16px 0;
        }
        .btn {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .footer {
            text-align: center;
            padding: 30px;
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .footer a {
            color: #6366f1;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1>Eventix</h1>
                <p>Payment Receipt</p>
            </div>

            <!-- Content -->
            <div class="content">
                <p class="welcome-text">
                    Hi <strong>{{ $user->name }}</strong>,
                </p>
                <p class="welcome-text">
                    Thank you for your purchase! We've successfully processed your payment. Below you'll find the receipt details and ticket summary for your order.
                </p>

                <!-- Receipt details card -->
                <div class="receipt-card">
                    <div class="receipt-card-header">
                        <table style="width: 100%;">
                            <tr>
                                <td>
                                    <p class="receipt-title">Order Reference</p>
                                    <p class="receipt-value">#{{ $reference }}</p>
                                </td>
                                <td style="text-align: right;">
                                    <p class="receipt-title">Date</p>
                                    <p style="font-size: 15px; color: #334155; font-weight: 600; margin: 4px 0 0 0;">
                                        {{ now()->format('M d, Y') }}
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Items table -->
                    <table class="item-table">
                        <thead>
                            <tr>
                                <th style="width: 60%;">Item Description</th>
                                <th style="width: 15%; text-align: center;">Qty</th>
                                <th style="width: 25%; text-align: right;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td>
                                        <div class="item-name">{{ $booking->ticketType->event->title }}</div>
                                        <div class="item-subtext">{{ $booking->ticketType->name }}</div>
                                    </td>
                                    <td style="text-align: center;">{{ $booking->quantity }}</td>
                                    <td style="text-align: right; font-weight: 600;">
                                        Rs. {{ number_format($booking->total_price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            
                            <!-- Total Row -->
                            <tr class="total-row">
                                <td colspan="2" class="total-label">Total Paid</td>
                                <td class="total-value">Rs. {{ number_format($totalAmount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Action Button -->
                <div class="btn-container">
                    <a href="{{ route('bookings.index') }}" class="btn" target="_blank">View Your Bookings</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>This email was sent to {{ $user->email }}.</p>
                <p>&copy; {{ date('Y') }} Eventix. All rights reserved.</p>
                <p>Need support? Please contact our <a href="mailto:support@eventix.test">support team</a>.</p>
            </div>
        </div>
    </div>
</body>
</html>
