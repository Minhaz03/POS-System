<x-layouts.admin title="Purchase Order — {{ $purchase->reference_no }}">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('dashboard.purchases') }}" class="btn-topbar" style="padding:8px 12px;text-decoration:none;"><i class="bi bi-arrow-left"></i> Back</a>
            <div>
                <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Purchase Order</h2>
                <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">
                    <span style="font-family:monospace;font-weight:700;color:#6366f1;">{{ $purchase->reference_no }}</span>
                    &nbsp;·&nbsp; {{ $purchase->purchase_date->format('d M, Y') }}
                </p>
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            @if(!in_array($purchase->status, ['received', 'returned']))
                <a href="{{ route('dashboard.purchases.edit', $purchase) }}" class="btn btn-outline" style="text-decoration:none;">
                    <i class="bi bi-pencil-square"></i> Edit Order
                </a>
            @endif

            @if($purchase->status !== 'received' && $purchase->status !== 'returned')
                <form id="receive-form" method="POST" action="{{ route('dashboard.purchases.receive', $purchase) }}" style="display:inline;">
                    @csrf
                    <button type="button" class="btn btn-primary" onclick="confirmReceive()">
                        <i class="bi bi-box-arrow-in-down"></i> Mark as Received
                    </button>
                </form>
            @endif

            <button onclick="window.print()" class="btn btn-outline" title="Print Invoice">
                <i class="bi bi-printer"></i> Print
            </button>
        </div>
    </div>

    {{-- Status Banner --}}
    @if($purchase->status === 'received')
        <div style="background:#dcfce7;border:1px solid #86efac;border-radius:10px;padding:12px 20px;margin-bottom:20px;display:flex;align-items:center;gap:10px;color:#15803d;font-weight:600;">
            <i class="bi bi-check-circle-fill" style="font-size:20px;"></i>
            This purchase order has been <strong>received</strong>. Stock has been updated.
        </div>
    @elseif($purchase->status === 'returned')
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:12px 20px;margin-bottom:20px;display:flex;align-items:center;gap:10px;color:#b91c1c;font-weight:600;">
            <i class="bi bi-exclamation-triangle-fill" style="font-size:20px;"></i>
            This purchase order has been <strong>returned</strong>.
        </div>
    @endif

    {{-- Main Content Grid --}}
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start;">

        {{-- Left: Order Info & Line Items --}}
        <div style="display:flex;flex-direction:column;gap:24px;">

            {{-- Header Card --}}
            <div class="card">
                <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;">
                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:#94a3b8;margin-bottom:6px;">Supplier</div>
                        @if($purchase->supplier)
                            <div style="font-weight:700;color:#0f172a;font-size:14px;">{{ $purchase->supplier->name }}</div>
                            @if($purchase->supplier->phone)
                                <div style="font-size:12.5px;color:#64748b;margin-top:2px;"><i class="bi bi-telephone"></i> {{ $purchase->supplier->phone }}</div>
                            @endif
                        @else
                            <div style="color:#64748b;font-size:14px;">— Walk-in / Unknown</div>
                        @endif
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:#94a3b8;margin-bottom:6px;">Purchase Date</div>
                        <div style="font-weight:700;color:#0f172a;font-size:14px;"><i class="bi bi-calendar3"></i> {{ $purchase->purchase_date->format('d M, Y') }}</div>
                        <div style="font-size:12px;color:#64748b;margin-top:2px;">Created: {{ $purchase->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;color:#94a3b8;margin-bottom:6px;">Created By</div>
                        <div style="font-weight:700;color:#0f172a;font-size:14px;"><i class="bi bi-person"></i> {{ $purchase->creator?->name ?? 'System' }}</div>
                        <div style="font-size:12px;color:#64748b;margin-top:2px;">
                            Payment: <span style="font-weight:600;color:#374151;text-transform:capitalize;">{{ str_replace('_', ' ', $purchase->payment_method ?? '—') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="bi bi-box-seam"></i> Purchase Items ({{ $purchase->items->count() }} {{ $purchase->items->count() === 1 ? 'item' : 'items' }})</span>
                </div>
                <div class="card-body" style="padding:0;overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;text-align:left;font-size:13.5px;">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#475569;font-weight:600;">
                                <th style="padding:14px 20px;">#</th>
                                <th style="padding:14px 20px;">Product</th>
                                <th style="padding:14px 20px;text-align:center;">SKU</th>
                                <th style="padding:14px 20px;text-align:center;">Quantity</th>
                                <th style="padding:14px 20px;text-align:right;">Unit Cost</th>
                                <th style="padding:14px 20px;text-align:right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody style="color:#334155;">
                            @foreach($purchase->items as $i => $item)
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:13px 20px;color:#94a3b8;font-weight:600;">{{ $i + 1 }}</td>
                                <td style="padding:13px 20px;">
                                    <div style="font-weight:700;color:#0f172a;">{{ $item->product?->name ?? 'Deleted Product' }}</div>
                                </td>
                                <td style="padding:13px 20px;text-align:center;font-family:monospace;font-size:12px;color:#475569;">{{ $item->product?->sku ?? '—' }}</td>
                                <td style="padding:13px 20px;text-align:center;font-weight:600;">
                                    {{ floatval($item->quantity) }} {{ $item->product?->unit?->short_name ?? 'pcs' }}
                                </td>
                                <td style="padding:13px 20px;text-align:right;">৳ {{ number_format($item->unit_cost, 2) }}</td>
                                <td style="padding:13px 20px;text-align:right;font-weight:700;color:#0f172a;">৳ {{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Notes --}}
            @if($purchase->notes)
                <div class="card">
                    <div class="card-header"><span class="card-title"><i class="bi bi-chat-left-text"></i> Purchase Notes</span></div>
                    <div class="card-body" style="color:#475569;line-height:1.7;white-space:pre-wrap;">{{ $purchase->notes }}</div>
                </div>
            @endif
        </div>

        {{-- Right: Financials Summary + Status --}}
        <div style="display:flex;flex-direction:column;gap:20px;">

            {{-- Statuses Card --}}
            <div class="card">
                <div class="card-header"><span class="card-title"><i class="bi bi-info-circle"></i> Status Overview</span></div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:13px;color:#64748b;font-weight:600;">Shipment Status</span>
                        @if($purchase->status === 'received')
                            <span style="background:#dcfce7;color:#15803d;padding:3px 12px;border-radius:999px;font-size:12px;font-weight:700;"><i class="bi bi-box-seam-fill"></i> Received</span>
                        @elseif($purchase->status === 'partial')
                            <span style="background:#fef3c7;color:#d97706;padding:3px 12px;border-radius:999px;font-size:12px;font-weight:700;"><i class="bi bi-patch-minus"></i> Partial</span>
                        @elseif($purchase->status === 'returned')
                            <span style="background:#fee2e2;color:#b91c1c;padding:3px 12px;border-radius:999px;font-size:12px;font-weight:700;"><i class="bi bi-arrow-left-right"></i> Returned</span>
                        @else
                            <span style="background:#eff6ff;color:#1d4ed8;padding:3px 12px;border-radius:999px;font-size:12px;font-weight:700;"><i class="bi bi-hourglass-split"></i> Ordered</span>
                        @endif
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:13px;color:#64748b;font-weight:600;">Payment Status</span>
                        @if($purchase->payment_status === 'paid')
                            <span style="background:#dcfce7;color:#15803d;padding:3px 12px;border-radius:4px;font-size:12px;font-weight:700;">Paid</span>
                        @elseif($purchase->payment_status === 'partial')
                            <span style="background:#fef3c7;color:#d97706;padding:3px 12px;border-radius:4px;font-size:12px;font-weight:700;">Partial</span>
                        @else
                            <span style="background:#fee2e2;color:#b91c1c;padding:3px 12px;border-radius:4px;font-size:12px;font-weight:700;">Unpaid</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Financial Breakdown Card --}}
            <div class="card">
                <div class="card-header"><span class="card-title"><i class="bi bi-receipt"></i> Financial Summary</span></div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#475569;">
                        <span>Subtotal:</span>
                        <span style="font-weight:600;color:#0f172a;">৳ {{ number_format($purchase->subtotal, 2) }}</span>
                    </div>
                    @if($purchase->discount_amount > 0)
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#475569;">
                        <span>Discount:</span>
                        <span style="font-weight:600;color:#ef4444;">— ৳ {{ number_format($purchase->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($purchase->tax_amount > 0)
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#475569;">
                        <span>Tax:</span>
                        <span style="font-weight:600;color:#0f172a;">+ ৳ {{ number_format($purchase->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($purchase->shipping_cost > 0)
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#475569;">
                        <span>Shipping Cost:</span>
                        <span style="font-weight:600;color:#0f172a;">+ ৳ {{ number_format($purchase->shipping_cost, 2) }}</span>
                    </div>
                    @endif

                    <hr style="border:0;border-top:2px solid #e2e8f0;margin:4px 0;">

                    <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:800;color:#0f172a;">
                        <span>Grand Total:</span>
                        <span>৳ {{ number_format($purchase->grand_total, 2) }}</span>
                    </div>

                    <hr style="border:0;border-top:1px solid #f1f5f9;margin:4px 0;">

                    <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#475569;">
                        <span>Amount Paid:</span>
                        <span style="font-weight:700;color:#16a34a;">৳ {{ number_format($purchase->amount_paid, 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:14px;font-weight:700;">
                        <span style="color:#475569;">Amount Due:</span>
                        <span style="color:{{ $purchase->amount_due > 0 ? '#ef4444' : '#16a34a' }};">৳ {{ number_format($purchase->amount_due, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Danger Zone --}}
            @if($purchase->status !== 'received')
                <div class="card" style="border-color:#fecaca;">
                    <div class="card-header" style="background:#fef2f2;border-bottom-color:#fecaca;">
                        <span class="card-title" style="color:#b91c1c;"><i class="bi bi-shield-exclamation"></i> Danger Zone</span>
                    </div>
                    <div class="card-body">
                        <p style="font-size:13px;color:#64748b;margin:0 0 14px 0;">Deleting this purchase order will reverse any supplier balance adjustments that were recorded.</p>
                        <form id="delete-form" method="POST" action="{{ route('dashboard.purchases.destroy', $purchase) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn" style="background:#fef2f2;border:1px solid #fca5a5;color:#b91c1c;width:100%;justify-content:center;" onclick="confirmDelete()">
                                <i class="bi bi-trash"></i> Delete Purchase Order
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function confirmReceive() {
            Swal.fire({
                title: 'Confirm Stock Receipt',
                html: `
                    <div style="text-align:left;font-size:14px;color:#374151;line-height:1.6;">
                        <p>Marking this order as <strong>Received</strong> will:</p>
                        <ul style="padding-left:20px;margin:10px 0;">
                            <li>Update stock quantities for all items in this order.</li>
                            <li>Update the cost price of each product to the purchase price.</li>
                        </ul>
                        <p style="color:#b91c1c;font-weight:600;">This action cannot be undone.</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-box-arrow-in-down"></i> Yes, Receive It!',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('receive-form').submit();
                }
            });
        }

        function confirmDelete() {
            Swal.fire({
                title: 'Delete Purchase Order?',
                text: "This purchase order will be permanently removed. Supplier balances will be reversed.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Delete It!',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form').submit();
                }
            });
        }
    </script>

    <style>
        @media print {
            .sidebar, .topbar, .btn-topbar, .btn, form[id="receive-form"], form[id="delete-form"], [style*="Danger Zone"] { display: none !important; }
            .main-wrapper { margin-left: 0 !important; }
            .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        }
    </style>
</x-layouts.admin>
