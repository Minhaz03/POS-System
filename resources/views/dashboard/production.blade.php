<x-layouts.admin title="Baking Production">

    <div style="display:flex;justify-content:between;align-items:center;margin-bottom:24px;">
        <div>
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Production Batches</h2>
            <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">Monitor active baking kitchen batches, quantities, scheduled timers, and statuses.</p>
        </div>
        <button class="btn btn-primary" style="margin-left:auto;">
            <i class="bi bi-plus-circle"></i> Create Production Batch
        </button>
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
                        <th style="padding:16px 20px;">Date & Time</th>
                        <th style="padding:16px 20px;text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody style="color:#334155;">
                    @foreach($batches as $batch)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px;font-weight:700;color:#0f172a;font-family:monospace;">{{ $batch['id'] }}</td>
                        <td style="padding:14px 20px;font-weight:700;color:#1e293b;">
                            <i class="bi bi-egg-fried" style="color:var(--primary);margin-right:6px;"></i>{{ $batch['recipe'] }}
                        </td>
                        <td style="padding:14px 20px;text-align:center;font-weight:600;">{{ $batch['qty'] }} items</td>
                        <td style="padding:14px 20px;text-align:center;">
                            @if($batch['status'] === 'Completed')
                                <span style="background:#dcfce7;color:#15803d;padding:3px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;"><i class="bi bi-check-circle-fill"></i> Completed</span>
                            @elseif($batch['status'] === 'In Progress')
                                <span style="background:#eff6ff;color:#1d4ed8;padding:3px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;"><i class="bi bi-arrow-repeat" style="display:inline-block;animation:spin 2s linear infinite;"></i> In Progress</span>
                            @else
                                <span style="background:#f1f5f9;color:#475569;padding:3px 8px;border-radius:999px;font-size:11px;font-weight:600;display:inline-block;"><i class="bi bi-calendar-event"></i> Scheduled</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px;color:#64748b;"><i class="bi bi-clock"></i> {{ $batch['date'] }}</td>
                        <td style="padding:14px 20px;text-align:center;font-size:16px;">
                            @if($batch['status'] !== 'Completed')
                                <a href="#" style="color:#10b981;margin-right:12px;" title="Mark Completed"><i class="bi bi-check-lg"></i></a>
                            @endif
                            <a href="#" style="color:#ef4444;" title="Cancel Batch"><i class="bi bi-x-circle"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>

</x-layouts.admin>
