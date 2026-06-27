<x-layouts.admin title="Edit Custom Order">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
        <a href="{{ route('dashboard.custom-orders') }}" class="btn-topbar" style="padding:8px 12px;text-decoration:none;"><i class="bi bi-arrow-left"></i> Back</a>
        <div>
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Edit Custom Order: {{ $custom_order->order_number }}</h2>
            <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">Update custom order client requirements, payment progress, status, or dates.</p>
        </div>
    </div>

    <div class="card" style="max-width:750px;">
        <div class="card-body">
            <form method="POST" action="{{ route('dashboard.custom-orders.update', $custom_order) }}">
                @csrf
                @method('PUT')

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="customer_id">Customer <span style="color:#ef4444;">*</span></label>
                        <select name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror" required style="cursor:pointer;">
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $custom_order->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->phone ?? 'No Phone' }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="details">Specifications & Details <span style="color:#ef4444;">*</span></label>
                        <textarea name="details" id="details" rows="4" class="form-control @error('details') is-invalid @enderror" required placeholder="e.g. 2kg Chocolate Red Velvet Cake, heart-shaped, written: 'Happy Anniversary Mom & Dad', gold roses frosting decoration...">{{ old('details', $custom_order->details) }}</textarea>
                        @error('details')
                            <span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="price">Total Cost (৳) <span style="color:#ef4444;">*</span></label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $custom_order->price) }}" required min="0">
                        @error('price')
                            <span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="advance">Advance Paid (৳) <span style="color:#ef4444;">*</span></label>
                        <input type="number" step="0.01" name="advance" id="advance" class="form-control @error('advance') is-invalid @enderror" value="{{ old('advance', $custom_order->advance) }}" required min="0">
                        @error('advance')
                            <span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="delivery_date">Delivery Date <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="delivery_date" id="delivery_date" class="form-control @error('delivery_date') is-invalid @enderror" value="{{ old('delivery_date', $custom_order->delivery_date->format('Y-m-d')) }}" required>
                        @error('delivery_date')
                            <span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Order Status <span style="color:#ef4444;">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required style="cursor:pointer;">
                            <option value="Pending" {{ old('status', $custom_order->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Confirmed" {{ old('status', $custom_order->status) === 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="In Progress" {{ old('status', $custom_order->status) === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ old('status', $custom_order->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Cancelled" {{ old('status', $custom_order->status) === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="margin-top:32px;display:flex;gap:12px;border-top:1px solid #f1f5f9;padding-top:20px;">
                    <button type="submit" class="btn btn-primary">Update Order</button>
                    <a href="{{ route('dashboard.custom-orders') }}" class="btn btn-outline" style="text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>
