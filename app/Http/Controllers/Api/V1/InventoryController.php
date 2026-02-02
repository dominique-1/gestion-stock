<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\StoreInventoryLineRequest;
use App\Models\Inventory;
use App\Models\InventoryLine;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with('user', 'lines.product');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->orderBy('performed_at', 'desc');

        return $query->paginate($request->input('per_page', 15));
    }

    public function store(StoreInventoryRequest $request)
    {
        $inventory = Inventory::create([
            'performed_at' => $request->performed_at,
            'user_id' => $request->user()->id,
            'note' => $request->note,
            'status' => 'draft',
        ]);

        return response()->json($inventory, 201);
    }

    public function show(Inventory $inventory)
    {
        $inventory->load('user', 'lines.product');

        return response()->json($inventory);
    }

    public function close(Inventory $inventory)
    {
        $this->authorize('close', $inventory);

        $inventory->close();

        return response()->json(['message' => 'Inventory closed and adjustments applied']);
    }

    public function destroy(Inventory $inventory)
    {
        $this->authorize('delete', $inventory);

        $inventory->delete();

        return response()->json(null, 204);
    }
}
