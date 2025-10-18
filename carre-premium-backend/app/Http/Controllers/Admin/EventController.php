<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['category']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title_fr', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%");
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(15);

        $stats = [
            'total' => Event::count(),
            'active' => Event::where('is_active', true)->count(),
            'upcoming' => Event::where('event_date', '>=', today())->count(),
            'featured' => Event::where('is_featured', true)->count(),
        ];

        return view('admin.events.index', compact('events', 'stats'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request, ImageUploadService $imageService)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title_fr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'event_type' => 'required|in:sport,concert,theater,festival,other',
            'sport_type' => 'nullable|string|max:100',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'nullable|string',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'end_date' => 'nullable|date|after_or_equal:event_date',
            'end_time' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_url' => 'nullable|url',
            'organizer' => 'nullable|string|max:255',
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['title_fr']) . '-' . time();
        $validated['available_seats'] = $validated['total_seats'];
        
        // Handle main image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $imageService->uploadImage($request->file('image'), 'events');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery')) {
            $galleryPaths = $imageService->uploadMultipleImages($request->file('gallery'), 'events/gallery');
            $validated['gallery'] = json_encode($galleryPaths);
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Événement créé avec succès');
    }

    public function show(string $id)
    {
        $event = Event::with(['category'])->findOrFail($id);
        return view('admin.events.show', compact('event'));
    }

    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, string $id, ImageUploadService $imageService)
    {
        $event = Event::findOrFail($id);
        
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title_fr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'event_type' => 'required|in:sport,concert,theater,festival,other',
            'sport_type' => 'nullable|string|max:100',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'nullable|string',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'end_date' => 'nullable|date|after_or_equal:event_date',
            'end_time' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_url' => 'nullable|url',
            'organizer' => 'nullable|string|max:255',
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
        ]);

        // Handle checkboxes (they won't be in request if unchecked)
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image) {
                $imageService->deleteImage($event->image);
            }
            $validated['image'] = $imageService->uploadImage($request->file('image'), 'events');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery')) {
            // Delete old gallery images if exists
            if ($event->gallery) {
                $oldGallery = json_decode($event->gallery, true);
                if (is_array($oldGallery)) {
                    $imageService->deleteMultipleImages($oldGallery);
                }
            }
            $galleryPaths = $imageService->uploadMultipleImages($request->file('gallery'), 'events/gallery');
            $validated['gallery'] = json_encode($galleryPaths);
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Événement mis à jour avec succès');
    }

    public function destroy(string $id, ImageUploadService $imageService)
    {
        $event = Event::findOrFail($id);
        
        // Delete main image if exists
        if ($event->image) {
            $imageService->deleteImage($event->image);
        }
        
        // Delete gallery images if exists
        if ($event->gallery) {
            $galleryImages = json_decode($event->gallery, true);
            if (is_array($galleryImages)) {
                $imageService->deleteMultipleImages($galleryImages);
            }
        }
        
        $event->delete();
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Événement supprimé avec succès');
    }
    
    public function toggleStatus($id)
    {
        $event = Event::findOrFail($id);
        $event->is_active = !$event->is_active;
        $event->save();

        return redirect()->back()
            ->with('success', 'Statut mis à jour avec succès');
    }
}
