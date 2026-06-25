<x-layouts.admin title="Add Supplier">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
        <a href="{{ route('dashboard.suppliers') }}" class="btn-topbar" style="padding:8px 12px;"><i class="bi bi-arrow-left"></i> Back</a>
        <div>
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Add New Supplier</h2>
            <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">Add a new vendor to manage raw materials supply and purchases.</p>
        </div>
    </div>

    <div class="card" style="max-width:700px;">
        <div class="card-body">
            <form method="POST" action="{{ route('dashboard.suppliers.store') }}">
                @csrf

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="name">Supplier Company / Name <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Wholesale Flour & Co.">
                        @error('name')
                            <span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contact_person">Contact Person</label>
                        <input type="text" name="contact_person" id="contact_person" class="form-control" value="{{ old('contact_person') }}" placeholder="e.g. Robert Floury">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="e.g. +1555123456">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="e.g. info@supplier.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="city">City</label>
                        <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}" placeholder="e.g. Minneapolis">
                    </div>

                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="address">Address</label>
                        <textarea name="address" id="address" rows="3" class="form-control" placeholder="Complete office/warehouse address...">{{ old('address') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="opening_balance">Opening Balance (৳)</label>
                        <input type="number" step="0.01" name="opening_balance" id="opening_balance" class="form-control" value="{{ old('opening_balance', '0.00') }}" required>
                        <small style="color:#64748b;font-size:11.5px;margin-top:4px;display:block;">Positive = we owe them, Negative = they owe us.</small>
                    </div>
                </div>

                <div class="form-group" style="display:flex;align-items:center;gap:10px;margin-top:24px;">
                    <label class="toggle">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span class="toggle-slider"></span>
                    </label>
                    <span style="font-size:13.5px;font-weight:600;color:#374151;">Active Status</span>
                </div>

                <div style="margin-top:32px;display:flex;gap:12px;border-top:1px solid #f1f5f9;padding-top:20px;">
                    <button type="submit" class="btn btn-primary">Save Supplier</button>
                    <a href="{{ route('dashboard.suppliers') }}" class="btn btn-outline" style="text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>
