<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PromoCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Contrôleur API pour les codes promotionnels
 */
class PromoCodeController extends Controller
{
    protected $promoCodeService;

    public function __construct(PromoCodeService $promoCodeService)
    {
        $this->promoCodeService = $promoCodeService;
    }

    /**
     * Valider un code promo
     * 
     * POST /api/v1/promo-codes/validate
     */
    public function validateCode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string',
                'order_amount' => 'required|numeric|min:0',
                'booking_type' => 'required|in:flight,event,package,all'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $userId = auth('sanctum')->id();
            
            $result = $this->promoCodeService->validatePromoCode(
                $request->code,
                $request->order_amount,
                $request->booking_type,
                $userId
            );

            if ($result['valid']) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'promo_code_id' => $result['promo_code_id'],
                        'discount_amount' => $result['discount_amount'],
                        'discount_type' => $result['discount_type'],
                        'discount_value' => $result['discount_value']
                    ],
                    'message' => $result['message']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la validation du code promo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les codes promo actifs
     * 
     * GET /api/v1/promo-codes/active
     */
    public function getActiveCodes(Request $request)
    {
        try {
            $bookingType = $request->query('type', 'all');
            
            $promoCodes = $this->promoCodeService->getActivePromoCodes($bookingType);

            return response()->json([
                'success' => true,
                'data' => $promoCodes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des codes promo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
