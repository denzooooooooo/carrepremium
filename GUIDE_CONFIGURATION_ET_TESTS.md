# 🚀 GUIDE CONFIGURATION ET TESTS COMPLETS

## ⚙️ ÉTAPE 1: CONFIGURATION SMTP

Ouvrez `carre-premium-backend/.env` et ajoutez/modifiez:

```env
# Configuration Email (Gmail exemple)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@carrepremium.com
MAIL_FROM_NAME="Carré Premium"

# URL Frontend (pour liens dans emails)
FRONTEND_URL=http://localhost:3000

# Mode Log (pour tester sans envoyer vraiment)
# MAIL_MAILER=log  # Décommentez pour mode test
```

**Note Gmail:** Créez un "Mot de passe d'application" dans votre compte Google:
1. Compte Google → Sécurité
2. Validation en 2 étapes (activez si nécessaire)
3. Mots de passe d'application → Générer

## 🧪 ÉTAPE 2: TESTS COMPLETS

### Test 1: Vérifier Installation
```bash
cd carre-premium-backend
composer show | grep -E "excel|dompdf|qrcode"
php artisan route:list | grep bookings-export
php artisan list | grep clean
```

### Test 2: Lancer le Backend
```bash
cd carre-premium-backend
php artisan serve
# Backend disponible sur http://localhost:8000
```

### Test 3: Tester Envoi Email
```bash
# Dans un nouveau terminal
cd carre-premium-backend
php artisan tinker

# Puis dans tinker:
$booking = \App\Models\Booking::with(['flightBooking', 'user', 'payment'])->first();

if ($booking && $booking->flightBooking) {
    Mail::to('votre-email-test@gmail.com')->send(
        new \App\Mail\FlightBookingConfirmation($booking->flightBooking)
    );
    echo "✅ Email confirmation vol envoyé!\n";
}

if ($booking && $booking->payment) {
    Mail::to('votre-email-test@gmail.com')->send(
        new \App\Mail\PaymentReceipt($booking->payment)
    );
    echo "✅ Email reçu paiement envoyé!\n";
}

exit
```

### Test 4: Tester Export Excel
```bash
# Ouvrir dans navigateur:
http://localhost:8000/admin/login

# Se connecter avec:
Email: admin@carrepremium.com
Password: Admin@2024

# Puis aller sur:
http://localhost:8000/admin/bookings

# Cliquer sur "Export Excel" ou accéder directement:
http://localhost:8000/admin/bookings-export
http://localhost:8000/admin/bookings-export-csv
```

### Test 5: Tester Génération PDF
```bash
cd carre-premium-backend
php artisan tinker

# Test reçu paiement
$payment = \App\Models\Payment::first();
$pdf = (new \App\Services\PdfGeneratorService())->generatePaymentReceipt($payment);
file_put_contents('test-recu.pdf', $pdf->output());
echo "✅ PDF reçu généré: test-recu.pdf\n";

# Test facture
$booking = \App\Models\Booking::with(['user', 'payment'])->first();
$pdf = (new \App\Services\PdfGeneratorService())->generateInvoice($booking);
file_put_contents('test-facture.pdf', $pdf->output());
echo "✅ PDF facture généré: test-facture.pdf\n";

# Test billet électronique
$flightBooking = \App\Models\FlightBooking::with(['booking.user'])->first();
if ($flightBooking) {
    $pdf = (new \App\Services\PdfGeneratorService())->generateETicket($flightBooking);
    file_put_contents('test-billet.pdf', $pdf->output());
    echo "✅ PDF billet généré: test-billet.pdf\n";
}

exit
```

### Test 6: Nettoyer Données Test
```bash
cd carre-premium-backend

# Avec confirmation
php artisan data:clean-test

# Sans confirmation (automatique)
php artisan data:clean-test --force
```

## 📊 ÉTAPE 3: VÉRIFICATION COMPLÈTE

### Checklist Fonctionnalités
- [ ] Backend démarre sans erreur
- [ ] Page admin/login accessible
- [ ] Page admin/dashboard accessible
- [ ] Export Excel télécharge fichier
- [ ] Export CSV télécharge fichier
- [ ] Email confirmation vol envoyé
- [ ] Email reçu paiement envoyé
- [ ] PDF reçu généré
- [ ] PDF facture généré
- [ ] PDF billet généré
- [ ] Commande nettoyage fonctionne

### Vérifier Emails Reçus
1. Ouvrez votre boîte email
2. Cherchez emails de "Carré Premium"
3. Vérifiez:
   - Design professionnel
   - Toutes informations présentes
   - Liens cliquables
   - Responsive mobile

### Vérifier Fichiers Excel
1. Ouvrez le fichier téléchargé
2. Vérifiez:
   - 14 colonnes présentes
   - En-têtes colorés
   - Données correctes
   - Calculs (commission, net, TVA)

### Vérifier PDF
1. Ouvrez les PDF générés
2. Vérifiez:
   - Design professionnel
   - QR codes visibles
   - Toutes informations
   - Pas d'erreurs formatage

## 🐛 DÉPANNAGE

### Erreur Email "Connection refused"
```bash
# Vérifiez configuration SMTP dans .env
# Ou utilisez mode log pour tester:
MAIL_MAILER=log

# Les emails seront dans storage/logs/laravel.log
```

### Erreur Export "Class not found"
```bash
cd carre-premium-backend
composer dump-autoload
php artisan optimize:clear
```

### Erreur PDF "QrCode not found"
```bash
cd carre-premium-backend
composer require simplesoftwareio/simple-qrcode
php artisan optimize:clear
```

### Erreur Route "login not defined"
```bash
cd carre-premium-backend
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

## 📝 LOGS À VÉRIFIER

```bash
# Logs Laravel
tail -f carre-premium-backend/storage/logs/laravel.log

# Logs serveur
tail -f backend.log
```

## ✅ VALIDATION FINALE

Une fois tous les tests passés:
1. ✅ Emails fonctionnent
2. ✅ Exports fonctionnent
3. ✅ PDF fonctionnent
4. ✅ Nettoyage fonctionne
5. ✅ Aucune erreur dans logs

**→ Système prêt pour production!**

## 🎯 COMMANDES RAPIDES

```bash
# Tout en un
cd carre-premium-backend && \
php artisan optimize:clear && \
php artisan serve

# Dans autre terminal - Tests
cd carre-premium-backend && \
chmod +x ../test_implementation_complete.sh && \
../test_implementation_complete.sh
