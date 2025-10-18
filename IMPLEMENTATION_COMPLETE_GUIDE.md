# 🚀 GUIDE COMPLET D'IMPLÉMENTATION - CARRÉ PREMIUM

## 📊 État Actuel du Projet

### ✅ Ce qui est déjà fait:
1. **Base de données** - 28 tables créées et migrées
2. **Modèles Laravel** - Tous les modèles principaux créés
3. **Interface Admin** - Dashboard et pages de gestion créés
4. **Authentification** - Système de connexion admin fonctionnel
5. **Design** - Interface moderne avec thème violet/doré

### 🔧 Ce qui reste à faire:

---

## ÉTAPE 1: FINALISER LES MIGRATIONS AVANCÉES

### 1.1 Copier les migrations depuis ADVANCED_MIGRATIONS.php

Ouvrez le fichier `carre-premium-backend/ADVANCED_MIGRATIONS.php` et copiez le contenu de chaque migration dans les fichiers correspondants:

```bash
cd carre-premium-backend/database/migrations/
```

**Fichiers à modifier:**
- `2025_10_07_190356_create_api_configurations_table.php`
- `2025_10_07_190356_create_flight_bookings_table.php`
- `2025_10_07_190357_create_pricing_rules_table.php`
- `2025_10_07_190357_create_event_tickets_table.php`
- `2025_10_07_190357_create_event_inventory_table.php`
- `2025_10_07_190357_create_package_bookings_table.php`
- `2025_10_07_190357_create_package_inventory_table.php`
- `2025_10_07_190358_create_payment_gateways_table.php`

### 1.2 Exécuter les migrations

```bash
php artisan migrate
```

---

## ÉTAPE 2: CRÉER LES MODÈLES MANQUANTS

### 2.1 Créer les modèles

```bash
php artisan make:model ApiConfiguration
php artisan make:model FlightBooking
php artisan make:model PricingRule
php artisan make:model EventTicket
php artisan make:model EventInventory
php artisan make:model PackageBooking
php artisan make:model PackageInventory
php artisan make:model PaymentGateway
```

### 2.2 Contenu des modèles

**ApiConfiguration.php:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiConfiguration extends Model
{
    protected $fillable = [
        'provider',
        'api_key',
        'api_secret',
        'endpoint_url',
        'is_production',
        'is_active',
        'additional_config'
    ];

    protected $casts = [
        'is_production' => 'boolean',
        'is_active' => 'boolean',
        'additional_config' => 'array'
    ];
}
```

**FlightBooking.php:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightBooking extends Model
{
    protected $fillable = [
        'booking_id',
        'pnr',
        'eticket_number',
        'amadeus_booking_ref',
        'flight_segments',
        'base_price',
        'taxes',
        'margin_amount',
        'margin_percentage',
        'final_price',
        'ticket_status',
        'ticket_pdf_path',
        'cancellation_reason',
        'issued_at',
        'cancelled_at'
    ];

    protected $casts = [
        'flight_segments' => 'array',
        'base_price' => 'decimal:2',
        'taxes' => 'decimal:2',
        'margin_amount' => 'decimal:2',
        'margin_percentage' => 'decimal:2',
        'final_price' => 'decimal:2',
        'issued_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
```

**PricingRule.php:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingRule extends Model
{
    protected $fillable = [
        'product_type',
        'rule_name',
        'category',
        'margin_type',
        'margin_value',
        'min_price',
        'max_price',
        'is_active',
        'priority',
        'description'
    ];

    protected $casts = [
        'margin_value' => 'decimal:2',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'is_active' => 'boolean',
        'priority' => 'integer'
    ];
}
```

**EventTicket.php:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    protected $fillable = [
        'booking_id',
        'event_id',
        'ticket_number',
        'qr_code',
        'qr_data',
        'seat_zone_id',
        'seat_number',
        'base_price',
        'margin_amount',
        'final_price',
        'ticket_status',
        'used_at',
        'used_by',
        'ticket_pdf_path'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'margin_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'used_at' => 'datetime'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function seatZone()
    {
        return $this->belongsTo(EventSeatZone::class, 'seat_zone_id');
    }
}
```

