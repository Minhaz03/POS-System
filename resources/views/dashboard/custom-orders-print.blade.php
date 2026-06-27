<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Custom Order - {{ $order->order_number }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: #f1f5f9;
            color: #0f172a;
            line-height: 1.5;
            padding: 40px 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 24px;
            margin-bottom: 32px;
        }

        .company-info h1 {
            font-size: 24px;
            font-weight: 800;
            color: #6366f1;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .company-info p {
            color: #64748b;
            font-size: 13px;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .invoice-title p {
            font-size: 14px;
            color: #64748b;
            margin-top: 4px;
        }

        .invoice-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .meta-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
        }

        .meta-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 8px;
        }

        .meta-content {
            font-size: 14px;
            font-weight: 500;
        }

        .meta-content strong {
            display: block;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 2px;
        }
        
        .order-specs {
            margin-bottom: 32px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .order-specs-header {
            background: #f8fafc;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .order-specs-body {
            padding: 20px;
            font-size: 15px;
            color: #1e293b;
            white-space: pre-wrap;
        }

        .totals {
            width: 320px;
            margin-left: auto;
            border-top: 2px solid #e2e8f0;
            padding-top: 16px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
            color: #475569;
        }

        .total-row.grand-total {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px dashed #cbd5e1;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 24px;
        }

        .print-btn {
            display: block;
            width: 200px;
            margin: 0 auto 30px auto;
            padding: 12px 20px;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            transition: transform 0.15s, background 0.15s;
        }

        .print-btn:hover {
            background: #4f46e5;
            transform: translateY(-1px);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .invoice-container {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }
            .print-btn {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn">
        🖨️ Print Order Slip
    </button>

    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>POS System</h1>
                <p>Bakery Edition</p>
                <p>123 Bakery Street, Food City, 12345</p>
                <p>Phone: +1 234 567 890</p>
            </div>
            <div class="invoice-title">
                <h2>CUSTOM ORDER</h2>
                <p># {{ $order->order_number }}</p>
            </div>
        </div>

        <!-- Meta Information -->
        <div class="invoice-meta">
            <div class="meta-box">
                <div class="meta-title">Customer Details</div>
                <div class="meta-content">
                    <strong>{{ $order->customer_name }}</strong>
                    @if($order->customer && $order->customer->phone)
                        <div>{{ $order->customer->phone }}</div>
                    @endif
                </div>
            </div>
            
            <div class="meta-box">
                <div class="meta-title">Order Timeline</div>
                <div class="meta-content" style="display:grid;grid-template-columns:auto 1fr;gap:4px 12px;">
                    <span style="color:#64748b">Order Date:</span>
                    <span>{{ $order->created_at->format('F d, Y') }}</span>
                    
                    <span style="color:#64748b">Delivery:</span>
                    <span style="font-weight:700;color:#6366f1;">{{ $order->delivery_date->format('F d, Y') }}</span>
                    
                    <span style="color:#64748b">Status:</span>
                    <span style="text-transform:capitalize;font-weight:700;
                        @if($order->status === 'Confirmed') color:#16a34a; 
                        @elseif($order->status === 'Cancelled') color:#dc2626; 
                        @else color:#d97706; @endif">
                        {{ $order->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Specifications -->
        <div class="order-specs">
            <div class="order-specs-header">Order Specifications / Details</div>
            <div class="order-specs-body">{{ $order->details }}</div>
        </div>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Total Cost</span>
                <span>৳ {{ number_format($order->total_price, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Advance Paid</span>
                <span style="color:#16a34a;">- ৳ {{ number_format($order->advance_payment, 2) }}</span>
            </div>
            <div class="total-row grand-total">
                <span>Balance Due</span>
                @php
                    $balance = $order->total_price - $order->advance_payment;
                @endphp
                <span style="color:#6366f1;">৳ {{ number_format(max(0, $balance), 2) }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Please present this slip when collecting your custom order.</p>
            <p style="margin-top:4px;">Thank you for choosing us for your special occasion!</p>
        </div>
    </div>

</body>
</html>
