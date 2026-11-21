<?php

namespace App\Http\Controllers;

use App\Models\FeeCategory;
use Illuminate\Http\Request;

class FeeCategoryController extends Controller
{
    /**
     * Display a listing of fee categories.
     */
    public function index()
    {
        $categories = FeeCategory::latest()->paginate(10);

        return view('fee_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new fee category.
     */
    public function create()
    {
        return view('fee_categories.create');
    }

    /**
     * Store a newly created fee category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:fee_categories,name',
            'description' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        FeeCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('fee-categories.index')
            ->with('success', 'Fee category created successfully.');
    }

    /**
     * Display the specified fee category.
     */
    public function show(FeeCategory $feeCategory)
    {
        return view('fee_categories.show', [
            'category' => $feeCategory,
        ]);
    }

    /**
     * Show the form for editing a fee category.
     */
    public function edit(FeeCategory $feeCategory)
    {
        return view('fee_categories.edit', [
            'category' => $feeCategory,
        ]);
    }

    /**
     * Update the specified fee category.
     */
    public function update(Request $request, FeeCategory $feeCategory)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:fee_categories,name,' . $feeCategory->id,
            'description' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $feeCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('fee-categories.index')
            ->with('success', 'Fee category updated successfully.');
    }

    /**
     * Remove the specified fee category.
     */
    public function destroy(FeeCategory $feeCategory)
    {
        $feeCategory->delete();

        return redirect()
            ->route('fee-categories.index')
            ->with('success', 'Fee category deleted successfully.');
    }
}