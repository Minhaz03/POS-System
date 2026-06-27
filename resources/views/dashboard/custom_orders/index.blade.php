<x-layouts.admin title="Custom Bakery Orders">

    <div style="display:flex;justify-content:between;align-items:center;margin-bottom:24px;">
        <div>
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Custom Orders</h2>
            <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">Manage custom cake requests, delivery dates, client specifications, and down payments.</p>
        </div>
        <a href="{{ route('dashboard.custom-orders.create') }}" class="btn btn-primary" style="margin-left:auto;text-decoration:none;">
            <i class="bi bi-calendar-plus"></i> New Custom Order
        </a>
    </div>

    <!-- Search Bar & Filters -->
    <div class="card" style="margin-bottom:24px;">
        <div class="card-body" style="padding:16px 20px;">
            <form method="GET" action="{{ route('dashboard.custom-orders') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin:0;">
                <div style="flex:1;min-width:240px;position:relative;">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by Order ID, customer name, phone, or specifications..." style="padding-left:36px;">
                    <i class="bi bi-search" style="position:absolute;left:13px;top:10px;color:#94a3b8;font-size:14px;"></i>
                </div>
                <select name="status" class="form-control" style="width:180px;cursor:pointer;" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Confirmed" {{ request('status') === 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-outline btn-sm">Filter</button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('dashboard.custom-orders') }}" class="btn btn-outline btn-sm" style="color:#ef4444;border-color:#fecaca;">Clear</a>
                @endif
            </form>
        </div>
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
                    @forelse($orders as $order)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px;font-weight:700;color:#0f172a;font-family:monospace;">{{ $order->order_number }}</td>
                        <td style="padding:14px 20px;font-weight:600;color:#334155;">
                            {{ $order->customer?->name ?? 'Unknown Customer' }}
                            @if($order->customer?->phone)
                                <small style="display:block;font-weight:400;color:#64748b;margin-top:2px;"><i class="bi bi-telephone"></i> {{ $order->customer->phone }}</small>
                            @endif
                        </td>
                        <td style="padding:14px 20px;max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $order->details }}">
                            <i class="bi bi-chat-text" style="color:#94a3b8;margin-right:4px;"></i>{{ $order->details }}
                        </td>
                        <td style="padding:14px 20px;text-align:right;font-weight:700;color:#0f172a;">৳ {{ number_format($order->price, 2) }}</td>
                        <td style="padding:14px 20px;text-align:right;font-weight:700;color:#16a34a;">৳ {{ number_format($order->advance, 2) }}</td>
                        <td style="padding:14px 20px;text-align:center;">
                            @if($order->status === 'Confirmed')
                                <span style="background:#dcfce7;color:#15803d;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;">Confirmed</span>
                            @elseif($order->status === 'In Progress')
                                <span style="background:#e0f2fe;color:#0369a1;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;">In Progress</span>
                            @elseif($order->status === 'Completed')
                                <span style="background:#f0fdf4;color:#166534;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;border:1px solid #bbf7d0;">Completed</span>
                            @elseif($order->status === 'Cancelled')
                                <span style="background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;">Cancelled</span>
                            @else
                                <span style="background:#fef3c7;color:#d97706;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;">Pending</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px;font-weight:600;color:#4f46e5;">
                            <i class="bi bi-calendar-event"></i> {{ $order->delivery_date->format('Y-m-d') }}
                        </td>
                        <td style="padding:14px 20px;text-align:center;font-size:16px;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:12px;">
                                <!-- view/print slip -->
                                <a href="{{ route('dashboard.custom-orders.show', $order) }}" style="color:#0ea5e9;" title="Print Slip / View"><i class="bi bi-printer"></i></a>
                                <!-- edit btn -->
                                <a href="{{ route('dashboard.custom-orders.edit', $order) }}" style="color:#6366f1;" title="Edit Order"><i class="bi bi-pencil-square"></i></a>
                                <!-- delete btn -->
                                <form id="delete-order-{{ $order->id }}" method="POST" action="{{ route('dashboard.custom-orders.destroy', $order) }}" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" onclick="confirmDeleteOrder({{ $order->id }}, '{{ $order->order_number }}')" style="background:none;border:none;color:#ef4444;font-size:16px;cursor:pointer;padding:0;" title="Delete Order">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding:48px;text-align:center;color:#64748b;">
                            <i class="bi bi-calendar-x" style="font-size:36px;display:block;margin-bottom:8px;"></i>
                            No custom orders found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div style="margin-top:20px;">
        {{ $orders->links() }}
    </div>

    <script>
        function confirmDeleteOrder(id, orderNumber) {
            Swal.fire({
                title: 'Delete Custom Order?',
                html: `Are you sure you want to delete order <strong>"${orderNumber}"</strong>? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!',
                background: '#ffffff',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-order-' + id).submit();
                }
            });
        }
    </script>

</x-layouts.admin>
