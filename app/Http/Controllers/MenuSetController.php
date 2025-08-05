<?php

namespace App\Http\Controllers;

use App\Models\MenuSet;
use Illuminate\Http\Request;

class MenuSetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('menuSets/Index', [
            'menuSets' => MenuSet::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('menuSets/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:products|string|max:255',
            'desc' => 'required|string',
        ]);

        MenuSet::create($request->all());

        return redirect()->route('menuSets.index')->with('message', 'MenuSet created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuSet $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuSet $category)
    {
        return inertia('menuSets/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuSet $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menuSets,name,' . $category->id,
            'desc' => 'required|string',
        ]);

        $category->update($request->all());

        return redirect()->route('menuSets.index')->with('message', 'MenuSet updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuSet $category)
    {
        $category->delete();

        return redirect()->route('menuSets.index')->with('message', 'MenuSet deleted successfully.');
    }
}
