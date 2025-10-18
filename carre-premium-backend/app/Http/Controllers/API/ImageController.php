<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

/**
 * Contrôleur pour servir les images via API
 * Permet l'accès CORS aux images pour le frontend React
 */
class ImageController extends Controller
{
    /**
     * Servir une image depuis le storage
     * 
     * GET /api/v1/images/{path}
     */
    public function serve($path)
    {
        try {
            // Décoder le chemin
            $decodedPath = urldecode($path);
            
            // Vérifier si l'image existe dans storage/app/public
            if (Storage::disk('public')->exists($decodedPath)) {
                $file = Storage::disk('public')->get($decodedPath);
                $fullPath = Storage::disk('public')->path($decodedPath);
                $mimeType = mime_content_type($fullPath);
                
                return Response::make($file, 200, [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=31536000',
                    'Access-Control-Allow-Origin' => '*'
                ]);
            }
            
            // Vérifier si l'image existe dans public/uploads
            $publicPath = public_path('uploads/' . $decodedPath);
            if (file_exists($publicPath)) {
                $file = file_get_contents($publicPath);
                $mimeType = mime_content_type($publicPath);
                
                return Response::make($file, 200, [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=31536000',
                    'Access-Control-Allow-Origin' => '*'
                ]);
            }
            
            // Image par défaut si non trouvée
            return response()->json([
                'success' => false,
                'message' => 'Image non trouvée'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement de l\'image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir l'URL complète d'une image
     * 
     * POST /api/v1/images/url
     */
    public function getImageUrl(Request $request)
    {
        try {
            $path = $request->input('path');
            
            if (!$path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chemin d\'image requis'
                ], 400);
            }
            
            // Si c'est déjà une URL complète
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return response()->json([
                    'success' => true,
                    'url' => $path
                ]);
            }
            
            // Construire l'URL complète
            $baseUrl = config('app.url');
            $url = $baseUrl . '/api/v1/images/' . urlencode($path);
            
            return response()->json([
                'success' => true,
                'url' => $url
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération de l\'URL',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