**PackageBooking.php:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageBooking extends Model
{
    protected $fillable = [
        'booking_id',
        'package_id',
        'confirmation_number',
        'travel_date',
        'participants_count',
        'participants_details',
        'base_price',
        'margin_amount',
        'final_price',
        'voucher_pdf_path',
        'itinerary_pdf_path',
        'status',
        'special_requests',
        'admin_notes',
        'confirmed_at'
    ];

    protected $casts = [
        'travel_date' => 'date',
        'participants_details' => 'array',
        'base_price' => 'decimal:2',
        'margin_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'confirmed_at' => 'datetime'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function package()
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }
}
```

---

## ÉTAPE 3: INSTALLER LES PACKAGES NÉCESSAIRES

### 3.1 Packages pour PDF et QR Code

```bash
composer require barryvdh/laravel-dompdf
composer require simplesoftwareio/simple-qrcode
composer require guzzlehttp/guzzle
```

### 3.2 Publier les configurations

```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

## ÉTAPE 4: CONFIGURATION AMADEUS

### 4.1 Ajouter dans .env

```env
# Amadeus API Configuration
AMADEUS_CLIENT_ID=your_client_id_here
AMADEUS_CLIENT_SECRET=your_client_secret_here
AMADEUS_ENVIRONMENT=test  # ou 'production'
```

### 4.2 Créer un Seeder pour la configuration Amadeus

```bash
php artisan make:seeder AmadeusConfigSeeder
```

**Contenu du seeder:**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiConfiguration;

class AmadeusConfigSeeder extends Seeder
{
    public function run()
    {
        ApiConfiguration::create([
            'provider' => 'amadeus',
            'api_key' => env('AMADEUS_CLIENT_ID'),
            'api_secret' => env('AMADEUS_CLIENT_SECRET'),
            'endpoint_url' => env('AMADEUS_ENVIRONMENT') === 'production' 
                ? 'https://api.amadeus.com/v2'
                : 'https://test.api.amadeus.com/v2',
            'is_production' => env('AMADEUS_ENVIRONMENT') === 'production',
            'is_active' => true
        ]);
    }
}
```

Exécuter:
```bash
php artisan db:seed --class=AmadeusConfigSeeder
```

---

## ÉTAPE 5: CRÉER LES RÈGLES DE PRICING PAR DÉFAUT

### 5.1 Créer le seeder

```bash
php artisan make:seeder PricingRulesSeeder
```

**Contenu:**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricingRule;

class PricingRulesSeeder extends Seeder
{
    public function run()
    {
        $rules = [
            // VOLS
            [
                'product_type' => 'flight',
                'rule_name' => 'Vols Domestiques',
                'category' => 'domestic',
                'margin_type' => 'percentage',
                'margin_value' => 5.00,
                'is_active' => true,
                'priority' => 10
            ],
            [
                'product_type' => 'flight',
                'rule_name' => 'Vols Internationaux',
                'category' => 'international',
                'margin_type' => 'percentage',
                'margin_value' => 8.00,
                'is_active' => true,
                'priority' => 10
            ],
            
            // ÉVÉNEMENTS
            [
                'product_type' => 'event',
                'rule_name' => 'Événements Sportifs',
                'category' => 'sport',
                'margin_type' => 'percentage',
                'margin_value' => 15.00,
                'is_active' => true,
                'priority' => 10
            ],
            [
                'product_type' => 'event',
                'rule_name' => 'Concerts',
                'category' => 'concert',
                'margin_type' => 'percentage',
                'margin_value' => 20.00,
                'is_active' => true,
                'priority' => 10
            ],
            
            // PACKAGES
            [
                'product_type' => 'package',
                'rule_name' => 'Hélicoptère',
                'category' => 'helicopter',
                'margin_type' => 'percentage',
                'margin_value' => 25.00,
                'is_active' => true,
                'priority' => 10
            ],
            [
                'product_type' => 'package',
                'rule_name' => 'Jet Privé',
                'category' => 'private_jet',
                'margin_type' => 'percentage',
                'margin_value' => 20.00,
                'is_active' => true,
                'priority' => 10
            ],
            [
                'product_type' => 'package',
                'rule_name' => 'Packages Standard',
                'category' => 'standard',
                'margin_type' => 'percentage',
                'margin_value' => 15.00,
                'is_active' => true,
                'priority' => 5
            ]
        ];

        foreach ($rules as $rule) {
            PricingRule::create($rule);
        }
    }
}
```

Exécuter:
```bash
php artisan db:seed --class=PricingRulesSeeder
```

---

## ÉTAPE 6: CRÉER LES PAGES ADMIN POUR GÉRER LES NOUVELLES ENTITÉS

### 6.1 Page de gestion des règles de pricing

