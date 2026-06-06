<div class="stack">
    @if ($statusMessage)
        <div class="notice">{{ $statusMessage }}</div>
    @endif

    @if ($isAdmin)
        <div
            class="card"
            x-data="{
                rows: @entangle('rows'),
                get total() {
                    return this.rows.reduce((sum, row) => sum + (Number(row.qty || 0) * Number(row.price || 0)), 0);
                },
                get formattedTotal() {
                    return this.total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }"
        >
            <div class="flex" style="justify-content: space-between; align-items: center;">
                <div>
                    <h2>{{ $editingPurchaseId ? 'Edit Purchase' : 'New Purchase' }}</h2>
                    <p class="mt-2">Add purchase rows, prevent duplicate item-brand pairs, and watch the total update instantly.</p>
                </div>
                <div class="flex">
                        <button type="button" wire:click="importLegacy" class="button button-secondary">Import Legacy</button>
                    <button type="button" wire:click="addRow" class="button button-primary">+ Add Row</button>
                </div>
            </div>

            <div class="grid mt-4" style="gap: 1rem;">
                @foreach ($rows as $index => $row)
                    <div class="card" style="padding: 1rem;" wire:key="row-{{ $index }}">
                        <div class="grid grid-4" style="gap: 1rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label>Item</label>
                                <input type="text" wire:model.blur="rows.{{ $index }}.item_name" style="width: 100%; box-sizing: border-box;" />
                                @error('rows.' . $index . '.item_name')<div class="error">{{ $message }}</div>@enderror
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label>Brand</label>
                                <input type="text" wire:model.blur="rows.{{ $index }}.brand_name" style="width: 100%; box-sizing: border-box;" />
                                @error('rows.' . $index . '.brand_name')<div class="error">{{ $message }}</div>@enderror
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label>Quantity</label>
                                <input type="number" min="1" x-model.number="rows[{{ $index }}].qty" style="width: 100%; box-sizing: border-box;" />
                                @error('rows.' . $index . '.qty')<div class="error">{{ $message }}</div>@enderror
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label>Price</label>
                                <input type="number" step="0.01" min="0" x-model.number="rows[{{ $index }}].price" style="width: 100%; box-sizing: border-box;" />
                                @error('rows.' . $index . '.price')<div class="error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="flex mt-3" style="justify-content: space-between; align-items: center;">
                            <div class="badge">Row #{{ $index + 1 }}</div>
                            <button type="button" wire:click="removeRow({{ $index }})" class="button button-danger">Remove</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex mt-4" style="justify-content: space-between; align-items: center;">
                <div class="badge">Total: <span x-text="formattedTotal">0.00</span></div>
                <div class="flex">
                    @if ($editingPurchaseId)
                        <button type="button" wire:click="cancelEdit" class="button button-secondary">Cancel</button>
                        <button type="button" wire:click="updatePurchase" class="button button-primary">Update Purchase</button>
                    @else
                        <button type="button" wire:click="savePurchase" class="button button-primary">Save Purchase</button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @livewire('purchase-list')
</div>
