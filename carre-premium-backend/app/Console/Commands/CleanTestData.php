<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\FlightBooking;
use App\Models\EventTicket;
use App\Models\PackageBooking;
use App\Models\Payment;
use App\Models\User;

class CleanTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:clean-test {--force : Force deletion without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoie toutes les données de test de la base de données';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  ATTENTION: Cette action va supprimer TOUTES les données de test. Continuer?')) {
                $this->info('Opération annulée.');
                return 0;
            }
        }

        $this->info('🧹 Nettoyage des données de test en cours...');
        
        DB::beginTransaction();
        
        try {
            // 1. Supprimer les réservations de test
            $this->info('📋 Suppression des réservations de test...');
            $testBookings = Booking::where('is_test', true)
                ->orWhere('booking_reference', 'like', 'TEST%')
                ->orWhere('created_at', '<', now()->subMonths(6))
                ->get();
            
            $bookingsCount = $testBookings->count();
            
            foreach ($testBookings as $booking) {
                // Supprimer les relations
                if ($booking->flightBooking) {
                    $booking->flightBooking->delete();
                }
                if ($booking->eventTickets) {
                    $booking->eventTickets()->delete();
                }
                if ($booking->packageBookings) {
                    $booking->packageBookings()->delete();
                }
                if ($booking->payment) {
                    $booking->payment->delete();
                }
                
                $booking->delete();
            }
            
            $this->line("   ✓ {$bookingsCount} réservations supprimées");

            // 2. Supprimer les paiements orphelins
            $this->info('💳 Suppression des paiements orphelins...');
            $orphanPayments = Payment::whereDoesntHave('booking')->delete();
            $this->line("   ✓ {$orphanPayments} paiements orphelins supprimés");

            // 3. Supprimer les utilisateurs de test (sauf admin)
            $this->info('👥 Suppression des utilisateurs de test...');
            $testUsers = User::where('email', 'like', '%test%')
                ->orWhere('email', 'like', '%example%')
                ->orWhere('email', 'like', '%demo%')
                ->where('email', '!=', 'admin@carrepremium.com')
                ->get();
            
            $usersCount = $testUsers->count();
            
            foreach ($testUsers as $user) {
                // Vérifier qu'il n'a pas de réservations réelles
                if ($user->bookings()->count() == 0) {
                    $user->delete();
                }
            }
            
            $this->line("   ✓ {$usersCount} utilisateurs de test supprimés");

            // 4. Nettoyer les fichiers uploads de test
            $this->info('📁 Nettoyage des fichiers uploads...');
            $uploadsPath = storage_path('app/public/uploads');
            if (file_exists($uploadsPath)) {
                $files = glob($uploadsPath . '/*test*');
                $filesCount = count($files);
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                $this->line("   ✓ {$filesCount} fichiers de test supprimés");
            }

            // 5. Optimiser la base de données
            $this->info('⚡ Optimisation de la base de données...');
            DB::statement('OPTIMIZE TABLE bookings');
            DB::statement('OPTIMIZE TABLE payments');
            DB::statement('OPTIMIZE TABLE users');
            DB::statement('OPTIMIZE TABLE flight_bookings');
            $this->line("   ✓ Tables optimisées");

            DB::commit();

            $this->newLine();
            $this->info('✅ Nettoyage terminé avec succès!');
            $this->newLine();
            
            // Statistiques finales
            $this->table(
                ['Type', 'Restant'],
                [
                    ['Utilisateurs', User::count()],
                    ['Réservations', Booking::count()],
                    ['Paiements', Payment::count()],
                    ['Vols', FlightBooking::count()],
                ]
            );

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Erreur lors du nettoyage: ' . $e->getMessage());
            return 1;
        }
    }
}
