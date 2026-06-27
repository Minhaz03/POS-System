<x-layouts.admin title="Custom Order — {{ $custom_order->order_number }}">

    {{-- Page Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('dashboard.custom-orders') }}" class="btn-topbar" style="padding:8px 12px;text-decoration:none;"><i class="bi bi-arrow-left"></i> Back</a>
            <div>
                <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Custom Order Slip</h2>
                <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">
                    <span style="font-family:monospace;font-weight:700;color:#6366f1;">{{ $custom_order->order_number }}</span>
                    &nbsp;·&nbsp; Delivery: {{ $custom_order->delivery_date->format('d M, Y') }}
                </p>
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;" class="no-print">
            <a href="{{ route('dashboard.custom-orders.edit', $custom_order) }}" class="btn btn-outline" style="text-decoration:none;">
                <i class="bi bi-pencil-square"></i> Edit Order
            </a>
            <button onclick="window.print()" class="btn btn-outline">
                <i class="bi bi-printer"></i> Print Receipt
            </button>
            <form id="delete-form" method="POST" action="{{ route('dashboard.custom-orders.destroy', $custom_order) }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="button" class="btn" style="background:#fef2f2;border:1px solid #fca5a5;color:#b91c1c;" onclick="confirmDelete()">
                    <i class="bi bi-trash"></i> Cancel / Delete
                </button>
            </form>
        </div>
    </div>

    {{-- Main Grid --}}
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start;">

        {{-- LEFT: Custom Order Body --}}
        <div style="display:flex;flex-direction:column;gap:24px;">

            {{-- Custom Order Header Info --}}
            <div class="card">
                <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;">
                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:#94a3b8;margin-bottom:6px;">Customer Details</div>
                        @if($custom_order->customer)
                            <div style="font-weight:700;color:#0f172a;font-size:14px;">{{ $custom_order->customer->name }}</div>
                            @if($custom_order->customer->phone)
                                <div style="font-size:12.5px;color:#64748b;margin-top:2px;"><i class="bi bi-telephone"></i> {{ $custom_order->customer->phone }}</div>
                            @endif
                            @if($custom_order->customer->email)
                                <div style="font-size:12.5px;color:#64748b;margin-top:2px;"><i class="bi bi-envelope"></i> {{ $custom_order->customer->email }}</div>
                            @endif
                        @else
                            <div style="color:#64748b;font-size:14px;">Walk-in Customer</div>
                        @endif
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:#94a3b8;margin-bottom:6px;">Delivery & Date</div>
                        <div style="font-weight:700;color:#0f172a;font-size:14px;"><i class="bi bi-calendar-event"></i> {{ $custom_order->delivery_date->format('d M, Y') }}</div>
                        <div style="font-size:12px;color:#64748b;margin-top:2px;">Ordered: {{ $custom_order->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:#94a3b8;margin-bottom:6px;">Order Managed By</div>
                        <div style="font-weight:700;color:#0f172a;font-size:14px;"><i class="bi bi-person"></i> {{ $custom_order->creator?->name ?? 'System' }}</div>
                        <div style="font-size:12px;color:#64748b;margin-top:2px;">
                            Payment status:
                            @if($custom_order->advance >= $custom_order->price)
                                <span style="font-weight:700;color:#16a34a;">Fully Paid</span>
                            @elseif($custom_order->advance > 0)
                                <span style="font-weight:700;color:#eab308;">Partial Payment</span>
                            @else
                                <span style="font-weight:700;color:#ef4444;">Unpaid</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Specifications Card --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="bi bi-chat-text"></i> Specifications & Details</span>
                </div>
                <div class="card-body" style="color:#334155;line-height:1.7;white-space:pre-wrap;font-size:14px;">{{ $custom_order->details }}</div>
            </div>
        </div>

        {{-- RIGHT: Status & Financial Summary --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Status Card --}}
            <div class="card">
                <div class="card-header"><span class="card-title"><i class="bi bi-info-circle"></i> Order Status</span></div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:13px;color:#64748b;font-weight:600;">Order ID</span>
                        <span style="font-family:monospace;font-weight:700;color:#6366f1;">{{ $custom_order->order_number }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:13px;color:#64748b;font-weight:600;">Status</span>
                        @if($custom_order->status === 'Confirmed')
                            <span style="background:#dcfce7;color:#15803d;padding:3px 12px;border-radius:999px;font-size:12px;font-weight:700;"><i class="bi bi-check-circle-fill"></i> Confirmed</span>
                        @elseif($custom_order->status === 'In Progress')
                            <span style="background:#e0f2fe;color:#0369a1;padding:3px 12px;border-radius:999px;font-size:12px;font-weight:700;"><i class="bi bi-arrow-repeat"></i> In Progress</span>
                        @elseif($custom_order->status === 'Completed')
                            <span style="background:#f0fdf4;color:#166534;padding:3px 12px;border-radius:999px;font-size:12px;font-weight:700;border:1px solid #bbf7d0;"><i class="bi bi-star-fill"></i> Completed</span>
                        @elseif($custom_order->status === 'Cancelled')
                            <span style="background:#fee2e2;color:#991b1b;padding:3px 12px;border-radius:999px;font-size:12px;font-weight:700;"><i class="bi bi-slash-circle"></i> Cancelled</span>
                        @else
                            <span style="background:#fef3c7;color:#d97706;padding:3px 12px;border-radius:999px;font-size:12px;font-weight:700;"><i class="bi bi-clock"></i> Pending</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Financial Summary --}}
            <div class="card">
                <div class="card-header"><span class="card-title"><i class="bi bi-receipt"></i> Payment Progress</span></div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#475569;">
                        <span>Total Price:</span>
                        <span style="font-weight:600;color:#0f172a;">৳ {{ number_format($custom_order->price, 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#475569;">
                        <span>Advance Paid:</span>
                        <span style="font-weight:600;color:#16a34a;">৳ {{ number_format($custom_order->advance, 2) }}</span>
                    </div>
                    <hr style="border:0;border-top:2px solid #e2e8f0;margin:4px 0;">
                    @php
                        $due = max(0, $custom_order->price - $custom_order->advance);
                    @endphp
                    <div style="display:flex;justify-content:space-between;font-size:17px;font-weight:800;color:#0f172a;">
                        <span>Balance Due:</span>
                        <span style="color:{{ $due > 0 ? '#ef4444' : '#16a34a' }};">৳ {{ number_format($due, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Print Receipt Template --}}
    <div id="print-receipt" style="display:none;">
        <div style="text-align:center;padding-bottom:16px;border-bottom:2px dashed #333;margin-bottom:16px;">
            <div style="font-size:20px;font-weight:900;">🏪 Sweet Bakery</div>
            <div style="font-size:12px;color:#555;">Custom celebratory order receipt</div>
        </div>
        <div style="font-size:13px;margin-bottom:12px;">
            <div><strong>Order No:</strong> {{ $custom_order->order_number }}</div>
            <div><strong>Order Date:</strong> {{ $custom_order->created_at->format('d M Y, h:i A') }}</div>
            <div><strong>Delivery Date:</strong> {{ $custom_order->delivery_date->format('d M Y') }}</div>
            <div><strong>Customer:</strong> {{ $custom_order->customer?->name ?? 'Walk-in Customer' }}</div>
            @if($custom_order->customer?->phone)
                <div><strong>Phone:</strong> {{ $custom_order->customer->phone }}</div>
            @endif
            <div><strong>Managed by:</strong> {{ $custom_order->creator?->name ?? 'System' }}</div>
        </div>
        <div style="font-size:13px;margin-bottom:12px;border-top:1px solid #333;border-bottom:1px solid #333;padding:8px 0;">
            <strong>Specifications:</strong>
            <p style="margin:4px 0 0 0; white-space: pre-wrap; font-family: monospace; line-height: 1.4;">{{ $custom_order->details }}</p>
        </div>
        <div style="font-size:13px;">
            <div style="display:flex;justify-content:space-between;"><span>Total Price:</span><span>৳{{ number_format($custom_order->price, 2) }}</span></div>
            <div style="display:flex;justify-content:space-between;"><span>Advance Paid:</span><span>৳{{ number_format($custom_order->advance, 2) }}</span></div>
            <div style="display:flex;justify-content:space-between;font-weight:900;font-size:15px;border-top:1px solid #333;margin-top:8px;padding-top:6px;"><span>DUE BALANCE:</span><span>৳{{ number_format($due, 2) }}</span></div>
        </div>
        <div style="text-align:center;margin-top:24px;padding-top:12px;border-top:2px dashed #333;font-size:12px;color:#555;">
            Thank you for choosing Sweet Bakery!<br>Please bring this slip upon picking up your order.
        </div>
    </div>

    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Cancel & Delete Order?',
                html: `<p style="color:#475569;font-size:14px;">Order <strong style="font-family:monospace;color:#0f172a;">{{ $custom_order->order_number }}</strong> will be deleted.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form').submit();
                }
            });
        }
    </script>

    <style>
        @media print {
            .no-print, .sidebar, .topbar, header { display: none !important; }
            .main-wrapper { margin-left: 0 !important; }
            .page-content { padding: 0 !important; }
            /* Hide all main content and show only receipt */
            body > * { display: none !important; }
            #print-receipt { display: block !important; padding: 20px; font-family: monospace; max-width: 320px; margin: 0 auto; }
        }
    </style>

</x-layouts.admin>
