<x-layouts.admin title="Purchase Orders">

    <div style="display:flex;justify-content:between;align-items:center;margin-bottom:24px;">
        <div>
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Purchase Orders</h2>
            <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">Record, track, and manage wholesale supplier purchase invoices and stock input shipments.</p>
        </div>
        <a href="{{ route('dashboard.purchases.create') }}" class="btn btn-primary" style="margin-left:auto;text-decoration:none;">
            <i class="bi bi-plus-circle"></i> Create Purchase Order
        </a>
    </div>

    <!-- Filters and Search Bar -->
    <div class="card" style="margin-bottom:24px;">
        <div class="card-body" style="padding:16px 20px;">
            <form method="GET" action="{{ route('dashboard.purchases') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin:0;">
                <div style="flex:1;min-width:240px;position:relative;">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by reference No. or supplier..." style="padding-left:36px;">
                    <i class="bi bi-search" style="position:absolute;left:13px;top:10px;color:#94a3b8;font-size:14px;"></i>
                </div>
                <select name="status" class="form-control" style="width:180px;cursor:pointer;" onchange="this.form.submit()">
                    <option value="">Shipment Status</option>
                    <option value="ordered" {{ request('status') === 'ordered' ? 'selected' : '' }}>Ordered</option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Received</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                </select>
                <select name="payment_status" class="form-control" style="width:180px;cursor:pointer;" onchange="this.form.submit()">
                    <option value="">Payment Status</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
                <button type="submit" class="btn btn-outline btn-sm">Filter</button>
                @if(request()->anyFilled(['search', 'status', 'payment_status']))
                    <a href="{{ route('dashboard.purchases') }}" class="btn btn-outline btn-sm" style="color:#ef4444;border-color:#fecaca;text-decoration:none;">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="card">
        <div class="card-body" style="padding:0;overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:13.5px;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#475569;font-weight:600;">
                        <th style="padding:16px 20px;">Reference No</th>
                        <th style="padding:16px 20px;">Supplier</th>
                        <th style="padding:16px 20px;">Date</th>
                        <th style="padding:16px 20px;text-align:right;">Grand Total</th>
                        <th style="padding:16px 20px;text-align:right;">Paid</th>
                        <th style="padding:16px 20px;text-align:right;">Due</th>
                        <th style="padding:16px 20px;text-align:center;">Shipment</th>
                        <th style="padding:16px 20px;text-align:center;">Payment</th>
                        <th style="padding:16px 20px;text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody style="color:#334155;">
                    @forelse($purchases as $purchase)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px;font-weight:700;color:#0f172a;font-family:monospace;">{{ $purchase->reference_no }}</td>
                        <td style="padding:14px 20px;font-weight:600;color:#1e293b;">{{ $purchase->supplier?->name ?? 'Walk-in/Unknown' }}</td>
                        <td style="padding:14px 20px;color:#64748b;"><i class="bi bi-calendar3"></i> {{ $purchase->purchase_date->format('Y-m-d') }}</td>
                        <td style="padding:14px 20px;text-align:right;font-weight:700;color:#0f172a;">৳ {{ number_format($purchase->grand_total, 2) }}</td>
                        <td style="padding:14px 20px;text-align:right;font-weight:600;color:#16a34a;">৳ {{ number_format($purchase->amount_paid, 2) }}</td>
                        <td style="padding:14px 20px;text-align:right;font-weight:600;color:#{{ $purchase->amount_due > 0 ? 'ef4444' : '64748b' }};">৳ {{ number_format($purchase->amount_due, 2) }}</td>
                        <td style="padding:14px 20px;text-align:center;">
                            @if($purchase->status === 'received')
                                <span style="background:#dcfce7;color:#15803d;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;"><i class="bi bi-box-seam-fill"></i> Received</span>
                            @elseif($purchase->status === 'partial')
                                <span style="background:#fef3c7;color:#d97706;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;"><i class="bi bi-patch-minus"></i> Partial</span>
                            @elseif($purchase->status === 'returned')
                                <span style="background:#fee2e2;color:#b91c1c;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;"><i class="bi bi-arrow-left-right"></i> Returned</span>
                            @else
                                <span style="background:#eff6ff;color:#1d4ed8;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;"><i class="bi bi-hourglass-split"></i> Ordered</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px;text-align:center;">
                            @if($purchase->payment_status === 'paid')
                                <span style="background:#dcfce7;color:#15803d;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;display:inline-block;">Paid</span>
                            @elseif($purchase->payment_status === 'partial')
                                <span style="background:#fef3c7;color:#d97706;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;display:inline-block;">Partial</span>
                            @else
                                <span style="background:#fee2e2;color:#b91c1c;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;display:inline-block;">Unpaid</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px;text-align:center;font-size:16px;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:12px;">
                                <a href="{{ route('dashboard.purchases.show', $purchase) }}" style="color:#0ea5e9;" title="View Details"><i class="bi bi-eye"></i></a>
                                
                                @if($purchase->status !== 'received' && $purchase->status !== 'returned')
                                    <a href="{{ route('dashboard.purchases.edit', $purchase) }}" style="color:#6366f1;" title="Edit Purchase Order"><i class="bi bi-pencil-square"></i></a>
                                @else
                                    <span style="color:#cbd5e1;cursor:not-allowed;" title="Cannot edit received/returned orders"><i class="bi bi-pencil-square"></i></span>
                                @endif

                                @if($purchase->status !== 'received')
                                    <form id="delete-form-{{ $purchase->id }}" method="POST" action="{{ route('dashboard.purchases.destroy', $purchase) }}" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" onclick="confirmDelete('{{ $purchase->id }}', '{{ $purchase->reference_no }}')" style="background:none;border:none;color:#ef4444;font-size:16px;cursor:pointer;padding:0;" title="Delete Purchase Order"><i class="bi bi-trash"></i></button>
                                @else
                                    <span style="color:#cbd5e1;cursor:not-allowed;" title="Cannot delete received orders"><i class="bi bi-trash"></i></span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding:48px;text-align:center;color:#64748b;">
                            <i class="bi bi-receipt" style="font-size:36px;display:block;margin-bottom:8px;"></i>
                            No purchase orders found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div style="margin-top:20px;">
        {{ $purchases->links() }}
    </div>

    <script>
        function confirmDelete(id, refNo) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Delete purchase order " + refNo + "? This will reverse supplier balances.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!',
                background: '#ffffff',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-layouts.admin>
