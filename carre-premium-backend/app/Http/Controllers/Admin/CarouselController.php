<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    public function index()
    {
        $carousels = Carousel::orderBy('order_position')->get();
        return view('admin.carousels.index', compact('carousels'));
    }

    public function create()
    {
        return view('admin.carousels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_fr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'subtitle_fr' => 'nullable|string',
            'subtitle_en' => 'nullable|string',
            'image' => 'required|image|max:2048',
            'link_url' => 'nullable|url',
            'order_position' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('carousels', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        Carousel::create($validated);

        return redirect()->route('admin.carousels.index')
            ->with('success', 'Carrousel créé avec succès');
    }

    public function edit(string $id)
    {
        $carousel = Carousel::findOrFail($id);
        return view('admin.carousels.edit', compact('carousel'));
    }

    public function update(Request $request, string $id)
    {
        $carousel = Carousel::findOrFail($id);

        $validated = $request->validate([
            'title_fr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'subtitle_fr' => 'nullable|string',
            'subtitle_en' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'link_url' => 'nullable|url',
            'order_position' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($carousel->image) {
                Storage::disk('public')->delete($carousel->image);
            }
            $validated['image'] = $request->file('image')->store('carousels', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $carousel->update($validated);

        return redirect()->route('admin.carousels.index')
            ->with('success', 'Carrousel mis à jour avec succès');
    }

    public function destroy(string $id)
    {
        $carousel = Carousel::findOrFail($id);
        
        if ($carousel->image) {
            Storage::disk('public')->delete($carousel->image);
        }
        
        $carousel->delete();

        return redirect()->route('admin.carousels.index')
            ->with('success', 'Carrousel supprimé avec succès');
    }

    public function toggle(string $id)
    {
        $carousel = Carousel::findOrFail($id);
        $carousel->is_active = !$carousel->is_active;
        $carousel->save();

        return response()->json([
            'success' => true,
            'is_active' => $carousel->is_active
        ]);
    }
}
