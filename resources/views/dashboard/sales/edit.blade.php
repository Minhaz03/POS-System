<x-layouts.admin title="Edit Invoice — {{ $sale->invoice_no }}">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
        <a href="{{ route('dashboard.sales.show', $sale) }}" class="btn-topbar" style="padding:8px 12px;text-decoration:none;"><i class="bi bi-arrow-left"></i> Back</a>
        <div>
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Edit Invoice</h2>
            <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">
                Editing: <strong style="font-family:monospace;color:#6366f1;">{{ $sale->invoice_no }}</strong>
                &nbsp;&middot;&nbsp; {{ $sale->sale_date->format('d M, Y') }}
            </p>
        </div>
    </div>

    {{-- Info Banner --}}
    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:12px 20px;margin-bottom:24px;display:flex;align-items:center;gap:10px;color:#1d4ed8;font-size:13.5px;">
        <i class="bi bi-info-circle-fill" style="font-size:18px;flex-shrink:0;"></i>
        <div>
            <strong>Limited edit mode.</strong> You may update the customer, payment method, discount, and notes.
            Line items and tax are read-only since this is a completed sale record.
        </div>
    </div>

    @if($errors->any())
        <div class="card" style="margin-bottom:20px;border-color:#fca5a5;background:#fef2f2;color:#991b1b;">
            <div class="card-body" style="padding:16px 20px;">
                <h5 style="margin:0 0 8px 0;font-weight:700;"><i class="bi bi-exclamation-triangle-fill"></i> Please fix the following:</h5>
                <ul style="margin:0;padding-left:20px;font-size:13px;">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('dashboard.sales.update', $sale) }}">
        @csrf
        @method('PUT')

        <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start;">

            {{-- Left: Editable Fields + Read-only Items --}}
            <div style="display:flex;flex-direction:column;gap:24px;">

                {{-- Editable Fields --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title"><i class="bi bi-pencil-square"></i> Editable Details</span>
                    </div>
                    <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="form-group">
                            <label class="form-label" for="customer_id">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control" style="cursor:pointer;">
                                <option value="">Walk-in Customer (No Account)</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $sale->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} @if($customer->phone) · {{ $customer->phone }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="payment_method">Payment Method <span style="color:#ef4444;">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-control" style="cursor:pointer;" required>
                                <option value="cash"       {{ old('payment_method', $sale->payment_method) === 'cash'       ? 'selected' : '' }}>Cash</option>
                                <option value="card"       {{ old('payment_method', $sale->payment_method) === 'card'       ? 'selected' : '' }}>Card</option>
                                <option value="mobile_pay" {{ old('payment_method', $sale->payment_method) === 'mobile_pay' ? 'selected' : '' }}>Mobile Pay</option>
                                <option value="credit"     {{ old('payment_method', $sale->payment_method) === 'credit'     ? 'selected' : '' }}>Credit</option>
                            </select>
                        </div>

                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label" for="discount_amount">Discount Amount (৳) <span style="color:#ef4444;">*</span></label>
                            <input type="number" step="0.01" min="0" name="discount_amount" id="discount_amount"
                                class="form-control" style="max-width:240px;"
                                value="{{ old('discount_amount', $sale->discount_amount) }}"
                                oninput="recalculate()" required>
                            <small style="color:#64748b;font-size:12px;margin-top:4px;display:block;">Changing discount will recalculate the grand total. Tax and line items are fixed.</small>
                        </div>

                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label" for="note">Notes</label>
                            <textarea name="note" id="note" rows="3" class="form-control" placeholder="Add a note about this sale...">{{ old('note', $sale->note) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Read-only Line Items --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title"><i class="bi bi-lock"></i> Line Items (Read-only)</span>
                        <span style="margin-left:auto;font-size:11.5px;color:#94a3b8;font-style:italic;">Items cannot be changed on a completed sale</span>
                    </div>
                    <div class="card-body" style="padding:0;overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:13.5px;">
                            <thead>
                                <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#475569;font-weight:600;">
                                    <th style="padding:12px 20px;">Product</th>
                                    <th style="padding:12px 20px;text-align:center;">Quantity</th>
                                    <th style="padding:12px 20px;text-align:right;">Unit Price</th>
                                    <th style="padding:12px 20px;text-align:right;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody style="color:#475569;">
                                @foreach($sale->items as $item)
                                <tr style="border-bottom:1px solid #f1f5f9;">
                                    <td style="padding:11px 20px;font-weight:600;color:#0f172a;">{{ $item->product?->name ?? 'Deleted Product' }}</td>
                                    <td style="padding:11px 20px;text-align:center;">{{ floatval($item->quantity) }} {{ $item->product?->unit?->short_name ?? 'pcs' }}</td>
                                    <td style="padding:11px 20px;text-align:right;">৳ {{ number_format($item->unit_price, 2) }}</td>
                                    <td style="padding:11px 20px;text-align:right;font-weight:700;color:#0f172a;">৳ {{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right: Live Summary --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="bi bi-receipt"></i> Order Summary</span>
                </div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#475569;">
                        <span>Subtotal (fixed):</span>
                        <span style="font-weight:600;color:#0f172a;" id="disp-subtotal">৳ {{ number_format($sale->subtotal, 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#475569;">
                        <span>Discount:</span>
                        <span style="font-weight:600;color:#ef4444;" id="disp-discount">— ৳ {{ number_format($sale->discount_amount, 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#475569;">
                        <span>Tax / VAT (fixed):</span>
                        <span style="font-weight:600;color:#0f172a;" id="disp-tax">+ ৳ {{ number_format($sale->tax_amount, 2) }}</span>
                    </div>
                    <hr style="border:0;border-top:2px solid #e2e8f0;margin:4px 0;">
                    <div style="display:flex;justify-content:space-between;font-size:17px;font-weight:800;color:#0f172a;">
                        <span>Grand Total:</span>
                        <span style="color:#6366f1;" id="disp-grand-total">৳ {{ number_format($sale->grand_total, 2) }}</span>
                    </div>

                    <div style="margin-top:24px;display:flex;flex-direction:column;gap:10px;">
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;"><i class="bi bi-save"></i> Save Changes</button>
                        <a href="{{ route('dashboard.sales.show', $sale) }}" class="btn btn-outline" style="text-decoration:none;justify-content:center;text-align:center;">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        const subtotal = {{ (float)$sale->subtotal }};
        const tax      = {{ (float)$sale->tax_amount }};

        function recalculate() {
            const discount   = parseFloat(document.getElementById('discount_amount').value) || 0;
            const grandTotal = Math.max(0, subtotal - discount + tax);
            document.getElementById('disp-discount').innerText    = '— ৳ ' + discount.toFixed(2);
            document.getElementById('disp-grand-total').innerText = '৳ ' + grandTotal.toFixed(2);
        }
    </script>

</x-layouts.admin>
