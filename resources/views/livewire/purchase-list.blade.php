<div class="card mt-4">
    <div class="flex" style="justify-content: space-between; align-items: center;">
        <h2>Purchase List</h2>
        <span class="badge">{{ $purchases->count() }} records</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Items</th>
                <th>Total</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $purchase)
                <tr wire:key="purchase-{{ $purchase->id }}">
                    <td>{{ $purchase->id }}</td>
                    <td>
                        @foreach ($purchase->items as $item)
                            <div>{{ $item->item->name }} / {{ $item->brand->name }} × {{ $item->qty }} @ {{ number_format($item->price, 2) }}</div>
                        @endforeach
                    </td>
                    <td>{{ number_format($purchase->total, 2) }}</td>
                    <td>{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                    <td class="flex">
                        @if ($isAdmin)
                            <button type="button" wire:click="editPurchase({{ $purchase->id }})" class="button button-secondary" wire:loading.attr="disabled">Edit</button>
                            <button type="button" wire:click="deletePurchase({{ $purchase->id }})" class="button button-danger" wire:loading.attr="disabled">Delete</button>
                        @else
                            <span class="badge">Read-only</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
