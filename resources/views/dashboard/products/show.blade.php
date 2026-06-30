<x-layouts.admin title="Product Details">

    <!-- Top Navigation and Action Bar -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('dashboard.products') }}" class="btn-topbar" style="padding:8px 12px;text-decoration:none;">
                <i class="bi bi-arrow-left"></i> Back to Products
            </a>
            <div>
                <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">{{ $product->name }}</h2>
                <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">Viewing product details, inventory status, and price records.</p>
            </div>
        </div>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-primary" style="text-decoration:none;background:#6366f1;">
                <i class="bi bi-pencil-square"></i> Edit Product
            </a>
            <form method="POST" action="{{ route('dashboard.products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product?')" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline" style="color:#ef4444;border-color:#fecaca;background:#fef2f2;">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Product Layout Grid -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(360px, 1fr));gap:24px;margin-bottom:32px;">
        
        <!-- Left Column: Identity & Identifiers -->
        <div class="card">
            <div class="card-header" style="background:#f8fafc;">
                <span class="card-title"><i class="bi bi-file-text" style="color:var(--primary);"></i> Identity & Info</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:20px;">
                <!-- Product Image -->
                <div style="display:flex;justify-content:center;align-items:center;padding:12px;background:#f8fafc;border-radius:12px;border:1px dashed #cbd5e1;">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-width:100%;max-height:220px;border-radius:8px;object-fit:contain;">
                    @else
                        <div style="height:180px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#94a3b8;gap:8px;">
                            <i class="bi bi-image" style="font-size:48px;"></i>
                            <span style="font-size:13px;">No Image Uploaded</span>
                        </div>
                    @endif
                </div>

                <!-- Basic Specs Table -->
                <table style="width:100%;border-collapse:collapse;font-size:14px;">
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:10px 0;color:#64748b;font-weight:500;width:120px;">SKU</td>
                        <td style="padding:10px 0;font-weight:700;color:#0f172a;font-family:monospace;">{{ $product->sku }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:10px 0;color:#64748b;font-weight:500;">Barcode Value</td>
                        <td style="padding:10px 0;font-weight:600;color:#334155;font-family:monospace;">
                            {{ $product->barcode ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:10px 0;color:#64748b;font-weight:500;">Category</td>
                        <td style="padding:10px 0;color:#0f172a;font-weight:600;">{{ $product->category?->name ?? 'N/A' }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:10px 0;color:#64748b;font-weight:500;">Brand</td>
                        <td style="padding:10px 0;color:#0f172a;font-weight:600;">{{ $product->brand?->name ?? 'N/A' }}</td>
                    </tr>
                </table>

                <!-- Description -->
                <div>
                    <h5 style="margin:0 0 8px 0;font-size:13.5px;color:#64748b;font-weight:600;">Description</h5>
                    <div style="background:#f8fafc;padding:12px;border-radius:8px;font-size:13.5px;color:#475569;line-height:1.6;border:1px solid #e2e8f0;min-height:80px;">
                        {{ $product->description ?? 'No description provided for this product.' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Barcode QR & Financials & Stock -->
        <div style="display:flex;flex-direction:column;gap:24px;">
            
            <!-- Scan QR card -->
            @if($product->barcode)
            <div class="card">
                <div class="card-header" style="background:#f8fafc;">
                    <span class="card-title"><i class="bi bi-qr-code-scan" style="color:var(--primary);"></i> Barcode Scan QR</span>
                </div>
                <div class="card-body" style="display:flex;align-items:center;gap:20px;padding:20px;">
                    <div style="background:#fff;padding:8px;border-radius:10px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode($product->barcode) }}" alt="Barcode Scan QR" style="width:100px;height:100px;display:block;">
                    </div>
                    <div>
                        <h4 style="margin:0 0 4px 0;font-size:15px;color:#0f172a;font-weight:700;">Point of Sale Scanner QR</h4>
                        <p style="margin:0 0 10px 0;font-size:12.5px;color:#64748b;line-height:1.4;">Scan this QR code with any terminal or barcode scanner to instantly fetch or ring up this product.</p>
                        <span style="font-family:monospace;background:#f1f5f9;color:#334155;padding:4px 8px;border-radius:6px;font-size:12.5px;font-weight:700;"><i class="bi bi-upc-scan"></i> {{ $product->barcode }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Pricing Details -->
            <div class="card">
                <div class="card-header" style="background:#f8fafc;">
                    <span class="card-title"><i class="bi bi-tags" style="color:var(--primary);"></i> Pricing & Margins</span>
                </div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:16px;">
                        <div style="background:#f8fafc;padding:12px;border-radius:10px;border:1px solid #e2e8f0;text-align:center;">
                            <span style="font-size:12px;color:#64748b;font-weight:500;display:block;margin-bottom:4px;">Cost Price</span>
                            <span style="font-size:18px;font-weight:800;color:#0f172a;">৳{{ number_format($product->cost_price, 2) }}</span>
                        </div>
                        <div style="background:#e0f2fe;padding:12px;border-radius:10px;border:1px solid #bae6fd;text-align:center;">
                            <span style="font-size:12px;color:#0369a1;font-weight:500;display:block;margin-bottom:4px;">Sale Price</span>
                            <span style="font-size:18px;font-weight:800;color:#0369a1;">৳{{ number_format($product->sale_price, 2) }}</span>
                        </div>
                        <div style="background:#f8fafc;padding:12px;border-radius:10px;border:1px solid #e2e8f0;text-align:center;">
                            <span style="font-size:12px;color:#64748b;font-weight:500;display:block;margin-bottom:4px;">MRP Price</span>
                            <span style="font-size:18px;font-weight:800;color:#0f172a;">৳{{ number_format($product->mrp_price, 2) }}</span>
                        </div>
                    </div>

                    @php
                        $margin = $product->sale_price - $product->cost_price;
                        $marginPercentage = $product->cost_price > 0 ? ($margin / $product->cost_price) * 100 : 0;
                    @endphp

                    <div style="margin-top:16px;padding:12px;background:#f0fdf4;border-radius:8px;border:1px solid #bbf7d0;display:flex;justify-content:between;align-items:center;font-size:13.5px;">
                        <span style="color:#166534;font-weight:500;"><i class="bi bi-graph-up-arrow"></i> Calculated Profit Margin:</span>
                        <strong style="color:#15803d;margin-left:auto;">৳{{ number_format($margin, 2) }} ({{ number_format($marginPercentage, 1) }}%)</strong>
                    </div>
                </div>
            </div>

            <!-- Inventory Controls -->
            <div class="card">
                <div class="card-header" style="background:#f8fafc;">
                    <span class="card-title"><i class="bi bi-boxes" style="color:var(--primary);"></i> Stock Levels</span>
                </div>
                <div class="card-body">
                    <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:16px;">
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:10px 0;color:#64748b;font-weight:500;">Current Stock</td>
                            <td style="padding:10px 0;font-weight:800;font-size:16px;color:{{ $product->stock_qty > $product->alert_qty ? '#16a34a' : ($product->stock_qty > 0 ? '#d97706' : '#dc2626') }}">
                                {{ floatval($product->stock_qty) }} {{ $product->unit?->short_name ?? 'pcs' }}
                            </td>
                        </tr>
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:10px 0;color:#64748b;font-weight:500;">Alert Quantity</td>
                            <td style="padding:10px 0;font-weight:600;color:#475569;">{{ floatval($product->alert_qty) }} {{ $product->unit?->short_name ?? 'pcs' }}</td>
                        </tr>
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:10px 0;color:#64748b;font-weight:500;">Reorder Quantity</td>
                            <td style="padding:10px 0;font-weight:600;color:#475569;">{{ floatval($product->reorder_qty) }} {{ $product->unit?->short_name ?? 'pcs' }}</td>
                        </tr>
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:10px 0;color:#64748b;font-weight:500;">Tax Applied</td>
                            <td style="padding:10px 0;font-weight:600;color:#475569;">
                                {{ $product->tax ? $product->tax->name . ' (' . floatval($product->tax->rate) . '%)' : 'Tax Exempt' }}
                            </td>
                        </tr>
                    </table>

                    <!-- Status Flags badges -->
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <!-- Active -->
                        @if($product->is_active)
                            <span style="background:#dcfce7;color:#15803d;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;"><i class="bi bi-circle-fill" style="font-size:8px;vertical-align:middle;margin-right:4px;"></i> Active</span>
                        @else
                            <span style="background:#f1f5f9;color:#64748b;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;"><i class="bi bi-circle-fill" style="font-size:8px;vertical-align:middle;margin-right:4px;"></i> Inactive</span>
                        @endif

                        <!-- POS Enabled -->
                        @if($product->is_pos_enabled)
                            <span style="background:#e0f2fe;color:#0369a1;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;"><i class="bi bi-calculator" style="margin-right:4px;"></i> POS Checkout</span>
                        @else
                            <span style="background:#f1f5f9;color:#64748b;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;"><i class="bi bi-calculator-fill" style="margin-right:4px;"></i> Not in POS</span>
                        @endif

                        <!-- Bakery item -->
                        @if($product->is_bakery_item)
                            <span style="background:#fdf2f8;color:#be185d;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;"><i class="bi bi-egg-fried" style="margin-right:4px;"></i> Bakery Recipe Item</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Recent Stock Ledger -->
    <div class="card" style="margin-bottom:32px;">
        <div class="card-header" style="background:#f8fafc;display:flex;justify-content:space-between;align-items:center;">
            <span class="card-title" style="margin:0;"><i class="bi bi-journal-text" style="color:var(--primary);"></i> Recent Stock Ledger Entries</span>
            <a href="{{ route('dashboard.stock-ledger', ['product_id' => $product->id]) }}" class="btn btn-sm btn-outline" style="font-size:12.5px;">View Full Ledger</a>
        </div>
        <div class="card-body" style="padding:0;overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:13.5px;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#475569;font-weight:600;">
                        <th style="padding:16px 20px;">Ref ID</th>
                        <th style="padding:16px 20px;">Movement Type</th>
                        <th style="padding:16px 20px;text-align:center;">Quantity</th>
                        <th style="padding:16px 20px;">Updated By</th>
                        <th style="padding:16px 20px;">Date & Time</th>
                    </tr>
                </thead>
                <tbody style="color:#334155;">
                    @forelse($stockLedger as $row)
                        <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding:14px 20px;font-family:monospace;color:#64748b;font-weight:600;">#TXN-{{ $row->id }}</td>
                            <td style="padding:14px 20px;">
                                @if (strpos($row->type, '(+)') !== false)
                                    <span style="color:#16a34a;font-weight:600;"><i class="bi bi-arrow-up-right-circle"></i> {{ $row->type }}</span>
                                @elseif(strpos($row->type, '(-)') !== false)
                                    <span style="color:#dc2626;font-weight:600;"><i class="bi bi-arrow-down-left-circle"></i> {{ $row->type }}</span>
                                @else
                                    <span style="color:#ea580c;font-weight:600;"><i class="bi bi-arrow-left-right"></i> {{ $row->type }}</span>
                                @endif
                                @if ($row->notes)
                                    <div style="font-size:11.5px;color:#64748b;font-weight:400;margin-top:3px;"><i class="bi bi-info-circle" style="font-size:11px;"></i> {{ $row->notes }}</div>
                                @endif
                            </td>
                            <td style="padding:14px 20px;text-align:center;font-weight:700;color:{{ $row->qty > 0 ? '#16a34a' : '#dc2626' }}">
                                {{ $row->qty > 0 ? '+' : '' }}{{ number_format($row->qty, 2) }}
                            </td>
                            <td style="padding:14px 20px;"><i class="bi bi-person-fill" style="color:#64748b;"></i> {{ $row->user?->name ?? 'System' }}</td>
                            <td style="padding:14px 20px;color:#64748b;"><i class="bi bi-clock"></i> {{ $row->created_at->format('Y-m-d h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding:48px 20px;text-align:center;color:#94a3b8;">
                                <i class="bi bi-layers-half" style="font-size:36px;display:block;margin-bottom:10px;"></i>
                                <span style="font-size:14px;font-weight:500;">No ledger entries found for this product.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($stockLedger->hasPages())
            <div style="padding:16px 20px;border-top:1px solid #e2e8f0;background:#fff;">
                {{ $stockLedger->links() }}
            </div>
        @endif
    </div>

</x-layouts.admin>
