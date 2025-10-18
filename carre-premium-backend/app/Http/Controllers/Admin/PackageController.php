<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use App\Models\Category;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = TourPackage::with(['category']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title_fr', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%");
        }

        if ($request->filled('package_type')) {
            $query->where('package_type', $request->package_type);
        }

        $packages = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => TourPackage::count(),
            'active' => TourPackage::where('is_active', true)->count(),
            'featured' => TourPackage::where('is_featured', true)->count(),
            'avg_price' => TourPackage::avg('price'),
        ];

        return view('admin.packages.index', compact('packages', 'stats'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.packages.create', compact('categories'));
    }

    public function store(Request $request, ImageUploadService $imageService)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title_fr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tour_packages,slug',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'package_type' => 'required|in:helicopter,private_jet,cruise,safari,city_tour,adventure,luxury',
            'destination' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'duration_text_fr' => 'nullable|string|max:100',
            'duration_text_en' => 'nullable|string|max:100',
            'departure_city' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'min_participants' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_url' => 'nullable|url',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title_fr']) . '-' . time();
        }

        // Handle main image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $imageService->uploadImage($request->file('image'), 'packages');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery')) {
            $galleryPaths = $imageService->uploadMultipleImages($request->file('gallery'), 'packages/gallery');
            $validated['gallery'] = json_encode($galleryPaths);
        }

        TourPackage::create($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package créé avec succès');
    }

    public function show(string $id)
    {
        $package = TourPackage::with(['category'])->findOrFail($id);
        return view('admin.packages.show', compact('package'));
    }

    public function edit(string $id)
    {
        $package = TourPackage::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('admin.packages.edit', compact('package', 'categories'));
    }

    public function update(Request $request, string $id, ImageUploadService $imageService)
    {
        $package = TourPackage::findOrFail($id);
        
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title_fr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'package_type' => 'required|in:helicopter,private_jet,cruise,safari,city_tour,adventure,luxury',
            'destination' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'duration_text_fr' => 'nullable|string|max:100',
            'duration_text_en' => 'nullable|string|max:100',
            'departure_city' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'min_participants' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_url' => 'nullable|url',
        ]);

        // Handle checkboxes
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($package->image) {
                $imageService->deleteImage($package->image);
            }
            $validated['image'] = $imageService->uploadImage($request->file('image'), 'packages');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery')) {
            // Delete old gallery images if exists
            if ($package->gallery) {
                $oldGallery = json_decode($package->gallery, true);
                if (is_array($oldGallery)) {
                    $imageService->deleteMultipleImages($oldGallery);
                }
            }
            $galleryPaths = $imageService->uploadMultipleImages($request->file('gallery'), 'packages/gallery');
            $validated['gallery'] = json_encode($galleryPaths);
        }

        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package mis à jour avec succès');
    }

    public function destroy(string $id, ImageUploadService $imageService)
    {
        $package = TourPackage::findOrFail($id);
        
        // Delete main image if exists
        if ($package->image) {
            $imageService->deleteImage($package->image);
        }
        
        // Delete gallery images if exists
        if ($package->gallery) {
            $galleryImages = json_decode($package->gallery, true);
            if (is_array($galleryImages)) {
                $imageService->deleteMultipleImages($galleryImages);
            }
        }
        
        $package->delete();
        
        return redirect()->route('admin.packages.index')
            ->with('success', 'Package supprimé avec succès');
    }
    
    public function toggleStatus($id)
    {
        $package = TourPackage::findOrFail($id);
        $package->is_active = !$package->is_active;
        $package->save();

        return redirect()->back()
            ->with('success', 'Statut mis à jour avec succès');
    }
}
