<x-layouts.admin title="Custom Bakery Orders">

    <div style="display:flex;justify-content:between;align-items:center;margin-bottom:24px;">
        <div>
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Custom Orders</h2>
            <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">Manage custom cake requests, delivery dates, client specifications, and down payments.</p>
        </div>
        <button class="btn btn-primary" style="margin-left:auto;">
            <i class="bi bi-calendar-plus"></i> New Custom Order
        </button>
    </div>

    <!-- Custom Orders Table -->
    <div class="card">
        <div class="card-body" style="padding:0;overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:13.5px;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#475569;font-weight:600;">
                        <th style="padding:16px 20px;">Order ID</th>
                        <th style="padding:16px 20px;">Customer</th>
                        <th style="padding:16px 20px;">Specifications / Details</th>
                        <th style="padding:16px 20px;text-align:right;">Total Cost</th>
                        <th style="padding:16px 20px;text-align:right;">Advance Paid</th>
                        <th style="padding:16px 20px;text-align:center;">Status</th>
                        <th style="padding:16px 20px;">Delivery Date</th>
                        <th style="padding:16px 20px;text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody style="color:#334155;">
                    @foreach($orders as $order)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px;font-weight:700;color:#0f172a;font-family:monospace;">{{ $order['id'] }}</td>
                        <td style="padding:14px 20px;font-weight:600;color:#334155;">{{ $order['customer'] }}</td>
                        <td style="padding:14px 20px;max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $order['details'] }}">
                            <i class="bi bi-chat-text" style="color:#94a3b8;margin-right:4px;"></i>{{ $order['details'] }}
                        </td>
                        <td style="padding:14px 20px;text-align:right;font-weight:700;color:#0f172a;">৳ {{ number_format($order['price'], 2) }}</td>
                        <td style="padding:14px 20px;text-align:right;font-weight:700;color:#16a34a;">৳ {{ number_format($order['advance'], 2) }}</td>
                        <td style="padding:14px 20px;text-align:center;">
                            @if($order['status'] === 'Confirmed')
                                <span style="background:#dcfce7;color:#15803d;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;">Confirmed</span>
                            @elseif($order['status'] === 'In Progress')
                                <span style="background:#e0f2fe;color:#0369a1;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;">In Progress</span>
                            @else
                                <span style="background:#fef3c7;color:#d97706;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;">Pending</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px;font-weight:600;color:#4f46e5;"><i class="bi bi-calendar-event"></i> {{ $order['delivery_date'] }}</td>
                        <td style="padding:14px 20px;text-align:center;font-size:16px;">
                            <a href="#" style="color:#6366f1;margin-right:12px;" title="Print Slip"><i class="bi bi-printer"></i></a>
                            <a href="#" style="color:#ef4444;" title="Cancel Order"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.admin>
