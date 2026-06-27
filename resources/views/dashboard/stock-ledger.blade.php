<x-layouts.admin title="Stock Ledger">
    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes modalScale {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
    </style>

    <div x-data="{ showAdjustModal: false, selectedType: 'Adjustment (+)', auditDirection: 'add' }" style="display:contents;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
            <div>
                <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Stock Ledger</h2>
                <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">Complete audit trail of inventory updates,
                    production increments, sales deductions, and adjustments.</p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <a href="{{ route('dashboard.stock-ledger.export') }}" class="btn btn-outline"
                    style="text-decoration:none;display:inline-flex;align-items:center;gap:7px;">
                    <i class="bi bi-download"></i> Export Excel
                </a>
                <button @click="showAdjustModal = true" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Stock Adjustment
                </button>
            </div>
        </div>

        <!-- Ledger Table -->
        <div class="card">
            <div class="card-body" style="padding:0;overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;text-align:left;font-size:13.5px;">
                    <thead>
                        <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#475569;font-weight:600;">
                            <th style="padding:16px 20px;">Ref ID</th>
                            <th style="padding:16px 20px;">Product</th>
                            <th style="padding:16px 20px;">Movement Type</th>
                            <th style="padding:16px 20px;text-align:center;">Quantity</th>
                            <th style="padding:16px 20px;">Updated By</th>
                            <th style="padding:16px 20px;">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody style="color:#334155;">
                        @forelse($ledger as $row)
                            <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s;"
                                onmouseover="this.style.background='#f8fafc'"
                                onmouseout="this.style.background='transparent'">
                                <td style="padding:14px 20px;font-family:monospace;color:#64748b;font-weight:600;">
                                    #TXN-{{ $row->id }}</td>
                                <td style="padding:14px 20px;font-weight:700;color:#0f172a;">
                                    {{ $row->product?->name ?? 'Deleted Product' }}
                                    @if ($row->product?->sku)
                                        <span
                                            style="font-size:11px;color:#94a3b8;font-weight:400;margin-left:4px;">({{ $row->product->sku }})</span>
                                    @endif
                                    @if ($row->notes)
                                        <div style="font-size:11.5px;color:#64748b;font-weight:400;margin-top:3px;">
                                            <i class="bi bi-info-circle" style="font-size:11px;"></i>
                                            {{ $row->notes }}
                                        </div>
                                    @endif
                                </td>
                                <td style="padding:14px 20px;">
                                    @if (strpos($row->type, '(+)') !== false)
                                        <span style="color:#16a34a;font-weight:600;"><i
                                                class="bi bi-arrow-up-right-circle"></i> {{ $row->type }}</span>
                                    @elseif(strpos($row->type, '(-)') !== false)
                                        <span style="color:#dc2626;font-weight:600;"><i
                                                class="bi bi-arrow-down-left-circle"></i> {{ $row->type }}</span>
                                    @else
                                        <span style="color:#ea580c;font-weight:600;"><i
                                                class="bi bi-arrow-left-right"></i> {{ $row->type }}</span>
                                    @endif
                                </td>
                                <td
                                    style="padding:14px 20px;text-align:center;font-weight:700;color:{{ $row->qty > 0 ? '#16a34a' : '#dc2626' }}">
                                    {{ $row->qty > 0 ? '+' : '' }}{{ number_format($row->qty, 2) }}
                                </td>
                                <td style="padding:14px 20px;"><i class="bi bi-person-fill" style="color:#64748b;"></i>
                                    {{ $row->user?->name ?? 'System' }}</td>
                                <td style="padding:14px 20px;color:#64748b;"><i class="bi bi-clock"></i>
                                    {{ $row->created_at->format('Y-m-d h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding:48px 20px;text-align:center;color:#94a3b8;">
                                    <i class="bi bi-layers-half"
                                        style="font-size:36px;display:block;margin-bottom:10px;"></i>
                                    <span style="font-size:14px;font-weight:500;">No ledger entries found. Perform a
                                        stock adjustment to add logs.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stock Adjustment Modal -->
        <div x-show="showAdjustModal" class="modal-overlay" x-cloak>

            <div @click.away="showAdjustModal = false"
                style="background:#ffffff;width:100%;max-width:550px;border-radius:20px;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);border:1px solid rgba(226,232,240,0.8);overflow:hidden;animation: modalScale 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);">

                <div
                    style="padding:22px 28px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg, rgba(99,102,241,0.05) 0%, rgba(139,92,246,0.02) 100%);">
                    <h3
                        style="margin:0;font-size:17.5px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:10px;">
                        <span
                            style="width:36px;height:36px;border-radius:10px;background:rgba(99,102,241,0.1);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:18px;">
                            <i class="bi bi-sliders"></i>
                        </span>
                        Stock Adjustment
                    </h3>
                    <button type="button" @click="showAdjustModal = false"
                        style="background:none;border:none;color:#94a3b8;font-size:24px;cursor:pointer;line-height:1;padding:4px;display:flex;align-items:center;justify-content:center;transition:color 0.15s;"
                        onmouseover="this.style.color='#475569'" onmouseout="this.style.color='#94a3b8'">
                        <i class="bi bi-x-lg" style="font-size:16px;"></i>
                    </button>
                </div>

                <form action="{{ route('dashboard.stock-ledger.adjust') }}" method="POST" style="margin:0;">
                    @csrf
                    <div style="padding:28px;">
                        <!-- Product Dropdown -->
                        <div class="form-group" style="margin-bottom:20px;">
                            <label class="form-label" for="product_id" style="font-weight:600;margin-bottom:8px;">Select
                                Product <span style="color:var(--danger)">*</span></label>
                            <select name="product_id" id="product_id" class="form-control" required
                                style="height:44px;border-radius:10px;cursor:pointer;">
                                <option value="">-- Choose a Product --</option>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} (SKU:
                                        {{ $p->sku }}) — Stock: {{ number_format($p->stock_qty, 2) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                            <!-- Movement Type -->
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" for="type"
                                    style="font-weight:600;margin-bottom:8px;">Adjustment Type <span
                                        style="color:var(--danger)">*</span></label>
                                <select name="type" id="type" x-model="selectedType" class="form-control"
                                    required style="height:44px;border-radius:10px;cursor:pointer;">
                                    <option value="Adjustment (+)">Adjustment (+)</option>
                                    <option value="Adjustment (-)">Adjustment (-)</option>
                                    <option value="Wastage (-)">Wastage (-)</option>
                                    <option value="Production (+)">Production (+)</option>
                                    <option value="Stock Audit (Adj)">Stock Audit (Adj)</option>
                                </select>
                            </div>

                            <!-- Quantity -->
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" for="qty"
                                    style="font-weight:600;margin-bottom:8px;">Quantity <span
                                        style="color:var(--danger)">*</span></label>
                                <input type="number" name="qty" id="qty" class="form-control"
                                    step="0.001" min="0.001" placeholder="e.g. 10.00" required
                                    style="height:44px;border-radius:10px;">
                            </div>
                        </div>

                        <!-- Conditional Audit Direction -->
                        <div x-show="selectedType === 'Stock Audit (Adj)'"
                            style="background:#f8fafc;border:1px solid #e2e8f0;padding:16px;border-radius:12px;margin-bottom:20px;"
                            x-transition>
                            <label class="form-label" style="font-weight:600;margin-bottom:8px;">Audit Direction <span
                                    style="color:var(--danger)">*</span></label>
                            <div style="display:flex;gap:12px;align-items:center;">
                                <label
                                    :style="auditDirection === 'add' ?
                                        'border-color: #10b981; background: rgba(16,185,129,0.05); color: #065f46;' :
                                        'border-color: #e2e8f0; background: #ffffff; color: #475569;'"
                                    style="flex:1; display:inline-flex;align-items:center;gap:8px;font-size:13.5px;font-weight:600;padding:12px;border:1px solid;border-radius:10px;cursor:pointer;transition:all 0.15s;margin:0;">
                                    <input type="radio" name="direction" value="add" x-model="auditDirection"
                                        style="accent-color:#10b981;width:16px;height:16px;margin:0;">
                                    <span>Add to Stock (+)</span>
                                </label>
                                <label
                                    :style="auditDirection === 'subtract' ?
                                        'border-color: #ef4444; background: rgba(239,68,68,0.05); color: #991b1b;' :
                                        'border-color: #e2e8f0; background: #ffffff; color: #475569;'"
                                    style="flex:1; display:inline-flex;align-items:center;gap:8px;font-size:13.5px;font-weight:600;padding:12px;border:1px solid;border-radius:10px;cursor:pointer;transition:all 0.15s;margin:0;">
                                    <input type="radio" name="direction" value="subtract" x-model="auditDirection"
                                        style="accent-color:#ef4444;width:16px;height:16px;margin:0;">
                                    <span>Deduct Stock (-)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="notes" style="font-weight:600;margin-bottom:8px;">Notes
                                / Reason for Adjustment</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"
                                placeholder="Describe the reason for adjustment..." style="resize:none;padding-top:10px;border-radius:10px;"></textarea>
                        </div>
                    </div>

                    <div
                        style="padding:18px 28px;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end;gap:12px;background:#f8fafc;">
                        <button type="button" @click="showAdjustModal = false" class="btn btn-outline"
                            style="height:42px;border-radius:10px;padding:0 20px;font-weight:600;">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="height:42px;border-radius:10px;padding:0 20px;font-weight:600;display:inline-flex;align-items:center;gap:8px;">
                            <i class="bi bi-check-circle"></i> Save Adjustment
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-layouts.admin>
