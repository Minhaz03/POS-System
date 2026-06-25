<x-layouts.admin title="Edit Recipe – {{ $recipe->name }}">

    {{-- Breadcrumb --}}
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;flex-wrap:wrap;">
        <a href="{{ route('dashboard.recipes') }}" class="btn-topbar" style="padding:8px 12px;text-decoration:none;"><i class="bi bi-arrow-left"></i> Back to Recipes</a>
        <div>
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;"><i class="bi bi-pencil-square"></i> Edit Recipe</h2>
            <p style="font-size:13.5px;color:#64748b;margin:4px 0 0 0;">Editing: <strong>{{ $recipe->name }}</strong></p>
        </div>
        <div style="margin-left:auto;display:flex;gap:10px;">
            <a href="{{ route('dashboard.recipes.show', $recipe) }}" class="btn btn-outline btn-sm" style="text-decoration:none;"><i class="bi bi-eye"></i> View</a>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="card" style="margin-bottom:20px;border-color:#fca5a5;background:#fef2f2;color:#991b1b;">
            <div class="card-body" style="padding:16px 20px;">
                <h5 style="margin:0 0 8px 0;font-weight:700;"><i class="bi bi-exclamation-triangle-fill"></i> Please correct the errors below:</h5>
                <ul style="margin:0;padding-left:20px;font-size:13px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('dashboard.recipes.update', $recipe) }}" id="recipe-form">
        @csrf
        @method('PUT')

        <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start;">

            {{-- LEFT COLUMN --}}
            <div style="display:flex;flex-direction:column;gap:24px;">

                {{-- Basic Info --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title"><i class="bi bi-info-circle"></i> Basic Details</span>
                    </div>
                    <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="form-group" style="grid-column:span 2;">
                            <label class="form-label" for="name">Recipe Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $recipe->name) }}" required placeholder="e.g. Classic Sourdough Bread">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="category">Category</label>
                            <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $recipe->category) }}" list="category-suggestions" placeholder="e.g. Bread, Cake, Pastry">
                            <datalist id="category-suggestions">
                                <option value="Bread">
                                <option value="Pastry">
                                <option value="Cake">
                                <option value="Cookie">
                                <option value="Beverage">
                                <option value="Savory">
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="product_id">Linked Product (Optional)</label>
                            <select name="product_id" id="product_id" class="form-control" style="cursor:pointer;">
                                <option value="">-- No linked product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id', $recipe->product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }} ({{ $product->sku }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="grid-column:span 2;">
                            <label class="form-label" for="description">Short Description</label>
                            <textarea name="description" id="description" rows="2" class="form-control" placeholder="Brief description of this recipe...">{{ old('description', $recipe->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Timing & Yield --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title"><i class="bi bi-hourglass-split"></i> Timing & Yield</span>
                    </div>
                    <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:16px;">
                        <div class="form-group">
                            <label class="form-label" for="prep_time">Prep Time</label>
                            <input type="text" name="prep_time" id="prep_time" class="form-control" value="{{ old('prep_time', $recipe->prep_time) }}" placeholder="e.g. 2 hours">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="bake_time">Bake Time</label>
                            <input type="text" name="bake_time" id="bake_time" class="form-control" value="{{ old('bake_time', $recipe->bake_time) }}" placeholder="e.g. 45 mins">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="yield_qty">Yield Qty <span style="color:#ef4444;">*</span></label>
                            <input type="number" name="yield_qty" id="yield_qty" class="form-control" value="{{ old('yield_qty', $recipe->yield_qty) }}" min="1" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="yield_unit">Yield Unit</label>
                            <input type="text" name="yield_unit" id="yield_unit" class="form-control" value="{{ old('yield_unit', $recipe->yield_unit) }}" placeholder="loaves, pcs, kg">
                        </div>
                    </div>
                </div>

                {{-- Ingredients Table --}}
                <div class="card">
                    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
                        <span class="card-title"><i class="bi bi-list-check"></i> Ingredients</span>
                        <button type="button" id="btn-add-ingredient" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Ingredient</button>
                    </div>
                    <div class="card-body" style="padding:0;">
                        <div style="overflow-x:auto;">
                            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:13px;" id="ingredients-table">
                                <thead>
                                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#475569;font-weight:600;">
                                        <th style="padding:12px 16px;">Ingredient Name <span style="color:#ef4444;">*</span></th>
                                        <th style="padding:12px 16px;width:110px;">Quantity</th>
                                        <th style="padding:12px 16px;width:90px;">Unit</th>
                                        <th style="padding:12px 16px;width:120px;">Unit Cost (৳)</th>
                                        <th style="padding:12px 16px;width:110px;text-align:right;">Subtotal</th>
                                        <th style="padding:12px 16px;width:50px;text-align:center;">Del</th>
                                    </tr>
                                </thead>
                                <tbody id="ingredients-tbody">
                                    {{-- Existing ingredients --}}
                                    @foreach($recipe->ingredients as $ing)
                                    <tr data-row="{{ $loop->index }}" style="border-bottom:1px solid #f1f5f9;">
                                        <td style="padding:10px 14px;">
                                            <div style="display:flex;flex-direction:column;gap:6px;">
                                                <select name="ingredients[product_id][]" class="form-control ing-product-select" style="font-size:13px;">
                                                    <option value="">-- Custom (No link) --</option>
                                                    @foreach($products as $prod)
                                                        <option value="{{ $prod->id }}" data-name="{{ $prod->name }}" data-unit="{{ $prod->unit?->short_name }}" data-cost="{{ $prod->cost_price }}" {{ $ing->product_id == $prod->id ? 'selected' : '' }}>
                                                            {{ $prod->name }} (SKU: {{ $prod->sku }} | Cost: ৳{{ $prod->cost_price }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="ingredients[ingredient_name][]" value="{{ $ing->ingredient_name }}" class="form-control ing-name" placeholder="e.g. Bread Flour" required style="font-size:13px;">
                                            </div>
                                        </td>
                                        <td style="padding:10px 14px;">
                                            <input type="number" step="0.001" min="0" name="ingredients[quantity][]" value="{{ $ing->quantity }}" class="form-control ing-qty" style="font-size:13px;text-align:center;" required>
                                        </td>
                                        <td style="padding:10px 14px;">
                                            <input type="text" name="ingredients[unit][]" value="{{ $ing->unit }}" class="form-control ing-unit" style="font-size:13px;text-align:center;" list="unit-suggestions" placeholder="g">
                                        </td>
                                        <td style="padding:10px 14px;">
                                            <input type="number" step="0.01" min="0" name="ingredients[unit_cost][]" value="{{ $ing->unit_cost }}" class="form-control ing-cost" style="font-size:13px;text-align:right;" required>
                                        </td>
                                        <td style="padding:10px 14px;font-weight:700;color:#0f172a;text-align:right;vertical-align:middle;">
                                            ৳ <span class="ing-subtotal">{{ number_format($ing->subtotal, 2) }}</span>
                                        </td>
                                        <td style="padding:10px 14px;text-align:center;vertical-align:middle;">
                                            <button type="button" class="btn-del-row" style="background:none;border:none;color:#ef4444;font-size:16px;cursor:pointer;padding:0;" title="Remove"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach

                                    @if($recipe->ingredients->count() === 0)
                                    <tr id="empty-ing-row">
                                        <td colspan="6" style="padding:32px;text-align:center;color:#94a3b8;">
                                            <i class="bi bi-basket" style="font-size:28px;display:block;margin-bottom:6px;"></i>
                                            No ingredients added. Click "+ Add Ingredient" to start.
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                                <tfoot id="ingredients-tfoot" style="{{ $recipe->ingredients->count() > 0 ? '' : 'display:none;' }}">
                                    <tr style="background:#f8fafc;border-top:2px solid #e2e8f0;">
                                        <td colspan="4" style="padding:12px 16px;font-weight:700;color:#0f172a;text-align:right;">Estimated Ingredient Cost:</td>
                                        <td style="padding:12px 16px;font-weight:800;color:#16a34a;text-align:right;">৳ <span id="total-cost-display">{{ number_format($recipe->estimated_cost, 2) }}</span></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Instructions --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title"><i class="bi bi-card-text"></i> Preparation Instructions</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group" style="margin:0;">
                            <textarea name="instructions" id="instructions" rows="8" class="form-control" placeholder="Step 1: Mix flour and water...">{{ old('instructions', $recipe->instructions) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN --}}
            <div style="display:flex;flex-direction:column;gap:24px;">

                {{-- Cost Summary --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title"><i class="bi bi-cash-stack"></i> Cost Summary</span>
                    </div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                        <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #86efac;border-radius:10px;padding:16px;text-align:center;">
                            <div style="font-size:11px;text-transform:uppercase;color:#16a34a;font-weight:700;letter-spacing:0.05em;margin-bottom:4px;">Estimated Total Cost</div>
                            <div style="font-size:26px;font-weight:800;color:#15803d;">৳ <span id="summary-cost">{{ number_format($recipe->estimated_cost, 2) }}</span></div>
                            <div style="font-size:11.5px;color:#4ade80;margin-top:4px;">Auto-calculated from ingredients</div>
                        </div>
                    </div>
                </div>

                {{-- Baker's Notes --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title"><i class="bi bi-sticky"></i> Baker's Notes</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group" style="margin:0;">
                            <textarea name="notes" id="notes" rows="4" class="form-control" placeholder="Tips, allergens, storage advice, variations...">{{ old('notes', $recipe->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title"><i class="bi bi-toggle-on"></i> Status</span>
                    </div>
                    <div class="card-body">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $recipe->is_active) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:#6366f1;cursor:pointer;">
                            <span style="font-weight:600;font-size:14px;color:#0f172a;">Recipe is Active</span>
                        </label>
                        <p style="font-size:12px;color:#64748b;margin:8px 0 0 28px;">Inactive recipes are hidden from production and ordering.</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;"><i class="bi bi-save"></i> Update Recipe</button>
                    <a href="{{ route('dashboard.recipes.show', $recipe) }}" class="btn btn-outline" style="text-decoration:none;justify-content:center;text-align:center;"><i class="bi bi-eye"></i> View Recipe</a>
                    <a href="{{ route('dashboard.recipes') }}" class="btn btn-outline" style="text-decoration:none;justify-content:center;text-align:center;">Cancel</a>
                </div>

            </div>
        </div>
    </form>

    <datalist id="unit-suggestions">
        <option value="g">
        <option value="kg">
        <option value="ml">
        <option value="L">
        <option value="pcs">
        <option value="tsp">
        <option value="tbsp">
        <option value="cup">
        <option value="oz">
        <option value="lb">
    </datalist>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const tbody = document.getElementById('ingredients-tbody');
        const tfoot = document.getElementById('ingredients-tfoot');
        const totalDisplay = document.getElementById('total-cost-display');
        const summaryDisplay = document.getElementById('summary-cost');

        const productOptionsHTML = `
            <option value="">-- Custom (No link) --</option>
            @foreach($products as $prod)
                <option value="{{ $prod->id }}" data-name="{{ $prod->name }}" data-unit="{{ $prod->unit?->short_name }}" data-cost="{{ $prod->cost_price }}">
                    {{ $prod->name }} (SKU: {{ $prod->sku }} | Cost: ৳{{ $prod->cost_price }})
                </option>
            @endforeach
        `;

        // Bind existing rows
        tbody.querySelectorAll('tr[data-row]').forEach(function (tr) {
            bindRow(tr);
        });

        // Initial calculation
        recalc();

        document.getElementById('btn-add-ingredient').addEventListener('click', function () {
            addRow();
        });

        function addRow() {
            const existingRows = tbody.querySelectorAll('tr[data-row]');
            const newIdx = existingRows.length;

            const emptyRow = document.getElementById('empty-ing-row');
            if (emptyRow) {
                emptyRow.style.display = 'none';
            }
            tfoot.style.display = '';

            const tr = document.createElement('tr');
            tr.setAttribute('data-row', newIdx);
            tr.style.borderBottom = '1px solid #f1f5f9';
            tr.innerHTML = `
                <td style="padding:10px 14px;">
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        <select name="ingredients[product_id][]" class="form-control ing-product-select" style="font-size:13px;">
                            ${productOptionsHTML}
                        </select>
                        <input type="text" name="ingredients[ingredient_name][]" value="" class="form-control ing-name" placeholder="e.g. Bread Flour" required style="font-size:13px;">
                    </div>
                </td>
                <td style="padding:10px 14px;">
                    <input type="number" step="0.001" min="0" name="ingredients[quantity][]" value="1.000" class="form-control ing-qty" style="font-size:13px;text-align:center;" required>
                </td>
                <td style="padding:10px 14px;">
                    <input type="text" name="ingredients[unit][]" value="g" class="form-control ing-unit" style="font-size:13px;text-align:center;" list="unit-suggestions" placeholder="g">
                </td>
                <td style="padding:10px 14px;">
                    <input type="number" step="0.01" min="0" name="ingredients[unit_cost][]" value="0.00" class="form-control ing-cost" style="font-size:13px;text-align:right;" required>
                </td>
                <td style="padding:10px 14px;font-weight:700;color:#0f172a;text-align:right;vertical-align:middle;">
                    ৳ <span class="ing-subtotal">0.00</span>
                </td>
                <td style="padding:10px 14px;text-align:center;vertical-align:middle;">
                    <button type="button" class="btn-del-row" style="background:none;border:none;color:#ef4444;font-size:16px;cursor:pointer;padding:0;" title="Remove"><i class="bi bi-trash"></i></button>
                </td>
            `;
            tbody.appendChild(tr);
            bindRow(tr);
            recalc();
        }

        function bindRow(tr) {
            const productSelect = tr.querySelector('.ing-product-select');
            const nameInput     = tr.querySelector('.ing-name');
            const qtyInput      = tr.querySelector('.ing-qty');
            const unitInput     = tr.querySelector('.ing-unit');
            const costInput     = tr.querySelector('.ing-cost');
            const delBtn        = tr.querySelector('.btn-del-row');

            productSelect.addEventListener('change', function () {
                const opt = productSelect.options[productSelect.selectedIndex];
                if (opt && opt.value) {
                    nameInput.value = opt.getAttribute('data-name') || '';
                    unitInput.value = opt.getAttribute('data-unit') || '';
                    costInput.value = parseFloat(opt.getAttribute('data-cost') || 0).toFixed(2);
                }
                recalc();
            });

            qtyInput.addEventListener('input', recalc);
            costInput.addEventListener('input', recalc);
            delBtn.addEventListener('click', function () {
                tr.remove();
                if (tbody.querySelectorAll('tr[data-row]').length === 0) {
                    const emptyRow = document.getElementById('empty-ing-row');
                    if (emptyRow) emptyRow.style.display = '';
                    tfoot.style.display = 'none';
                }
                recalc();
            });
        }

        function recalcRow(tr) {
            const qty  = parseFloat(tr.querySelector('.ing-qty').value) || 0;
            const cost = parseFloat(tr.querySelector('.ing-cost').value) || 0;
            tr.querySelector('.ing-subtotal').innerText = (qty * cost).toFixed(2);
        }

        function recalc() {
            let total = 0;
            tbody.querySelectorAll('tr[data-row]').forEach(function (tr) {
                recalcRow(tr);
                total += parseFloat(tr.querySelector('.ing-subtotal').innerText) || 0;
            });
            const f = total.toFixed(2);
            totalDisplay.innerText = f;
            summaryDisplay.innerText = f;
        }

        // Prevent form submit if no ingredients
        document.getElementById('recipe-form').addEventListener('submit', function (e) {
            const rows = tbody.querySelectorAll('tr[data-row]');
            if (rows.length === 0) {
                e.preventDefault();
                Swal.fire('Missing Ingredients', 'Please add at least one ingredient before saving.', 'warning');
            }
        });
    });
    </script>

</x-layouts.admin>
