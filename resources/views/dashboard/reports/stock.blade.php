<x-layouts.admin title="Stock Report">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
        <a href="{{ route('dashboard.reports.index') }}" class="btn-topbar" style="padding:8px 12px;text-decoration:none;"><i class="bi bi-arrow-left"></i> Back</a>
        <div>
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Stock Report</h2>
            <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">View current inventory levels and valuations.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom:24px;">
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard.reports.stock') }}" style="display:flex;gap:16px;align-items:flex-end;flex-wrap:wrap;">
                <div class="form-group" style="margin:0;flex:1;min-width:200px;">
                    <label class="form-label" for="product_type">Product Type</label>
                    <select name="product_type" id="product_type" class="form-control">
                        <option value="">All Types</option>
                        <option value="raw_material" {{ request('product_type') == 'raw_material' ? 'selected' : '' }}>Raw Material</option>
                        <option value="ready_made" {{ request('product_type') == 'ready_made' ? 'selected' : '' }}>Ready Made (Resale)</option>
                        <option value="finished_product" {{ request('product_type') == 'finished_product' ? 'selected' : '' }}>Finished Product (Bakery)</option>
                    </select>
                </div>
                <div class="form-group" style="margin:0;flex:1;min-width:200px;">
                    <label class="form-label" for="category_id">Category</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-filter"></i> Filter</button>
                    <a href="{{ route('dashboard.reports.stock') }}" class="btn btn-outline">Clear</a>
                    <button type="button" onclick="window.print()" class="btn btn-outline"><i class="bi bi-printer"></i> Print</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:24px;">
        <div class="card">
            <div class="card-body" style="padding:16px 20px;">
                <div style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;margin-bottom:6px;">Total Unique Items</div>
                <div style="font-size:24px;font-weight:800;color:#0f172a;">{{ $summary['total_items'] }}</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body" style="padding:16px 20px;">
                <div style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;margin-bottom:6px;">Total Stock Value</div>
                <div style="font-size:24px;font-weight:800;color:#10b981;">৳ {{ number_format($summary['total_value'], 2) }}</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body" style="padding:16px 20px;">
                <div style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;margin-bottom:6px;">Low Stock Items</div>
                <div style="font-size:24px;font-weight:800;color:#ef4444;">{{ $summary['low_stock_items'] }}</div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-body" style="padding:0;overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13.5px;text-align:left;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#475569;">
                        <th style="padding:12px 16px;">Product Name</th>
                        <th style="padding:12px 16px;">Type</th>
                        <th style="padding:12px 16px;">Category</th>
                        <th style="padding:12px 16px;text-align:right;">Current Stock</th>
                        <th style="padding:12px 16px;text-align:right;">Avg Unit Cost (৳)</th>
                        <th style="padding:12px 16px;text-align:right;">Total Value (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:12px 16px;">
                                <div style="font-weight:700;color:#0f172a;">{{ $product->name }}</div>
                                <div style="font-size:11.5px;color:#64748b;font-family:monospace;">{{ $product->sku }}</div>
                            </td>
                            <td style="padding:12px 16px;">
                                @if($product->product_type == 'raw_material')
                                    <span style="background:#f1f5f9;color:#475569;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;">Raw Material</span>
                                @elseif($product->product_type == 'finished_product')
                                    <span style="background:#f0fdfa;color:#0f766e;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;">Finished Product</span>
                                @else
                                    <span style="background:#eff6ff;color:#1d4ed8;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;">Ready Made</span>
                                @endif
                            </td>
                            <td style="padding:12px 16px;">{{ $product->category ? $product->category->name : 'Uncategorized' }}</td>
                            <td style="padding:12px 16px;text-align:right;font-weight:600; {{ $product->stock_qty <= 10 ? 'color:#ef4444;' : 'color:#10b981;' }}">
                                {{ floatval($product->stock_qty) }} {{ $product->unit ? $product->unit->short_name : 'pcs' }}
                            </td>
                            <td style="padding:12px 16px;text-align:right;color:#475569;">{{ number_format($product->cost_price, 2) }}</td>
                            <td style="padding:12px 16px;text-align:right;font-weight:700;color:#0f172a;">{{ number_format($product->stock_qty * $product->cost_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:30px;text-align:center;color:#64748b;">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <style type="text/css" media="print">
        @page { size: portrait; }
        body { background: #fff !important; }
        .sidebar, .topbar, .btn-topbar, .card form, .btn { display: none !important; }
        .main-wrapper { margin: 0 !important; }
        .card { box-shadow: none !important; border: none !important; }
        .card-body { padding: 0 !important; }
    </style>
</x-layouts.admin>
