<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait pour gérer les URLs d'images dans les modèles
 * Transforme automatiquement les chemins relatifs en URLs complètes
 */
trait HasImageUrl
{
    /**
     * Obtenir l'URL complète d'une image
     *
     * @param string|null $path
     * @return string|null
     */
    public function getImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        
        // Si c'est déjà une URL complète
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        
        // Construire l'URL via l'API
        $baseUrl = config('app.url');
        return $baseUrl . '/api/v1/images/' . urlencode($path);
    }
    
    /**
     * Accessor pour l'attribut image_url
     * Permet d'utiliser $model->image_url dans les réponses JSON
     */
    public function getImageUrlAttribute(): ?string
    {
        // Chercher les colonnes possibles pour l'image
        $imageColumns = ['image', 'featured_image', 'avatar', 'photo', 'picture'];
        
        foreach ($imageColumns as $column) {
            if (isset($this->attributes[$column]) && $this->attributes[$column]) {
                return $this->getImageUrl($this->attributes[$column]);
            }
        }
        
        return null;
    }
    
    /**
     * Accessor pour l'attribut gallery_urls
     * Transforme un tableau de chemins en URLs complètes
     */
    public function getGalleryUrlsAttribute(): ?array
    {
        if (!isset($this->attributes['gallery']) || !$this->attributes['gallery']) {
            return null;
        }
        
        $gallery = is_string($this->attributes['gallery']) 
            ? json_decode($this->attributes['gallery'], true) 
            : $this->attributes['gallery'];
        
        if (!is_array($gallery)) {
            return null;
        }
        
        return array_map(function($path) {
            return $this->getImageUrl($path);
        }, $gallery);
    }
}
