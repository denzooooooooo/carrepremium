<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FinancialReportingService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

/**
 * Contrôleur de Reporting Financier pour l'Admin
 * 
 * Permet au comptable de visualiser et analyser:
 * - Chiffre d'affaires par période, client, service
 * - Statistiques de réservations
 * - Exports PDF et Excel
 */
class ReportingController extends Controller
{
    protected $reportingService;

    public function __construct(FinancialReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    /**
     * Dashboard de reporting principal
     * 
     * GET /admin/reporting
     */
    public function index(Request $request)
    {
        // Période par défaut: 30 derniers jours
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Récupérer les KPIs
        $kpis = $this->reportingService->getKPIs($startDate, $endDate);
        
        // Récupérer les données pour les graphiques
        $revenueByService = $this->reportingService->getRevenueByService($startDate, $endDate);
        $revenueByPeriod = $this->reportingService->getRevenueByPeriod($startDate, $endDate, 'day');
        $topClients = $this->reportingService->getTopClients($startDate, $endDate, 10);
        $trendAnalysis = $this->reportingService->getTrendAnalysis($startDate, $endDate);

        return view('admin.reporting.index', compact(
            'kpis',
            'revenueByService',
            'revenueByPeriod',
            'topClients',
            'trendAnalysis',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Rapport détaillé du chiffre d'affaires
     * 
     * GET /admin/reporting/revenue
     */
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $groupBy = $request->input('group_by', 'day');

        $data = [
            'summary' => $this->reportingService->getTotalRevenue($startDate, $endDate),
            'by_period' => $this->reportingService->getRevenueByPeriod($startDate, $endDate, $groupBy),
            'by_service' => $this->reportingService->getRevenueByService($startDate, $endDate),
            'by_currency' => $this->reportingService->getRevenueByCurrency($startDate, $endDate),
            'by_payment_method' => $this->reportingService->getRevenueByPaymentMethod($startDate, $endDate),
            'trend' => $this->reportingService->getTrendAnalysis($startDate, $endDate)
        ];

        return view('admin.reporting.revenue', compact('data', 'startDate', 'endDate', 'groupBy'));
    }

    /**
     * Rapport par client
     * 
     * GET /admin/reporting/clients
     */
    public function clients(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $limit = $request->input('limit', 50);

        $topClients = $this->reportingService->getTopClients($startDate, $endDate, $limit);

        return view('admin.reporting.clients', compact('topClients', 'startDate', 'endDate'));
    }

    /**
     * Rapport par service
     * 
     * GET /admin/reporting/services
     */
    public function services(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $data = $this->reportingService->getRevenueByService($startDate, $endDate);

        return view('admin.reporting.services', compact('data', 'startDate', 'endDate'));
    }

    /**
     * Détails des transactions
     * 
     * GET /admin/reporting/transactions
     */
    public function transactions(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        $filters = [
            'user_id' => $request->input('user_id'),
            'status' => $request->input('status'),
            'payment_method' => $request->input('payment_method'),
            'booking_type' => $request->input('booking_type')
        ];

        $transactions = $this->reportingService->getTransactionDetails(
            $startDate, 
            $endDate, 
            $filters,
            $request->input('per_page', 50)
        );

        return view('admin.reporting.transactions', compact('transactions', 'startDate', 'endDate', 'filters'));
    }

    /**
     * Exporter un rapport
     * 
     * GET /admin/reporting/export/{type}
     * 
     * @param string $type (pdf|excel|csv)
     */
    public function export(Request $request, $type)
    {
        try {
            $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
            $filters = $request->input('filters', []);

            // Générer le rapport complet
            $reportData = $this->reportingService->getCompleteReport($startDate, $endDate, $filters);

            switch ($type) {
                case 'pdf':
                    $pdf = $this->reportingService->generatePDFReport($reportData, 'Rapport Financier Carré Premium');
                    return $pdf->download('rapport_financier_' . date('Y-m-d') . '.pdf');

                case 'excel':
                case 'csv':
                    $csv = $this->reportingService->exportToCSV(
                        $this->flattenReportData($reportData),
                        'rapport_financier'
                    );
                    
                    return response($csv)
                        ->header('Content-Type', 'text/csv')
                        ->header('Content-Disposition', 'attachment; filename="rapport_financier_' . date('Y-m-d') . '.csv"');

                default:
                    return back()->with('error', 'Format d\'export non supporté');
            }

        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de l\'export: ' . $e->getMessage());
        }
    }

    /**
     * Rapport personnalisé
     * 
     * POST /admin/reporting/custom
     */
    public function customReport(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'group_by' => 'nullable|in:day,week,month,year',
            'filters' => 'nullable|array'
        ]);

        $reportData = $this->reportingService->getCompleteReport(
            $validated['start_date'],
            $validated['end_date'],
            $validated['filters'] ?? []
        );

        return view('admin.reporting.custom', compact('reportData', 'validated'));
    }

    /**
     * API: Obtenir les données de reporting (pour graphiques AJAX)
     * 
     * GET /admin/reporting/api/data
     */
    public function getReportingData(Request $request)
    {
        try {
            $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
            $type = $request->input('type', 'summary');

            $data = match($type) {
                'summary' => $this->reportingService->getTotalRevenue($startDate, $endDate),
                'by_service' => $this->reportingService->getRevenueByService($startDate, $endDate),
                'by_period' => $this->reportingService->getRevenueByPeriod($startDate, $endDate, $request->input('group_by', 'day')),
                'top_clients' => $this->reportingService->getTopClients($startDate, $endDate, $request->input('limit', 10)),
                'kpis' => $this->reportingService->getKPIs($startDate, $endDate),
                'trend' => $this->reportingService->getTrendAnalysis($startDate, $endDate),
                'complete' => $this->reportingService->getCompleteReport($startDate, $endDate),
                default => ['error' => 'Type de rapport non supporté']
            };

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aplatir les données du rapport pour l'export CSV
     * 
     * @param array $reportData
     * @return array
     */
    protected function flattenReportData($reportData)
    {
        $flattened = [];

        // Résumé
        if (isset($reportData['summary'])) {
            $flattened[] = [
                'Type' => 'Résumé',
                'Période Début' => $reportData['summary']['period']['start'],
                'Période Fin' => $reportData['summary']['period']['end'],
                'CA Total' => $reportData['summary']['total_revenue'],
                'Transactions' => $reportData['summary']['total_transactions'],
                'Panier Moyen' => $reportData['summary']['average_transaction']
            ];
        }

        return $flattened;
    }

    /**
     * Rapport des remboursements
     * 
     * GET /admin/reporting/refunds
     */
    public function refunds(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $refundData = $this->reportingService->getRefundReport($startDate, $endDate);

        return view('admin.reporting.refunds', compact('refundData', 'startDate', 'endDate'));
    }
}
