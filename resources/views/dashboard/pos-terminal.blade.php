<x-layouts.admin title="POS Terminal">

    <!-- POS Interface with AlpineJS -->
    <div x-data="posApp()" style="display:grid;grid-template-columns:1fr 380px;gap:20px;height:calc(100vh - 120px);">

        <!-- Left Column: Products Grid -->
        <div style="display:flex;flex-direction:column;gap:16px;height:100%;min-height:0;">
            <!-- Header Controls -->
            <div class="card" style="padding:16px 20px;display:flex;gap:12px;align-items:center;flex-shrink:0;">
                <div style="flex:1;position:relative;">
                    <input type="text" x-model="searchQuery" class="form-control" placeholder="Search menu items..." style="padding-left:36px;">
                    <i class="bi bi-search" style="position:absolute;left:13px;top:10px;color:#94a3b8;font-size:14px;"></i>
                </div>
                <div style="display:flex;gap:6px;">
                    <button class="btn btn-sm" :class="activeCategory === 'All' ? 'btn-primary' : 'btn-outline'" @click="activeCategory = 'All'">All</button>
                    <button class="btn btn-sm" :class="activeCategory === 'Bread' ? 'btn-primary' : 'btn-outline'" @click="activeCategory = 'Bread'">Breads</button>
                    <button class="btn btn-sm" :class="activeCategory === 'Pastry' ? 'btn-primary' : 'btn-outline'" @click="activeCategory = 'Pastry'">Pastries</button>
                    <button class="btn btn-sm" :class="activeCategory === 'Cake' ? 'btn-primary' : 'btn-outline'" @click="activeCategory = 'Cake'">Cakes</button>
                </div>
            </div>

            <!-- Items Catalog -->
            <div style="flex:1;overflow-y:auto;min-height:0;padding-bottom:12px;">
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;">
                    <template x-for="item in filteredItems()" :key="item.id">
                        <div class="card" @click="addToCart(item)" style="cursor:pointer;transition:all 0.15s;border-radius:10px;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                            <div style="padding:18px;text-align:center;background:#fafafa;border-bottom:1px solid #f1f5f9;">
                                <div style="font-size:32px;color:var(--primary);margin-bottom:8px;">
                                    <i :class="'bi ' + item.image"></i>
                                </div>
                                <div style="font-size:13.5px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" x-text="item.name"></div>
                                <div style="font-size:11px;color:#64748b;margin-top:2px;" x-text="item.category"></div>
                            </div>
                            <div style="padding:10px 14px;display:flex;justify-content:between;align-items:center;background:#fff;">
                                <span style="font-weight:800;color:var(--primary);font-size:13.5px;">৳<span x-text="item.price"></span></span>
                                <span style="font-size:10px;padding:1px 6px;border-radius:999px;font-weight:600;" :class="item.stock <= 5 ? 'bg-orange-100 text-orange-800' : 'bg-slate-100 text-slate-800'">
                                    Stock: <span x-text="item.stock"></span>
                                </span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Right Column: Cart Panel -->
        <div class="card" style="display:flex;flex-direction:column;height:100%;min-height:0;background:#fff;border-radius:12px;">
            <div class="card-header" style="display:flex;justify-content:between;align-items:center;flex-shrink:0;">
                <span class="card-title"><i class="bi bi-cart3" style="color:var(--primary);"></i> Current Cart</span>
                <button class="btn btn-outline btn-sm" @click="clearCart()" style="margin-left:auto;padding:4px 8px;font-size:11px;border-color:#fee2e2;color:#b91c1c;">Clear</button>
            </div>

            <!-- Cart Items List -->
            <div style="flex:1;overflow-y:auto;padding:12px;min-height:0;border-bottom:1px solid #f1f5f9;">
                <template x-if="cart.length === 0">
                    <div style="text-align:center;padding:40px 20px;color:#94a3b8;">
                        <i class="bi bi-cart-x" style="font-size:36px;display:block;margin-bottom:8px;"></i>
                        <span style="font-size:13px;">Cart is empty. Click items on the left to add.</span>
                    </div>
                </template>

                <div style="display:flex;flex-direction:column;gap:8px;">
                    <template x-for="cartItem in cart" :key="cartItem.id">
                        <div style="display:flex;align-items:center;gap:10px;padding:8px 10px;background:#f8fafc;border-radius:8px;border:1px solid #f1f5f9;">
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:12.5px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" x-text="cartItem.name"></div>
                                <div style="font-size:11px;color:#64748b;margin-top:2px;">৳<span x-text="cartItem.price"></span> / unit</div>
                            </div>
                            <!-- Qty Controls -->
                            <div style="display:flex;align-items:center;gap:6px;">
                                <button @click="updateQty(cartItem.id, -1)" style="width:22px;height:22px;border-radius:4px;border:1px solid #cbd5e1;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;">-</button>
                                <span style="font-size:12.5px;font-weight:700;width:18px;text-align:center;" x-text="cartItem.qty"></span>
                                <button @click="updateQty(cartItem.id, 1)" style="width:22px;height:22px;border-radius:4px;border:1px solid #cbd5e1;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;">+</button>
                            </div>
                            <!-- Price -->
                            <div style="font-weight:800;color:#0f172a;font-size:12.5px;width:60px;text-align:right;">
                                ৳<span x-text="cartItem.price * cartItem.qty"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Pricing Calculation -->
            <div style="padding:18px;background:#f8fafc;border-top:1px solid #e2e8f0;flex-shrink:0;display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;justify-content:between;font-size:12.5px;color:#475569;">
                    <span>Subtotal</span>
                    <span style="margin-left:auto;font-weight:600;color:#0f172a;">৳<span x-text="subtotal()"></span></span>
                </div>
                <div style="display:flex;justify-content:between;font-size:12.5px;color:#475569;align-items:center;">
                    <span>Discount (৳)</span>
                    <input type="number" x-model.number="discount" min="0" class="form-control" style="width:70px;padding:2px 6px;font-size:12px;height:26px;text-align:right;margin-left:auto;">
                </div>
                <div style="display:flex;justify-content:between;font-size:12.5px;color:#475569;">
                    <span>VAT / Tax (5%)</span>
                    <span style="margin-left:auto;font-weight:600;color:#0f172a;">৳<span x-text="tax()"></span></span>
                </div>
                <div style="display:flex;justify-content:between;font-size:15px;font-weight:800;color:#0f172a;border-top:1px dashed #cbd5e1;padding-top:10px;margin-top:4px;">
                    <span>Total Bill</span>
                    <span style="margin-left:auto;color:var(--primary);">৳<span x-text="total()"></span></span>
                </div>

                <button class="btn btn-primary" @click="checkout()" :disabled="cart.length === 0" style="width:100%;margin-top:12px;justify-content:center;height:44px;font-size:14px;">
                    <i class="bi bi-wallet2"></i> Pay & Print Receipt
                </button>
            </div>
        </div>

    </div>

    <script>
        function posApp() {
            return {
                searchQuery: '',
                activeCategory: 'All',
                discount: 0,
                items: @json($posItems),
                cart: [],

                filteredItems() {
                    return this.items.filter(item => {
                        const matchesSearch = item.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                        const matchesCategory = this.activeCategory === 'All' || item.category === this.activeCategory;
                        return matchesSearch && matchesCategory;
                    });
                },

                addToCart(item) {
                    const existing = this.cart.find(c => c.id === item.id);
                    if (existing) {
                        if (existing.qty < item.stock) {
                            existing.qty++;
                        } else {
                            Swal.fire('Stock Limit Reached', 'Cannot exceed available stock of ' + item.stock + ' items.', 'warning');
                        }
                    } else {
                        if (item.stock > 0) {
                            this.cart.push({
                                id: item.id,
                                name: item.name,
                                price: item.price,
                                qty: 1
                            });
                        } else {
                            Swal.fire('Out of Stock', 'This product is currently out of stock.', 'error');
                        }
                    }
                },

                updateQty(id, amount) {
                    const item = this.cart.find(c => c.id === id);
                    const catalogItem = this.items.find(i => i.id === id);
                    if (item) {
                        item.qty += amount;
                        if (item.qty <= 0) {
                            this.cart = this.cart.filter(c => c.id !== id);
                        } else if (item.qty > catalogItem.stock) {
                            item.qty = catalogItem.stock;
                            Swal.fire('Stock Limit Reached', 'Cannot exceed available stock.', 'warning');
                        }
                    }
                },

                clearCart() {
                    this.cart = [];
                    this.discount = 0;
                },

                subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                tax() {
                    return Math.round(this.subtotal() * 0.05);
                },

                total() {
                    const sum = this.subtotal() - this.discount + this.tax();
                    return sum > 0 ? sum : 0;
                },

                checkout() {
                    const totalBill = this.total();
                    Swal.fire({
                        title: 'Order Placed Successfully!',
                        html: `<div style="font-size: 15px; margin-top: 8px;">Total Bill: <strong style="color: #16a34a; font-size: 18px;">৳${totalBill}</strong></div><div style="font-size: 13px; color: #64748b; margin-top: 6px;"><i class="bi bi-printer"></i> Receipt printed.</div>`,
                        icon: 'success',
                        confirmButtonColor: '#6366f1',
                        confirmButtonText: 'OK'
                    });
                    this.clearCart();
                }
            }
        }
    </script>

</x-layouts.admin>
