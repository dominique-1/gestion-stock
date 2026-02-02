<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index(Request $request)
    {
        $query = Alert::with('product', 'creator');

        if ($request->boolean('unread')) {
            $query->unread();
        }

        if ($request->filled('level')) {
            $query->byLevel($request->level);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $query->latest();

        return $query->paginate($request->input('per_page', 15));
    }

    public function markAsRead(Alert $alert)
    {
        $this->authorize('markAsRead', $alert);

        $alert->markAsRead();

        return response()->json(['message' => 'Alert marked as read']);
    }

    public function destroy(Alert $alert)
    {
        $this->authorize('delete', $alert);

        $alert->delete();

        return response()->json(null, 204);
    }
}
