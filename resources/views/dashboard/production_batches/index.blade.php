<x-layouts.admin title="Baking Production">

    <div style="display:flex;justify-content:between;align-items:center;margin-bottom:24px;">
        <div>
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Production Batches</h2>
            <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">Monitor active baking kitchen batches, quantities, scheduled timers, and statuses.</p>
        </div>
        <a href="{{ route('dashboard.production.create') }}" class="btn btn-primary" style="margin-left:auto;text-decoration:none;">
            <i class="bi bi-plus-circle"></i> Create Production Batch
        </a>
    </div>

    <!-- Search & Filters -->
    <div class="card" style="margin-bottom:24px;">
        <div class="card-body" style="padding:16px 20px;">
            <form method="GET" action="{{ route('dashboard.production') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin:0;">
                <div style="flex:1;min-width:240px;position:relative;">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by Batch ID or Recipe name..." style="padding-left:36px;">
                    <i class="bi bi-search" style="position:absolute;left:13px;top:10px;color:#94a3b8;font-size:14px;"></i>
                </div>
                <select name="status" class="form-control" style="width:180px;cursor:pointer;" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="Scheduled" {{ request('status') === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-outline btn-sm">Filter</button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('dashboard.production') }}" class="btn btn-outline btn-sm" style="color:#ef4444;border-color:#fecaca;">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Production Table -->
    <div class="card">
        <div class="card-body" style="padding:0;overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:13.5px;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#475569;font-weight:600;">
                        <th style="padding:16px 20px;">Batch ID</th>
                        <th style="padding:16px 20px;">Recipe Name</th>
                        <th style="padding:16px 20px;text-align:center;">Target Qty</th>
                        <th style="padding:16px 20px;text-align:center;">Status</th>
                        <th style="padding:16px 20px;">Scheduled Date & Time</th>
                        <th style="padding:16px 20px;text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody style="color:#334155;">
                    @forelse($batches as $batch)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px;font-weight:700;color:#0f172a;font-family:monospace;">{{ $batch->batch_code }}</td>
                        <td style="padding:14px 20px;font-weight:700;color:#1e293b;">
                            <i class="bi bi-egg-fried" style="color:var(--primary);margin-right:6px;"></i>{{ $batch->recipe?->name ?? 'Unknown Recipe' }}
                        </td>
                        <td style="padding:14px 20px;text-align:center;font-weight:600;">
                            {{ floatval($batch->qty) }} {{ $batch->recipe?->yield_unit ?? 'items' }}
                        </td>
                        <td style="padding:14px 20px;text-align:center;">
                            @if($batch->status === 'Completed')
                                <span style="background:#dcfce7;color:#15803d;padding:3px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;"><i class="bi bi-check-circle-fill"></i> Completed</span>
                            @elseif($batch->status === 'In Progress')
                                <span style="background:#eff6ff;color:#1d4ed8;padding:3px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;"><i class="bi bi-arrow-repeat" style="display:inline-block;animation:spin 2s linear infinite;"></i> In Progress</span>
                            @elseif($batch->status === 'Cancelled')
                                <span style="background:#fee2e2;color:#991b1b;padding:3px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;"><i class="bi bi-x-circle"></i> Cancelled</span>
                            @else
                                <span style="background:#f1f5f9;color:#475569;padding:3px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;"><i class="bi bi-calendar-event"></i> Scheduled</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px;color:#64748b;">
                            <i class="bi bi-clock"></i> {{ $batch->scheduled_at->format('Y-m-d h:i A') }}
                            @if($batch->completed_at)
                                <small style="display:block;color:#16a34a;margin-top:2px;">Baked: {{ $batch->completed_at->format('h:i A') }}</small>
                            @endif
                        </td>
                        <td style="padding:14px 20px;text-align:center;font-size:16px;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:12px;">
                                @if($batch->status !== 'Completed' && $batch->status !== 'Cancelled')
                                    <!-- Complete Form -->
                                    <form id="complete-batch-{{ $batch->id }}" method="POST" action="{{ route('dashboard.production-batches.complete', $batch) }}" style="display:none;">
                                        @csrf
                                        @method('PATCH')
                                    </form>
                                    <button type="button" onclick="confirmCompleteBatch({{ $batch->id }}, '{{ $batch->batch_code }}')" style="background:none;border:none;color:#10b981;font-size:18px;cursor:pointer;padding:0;" title="Mark Completed">
                                        <i class="bi bi-check-lg"></i>
                                    </button>

                                    <!-- Cancel Form -->
                                    <form id="cancel-batch-{{ $batch->id }}" method="POST" action="{{ route('dashboard.production-batches.cancel', $batch) }}" style="display:none;">
                                        @csrf
                                        @method('PATCH')
                                    </form>
                                    <button type="button" onclick="confirmCancelBatch({{ $batch->id }}, '{{ $batch->batch_code }}')" style="background:none;border:none;color:#f59e0b;font-size:16px;cursor:pointer;padding:0;" title="Cancel Batch">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                @endif

                                @if($batch->status !== 'Completed')
                                    <!-- Delete Form -->
                                    <form id="delete-batch-{{ $batch->id }}" method="POST" action="{{ route('dashboard.production-batches.destroy', $batch) }}" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" onclick="confirmDeleteBatch({{ $batch->id }}, '{{ $batch->batch_code }}')" style="background:none;border:none;color:#ef4444;font-size:16px;cursor:pointer;padding:0;" title="Delete Batch">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:48px;text-align:center;color:#64748b;">
                            <i class="bi bi-clipboard-x" style="font-size:36px;display:block;margin-bottom:8px;"></i>
                            No production batches scheduled.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div style="margin-top:20px;">
        {{ $batches->links() }}
    </div>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>

    <script>
        function confirmCompleteBatch(id, code) {
            Swal.fire({
                title: 'Complete Production Batch?',
                html: `Mark batch <strong>"${code}"</strong> as Completed? This will increment recipe product stock and log inventory additions.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, complete it!',
                background: '#ffffff',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('complete-batch-' + id).submit();
                }
            });
        }

        function confirmCancelBatch(id, code) {
            Swal.fire({
                title: 'Cancel Production Batch?',
                html: `Are you sure you want to cancel batch <strong>"${code}"</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, cancel it!',
                background: '#ffffff',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancel-batch-' + id).submit();
                }
            });
        }

        function confirmDeleteBatch(id, code) {
            Swal.fire({
                title: 'Delete Production Batch?',
                html: `Are you sure you want to delete batch <strong>"${code}"</strong>? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!',
                background: '#ffffff',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-batch-' + id).submit();
                }
            });
        }
    </script>

</x-layouts.admin>
