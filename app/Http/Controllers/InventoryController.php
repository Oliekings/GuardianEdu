<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\InventorySupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function categoryIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $categories = InventoryCategory::where('school_id', $schoolId)->get();

        return Inertia::render('Accountant/Inventory/Categories', ['categories' => $categories]);
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        InventoryCategory::create([
            'school_id' => Auth::user()->getScopedSchoolId(),
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Inventory category created.');
    }

    public function itemIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $items = InventoryItem::where('school_id', $schoolId)->with('category')->get();
        $categories = InventoryCategory::where('school_id', $schoolId)->get();

        return Inertia::render('Accountant/Inventory/Items', [
            'items' => $items,
            'categories' => $categories,
        ]);
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'inventory_category_id' => 'required|exists:inventory_categories,id',
            'name' => 'required|string',
            'code' => 'required|string',
        ]);
        InventoryItem::create(array_merge($request->all(), [
            'school_id' => Auth::user()->getScopedSchoolId(),
        ]));

        return redirect()->back()->with('success', 'Inventory item registered.');
    }

    public function supplierIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $suppliers = InventorySupplier::where('school_id', $schoolId)->get();

        return Inertia::render('Accountant/Inventory/Suppliers', ['suppliers' => $suppliers]);
    }

    public function storeSupplier(Request $request)
    {
        $request->validate(['name' => 'required|string', 'phone' => 'nullable']);
        InventorySupplier::create(array_merge($request->all(), [
            'school_id' => Auth::user()->getScopedSchoolId(),
        ]));

        return redirect()->back()->with('success', 'Supplier registered.');
    }
}