**Route (routes/admin.php):**
```php
Route::get('/pricing-rules', [PricingRuleController::class, 'index'])->name('pricing-rules.index');
Route::post('/pricing-rules', [PricingRuleController::class, 'store'])->name('pricing-rules.store');
Route::put('/pricing-rules/{id}', [PricingRuleController::class, 'update'])->name('pricing-rules.update');
Route::delete('/pricing-rules/{id}', [PricingRuleController::class, 'destroy'])->name('pricing-rules.destroy');
```

### 6.2 Page de configuration API

**Route:**
```php
Route::get('/api-config', [ApiConfigController::class, 'index'])->name('api-config.index');
Route::put('/api-config/{id}', [ApiConfigController::class, 'update'])->name('api-config.update');
```

### 6.3 Page de gestion des gateways de paiement

**Route:**
```php
Route::get('/payment-gateways', [PaymentGatewayController::class, 'index'])->name('payment-gateways.index');
Route::post('/payment-gateways', [PaymentGatewayController::class, 'store'])->name('payment-gateways.store');
```

---

## ÉTAPE 7: CRÉER LES TEMPLATES PDF

Créer les vues Blade pour les PDF dans `resources/views/pdf/`:

### 7.1 flight_eticket.blade.php
### 7.2 event_ticket.blade.php
### 7.3 package_voucher.blade.php
### 7.4 payment_receipt.blade.php
### 7.5 invoice.blade.php

---

## ÉTAPE 8: TESTER LE SYSTÈME

### 8.1 Test Amadeus (Mode Test)

1. Créer un compte test sur https://developers.amadeus.com
2. Obtenir les credentials (Client ID + Secret)
3. Les ajouter dans `.env`
4. Tester la recherche de vols

### 8.2 Test de Pricing

1. Aller dans l'admin
2. Vérifier les règles de pricing
3. Ajuster les marges selon les besoins

### 8.3 Test de Génération de Documents

1. Créer une réservation test
2. Générer les documents
3. Vérifier les PDF et QR codes

---

## ÉTAPE 9: INTÉGRATION DES PAIEMENTS

### 9.1 Stripe

```bash
composer require stripe/stripe-php
```

### 9.2 Mobile Money (Côte d'Ivoire)

Intégrer les APIs:
- Orange Money API
- MTN Mobile Money API
- Moov Money API

---

## ÉTAPE 10: DÉPLOIEMENT

### 10.1 Checklist avant déploiement

- [ ] Toutes les migrations exécutées
- [ ] Tous les seeders exécutés
- [ ] Configuration Amadeus en production
- [ ] Gateways de paiement configurés
- [ ] Tests end-to-end effectués
- [ ] Backup de la base de données
- [ ] SSL configuré
- [ ] Emails configurés
- [ ] Logs configurés

### 10.2 Commandes de déploiement

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 📞 INFORMATIONS À COLLECTER DU CLIENT

### Pour Amadeus:
- [ ] Client ID (Production)
- [ ] Client Secret (Production)
- [ ] Marges souhaitées par type de vol

### Pour les Événements:
- [ ] Liste complète des événements à vendre
- [ ] Prix de base par catégorie
- [ ] Marges souhaitées

### Pour les Packages:
- [ ] Catalogue complet des packages
- [ ] Prix fournisseurs
- [ ] Marges souhaitées

### Pour les Paiements:
- [ ] Compte Stripe (ou autre)
- [ ] Numéros Mobile Money
- [ ] Coordonnées bancaires

---

## 🎯 PROCHAINES FONCTIONNALITÉS À AJOUTER

1. **Notifications Push** - Pour alerter les clients
2. **App Mobile** - Pour scanner les QR codes
3. **Programme de Fidélité** - Points et récompenses
4. **Chat en Direct** - Support client
5. **Recommandations IA** - Suggestions personnalisées
6. **Multi-langue** - Plus de langues
7. **Rapports Avancés** - Analytics détaillés

---

## 📚 DOCUMENTATION

Toute la documentation technique est disponible dans:
- `INTEGRATION_AMADEUS_GUIDE.md` - Guide Amadeus complet
- `ADVANCED_MIGRATIONS.php` - Migrations avancées
- `PROJECT_STRUCTURE.md` - Structure du projet
- `DESIGN_IMPROVEMENTS.md` - Améliorations design

---

**🚀 Le système est maintenant prêt à être 100% fonctionnel !**

Une fois toutes ces étapes complétées, vous aurez:
✅ Intégration Amadeus complète
✅ Gestion automatique des marges
✅ Génération automatique de billets/reçus
✅ Paiement en ligne sécurisé
✅ Interface admin complète
✅ Système de QR codes
✅ Gestion d'inventaire en temps réel
