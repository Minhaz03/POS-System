<x-layouts.admin title="Create Production Batch">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
        <a href="{{ route('dashboard.production') }}" class="btn-topbar" style="padding:8px 12px;text-decoration:none;"><i class="bi bi-arrow-left"></i> Back</a>
        <div>
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Create Production Batch</h2>
            <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">Schedule or start a new baking production batch in the kitchen.</p>
        </div>
    </div>

    <div class="card" style="max-width:700px;">
        <div class="card-body">
            <form method="POST" action="{{ route('dashboard.production.store') }}">
                @csrf

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="recipe_id">Select Recipe <span style="color:#ef4444;">*</span></label>
                        <select name="recipe_id" id="recipe_id" class="form-control @error('recipe_id') is-invalid @enderror" required style="cursor:pointer;">
                            <option value="">-- Select Recipe --</option>
                            @foreach($recipes as $recipe)
                                <option value="{{ $recipe->id }}" {{ old('recipe_id') == $recipe->id ? 'selected' : '' }}>
                                    {{ $recipe->name }} (Yield: {{ floatval($recipe->yield_qty) }} {{ $recipe->yield_unit ?? 'pcs' }})
                                </option>
                            @endforeach
                        </select>
                        @error('recipe_id')
                            <span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="qty">Target Quantity <span style="color:#ef4444;">*</span></label>
                        <input type="number" step="0.001" name="qty" id="qty" class="form-control @error('qty') is-invalid @enderror" value="{{ old('qty', '1.000') }}" required min="0.001">
                        @error('qty')
                            <span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Initial Status <span style="color:#ef4444;">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required style="cursor:pointer;">
                            <option value="Scheduled" {{ old('status') === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="In Progress" {{ old('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ old('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="scheduled_at">Scheduled Production Date & Time <span style="color:#ef4444;">*</span></label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror" value="{{ old('scheduled_at', date('Y-m-d\TH:i')) }}" required>
                        @error('scheduled_at')
                            <span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="margin-top:32px;display:flex;gap:12px;border-top:1px solid #f1f5f9;padding-top:20px;">
                    <button type="submit" class="btn btn-primary">Save Batch</button>
                    <a href="{{ route('dashboard.production') }}" class="btn btn-outline" style="text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>
