<x-layouts.admin title="Reports Dashboard">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div>
            <h2 style="font-size:24px;font-weight:800;color:#0f172a;margin:0;letter-spacing:-0.5px;">Reports Dashboard</h2>
            <p style="font-size:14px;color:#64748b;margin:4px 0 0 0;">View and analyze your business data.</p>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;">
        
        <!-- Sales Report Card -->
        <a href="{{ route('dashboard.reports.sales') }}" style="text-decoration:none;">
            <div class="card" style="transition: transform 0.2s, box-shadow 0.2s;">
                <div class="card-body" style="display:flex;align-items:center;gap:16px;">
                    <div style="width:56px;height:56px;border-radius:12px;background:rgba(99,102,241,0.1);display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:24px;">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div>
                        <h3 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;">Sales Report</h3>
                        <p style="font-size:13px;color:#64748b;margin:4px 0 0 0;">View total sales, revenue, and taxes over time.</p>
                    </div>
                </div>
            </div>
        </a>

        <!-- Purchases Report Card -->
        <a href="{{ route('dashboard.reports.purchases') }}" style="text-decoration:none;">
            <div class="card" style="transition: transform 0.2s, box-shadow 0.2s;">
                <div class="card-body" style="display:flex;align-items:center;gap:16px;">
                    <div style="width:56px;height:56px;border-radius:12px;background:rgba(245,158,11,0.1);display:flex;align-items:center;justify-content:center;color:var(--warning);font-size:24px;">
                        <i class="bi bi-cart4"></i>
                    </div>
                    <div>
                        <h3 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;">Purchases Report</h3>
                        <p style="font-size:13px;color:#64748b;margin:4px 0 0 0;">Track expenses on raw materials and supplies.</p>
                    </div>
                </div>
            </div>
        </a>

        <!-- Stock Report Card -->
        <a href="{{ route('dashboard.reports.stock') }}" style="text-decoration:none;">
            <div class="card" style="transition: transform 0.2s, box-shadow 0.2s;">
                <div class="card-body" style="display:flex;align-items:center;gap:16px;">
                    <div style="width:56px;height:56px;border-radius:12px;background:rgba(16,185,129,0.1);display:flex;align-items:center;justify-content:center;color:var(--success);font-size:24px;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <h3 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;">Stock Report</h3>
                        <p style="font-size:13px;color:#64748b;margin:4px 0 0 0;">View current inventory levels and valuations.</p>
                    </div>
                </div>
            </div>
        </a>

        <!-- Production Report Card -->
        <a href="{{ route('dashboard.reports.production') }}" style="text-decoration:none;">
            <div class="card" style="transition: transform 0.2s, box-shadow 0.2s;">
                <div class="card-body" style="display:flex;align-items:center;gap:16px;">
                    <div style="width:56px;height:56px;border-radius:12px;background:rgba(239,68,68,0.1);display:flex;align-items:center;justify-content:center;color:var(--danger);font-size:24px;">
                        <i class="bi bi-gear"></i>
                    </div>
                    <div>
                        <h3 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;">Production Report</h3>
                        <p style="font-size:13px;color:#64748b;margin:4px 0 0 0;">Track bakery outputs and ingredient wastage.</p>
                    </div>
                </div>
            </div>
        </a>

        <!-- Profit/Loss Report Card -->
        <a href="{{ route('dashboard.reports.profit-loss') }}" style="text-decoration:none;">
            <div class="card" style="transition: transform 0.2s, box-shadow 0.2s;">
                <div class="card-body" style="display:flex;align-items:center;gap:16px;">
                    <div style="width:56px;height:56px;border-radius:12px;background:rgba(139,92,246,0.1);display:flex;align-items:center;justify-content:center;color:#8b5cf6;font-size:24px;">
                        <i class="bi bi-pie-chart"></i>
                    </div>
                    <div>
                        <h3 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;">Profit / Loss</h3>
                        <p style="font-size:13px;color:#64748b;margin:4px 0 0 0;">Calculate gross profits and COGS margins.</p>
                    </div>
                </div>
            </div>
        </a>

    </div>

    <style>
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
        }
    </style>
</x-layouts.admin>
