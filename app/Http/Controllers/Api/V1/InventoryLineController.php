<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventoryLineRequest;
use App\Models\Inventory;
use App\Models\InventoryLine;
use Illuminate\Http\Request;

class InventoryLineController extends Controller
{
    public function store(StoreInventoryLineRequest $request, Inventory $inventory)
    {
        $validated = $request->validated();
        $validated['inventory_id'] = $inventory->id;

        $line = InventoryLine::create($validated);

        return response()->json($line, 201);
    }

    public function update(Request $request, Inventory $inventory, InventoryLine $line)
    {
        $this->authorize('update', $inventory);

        $validated = $request->validate([
            'qty_expected' => 'sometimes|integer|min:0',
            'qty_counted' => 'sometimes|integer|min:0',
            'justification' => 'nullable|string',
        ]);

        $line->update($validated);

        return response()->json($line);
    }

    public function destroy(Inventory $inventory, InventoryLine $line)
    {
        $this->authorize('delete', $inventory);

        $line->delete();

        return response()->json(null, 204);
    }
}
