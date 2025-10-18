# 🚀 GUIDE DE DÉPLOIEMENT PRODUCTION - CARRÉ PREMIUM

**Date:** 10 Janvier 2025  
**Objectif:** Déployer le site en production avec toutes les configurations

---

## 📋 CHECKLIST PRÉ-DÉPLOIEMENT

### ✅ Étape 1: Obtenir les Clés API (OBLIGATOIRE)

#### 1.1 Stripe (Paiements par carte)
1. Créer un compte sur https://stripe.com
2. Aller dans **Developers → API Keys**
3. Récupérer:
   - **Publishable key** (pk_live_...)
   - **Secret key** (sk_live_...)
4. Configurer les webhooks:
   - URL: `https://votre-domaine.com/api/v1/payments/stripe/webhook`
   - Événements: `payment_intent.succeeded`, `payment_intent.payment_failed`
   - Récupérer le **Webhook secret** (whsec_...)

#### 1.2 Orange Money (Côte d'Ivoire)
1. Contacter Orange Money Business: https://orangemoney.ci
2. Demander un compte marchand
3. Récupérer:
   - **Merchant Key**
   - **Client ID**
   - **Client Secret**
4. URL de callback: `https://votre-domaine.com/api/v1/payments/orange/callback`

#### 1.3 MTN Mobile Money
1. S'inscrire sur https://momodeveloper.mtn.com
2. Créer une application
3. Récupérer:
   - **API Key**
   - **API Secret**
   - **Subscription Key**
4. Passer en mode production

#### 1.4 Email (SendGrid ou Mailgun)
**Option A: SendGrid** (Recommandé)
1. Créer un compte sur https://sendgrid.com
2. Créer une API Key
3. Vérifier votre domaine

**Option B: Mailgun**
1. Créer un compte sur https://mailgun.com
2. Ajouter votre domaine
3. Récupérer les credentials SMTP

#### 1.5 Base de Données MySQL
**Option A: Serveur dédié**
- Installer MySQL 8.0+
- Créer la base de données `carre_premium`
- Créer un utilisateur avec tous les privilèges

**Option B: Service cloud (Recommandé)**
- AWS RDS
- Google Cloud SQL
- DigitalOcean Managed Database

---

## 🔧 CONFIGURATION BACKEND (.env)

### Fichier: `carre-premium-backend/.env`

```env
# ============================================
# APPLICATION
# ============================================
APP_NAME="Carré Premium"
APP_ENV=production
APP_KEY=base64:GENERER_AVEC_php_artisan_key:generate
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://api.carrepremium.com
FRONTEND_URL=https://carrepremium.com

APP_LOCALE=fr
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=fr_FR

# ============================================
# BASE DE DONNÉES (À CONFIGURER)
# ============================================
DB_CONNECTION=mysql
DB_HOST=votre-serveur-mysql.com
DB_PORT=3306
DB_DATABASE=carre_premium
DB_USERNAME=carre_premium_user
DB_PASSWORD=VOTRE_MOT_DE_PASSE_SECURISE_ICI

# ============================================
# SESSION & CACHE
# ============================================
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.carrepremium.com
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=s3
QUEUE_CONNECTION=database

CACHE_STORE=redis
CACHE_PREFIX=carre_premium

# ============================================
# REDIS (Optionnel mais recommandé)
# ============================================
REDIS_CLIENT=phpredis
REDIS_HOST=votre-redis-host.com
REDIS_PASSWORD=votre-redis-password
REDIS_PORT=6379

# ============================================
# EMAIL (SendGrid recommandé)
# ============================================
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=VOTRE_SENDGRID_API_KEY
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@carrepremium.com"
MAIL_FROM_NAME="Carré Premium"

# ============================================
# STRIPE (PRODUCTION)
# ============================================
STRIPE_KEY=pk_live_VOTRE_CLE_PUBLIQUE_STRIPE
STRIPE_SECRET=sk_live_VOTRE_CLE_SECRETE_STRIPE
STRIPE_WEBHOOK_SECRET=whsec_VOTRE_WEBHOOK_SECRET

# ============================================
# ORANGE MONEY (PRODUCTION)
# ============================================
ORANGE_MONEY_MERCHANT_KEY=VOTRE_MERCHANT_KEY
ORANGE_MONEY_CLIENT_ID=VOTRE_CLIENT_ID
ORANGE_MONEY_CLIENT_SECRET=VOTRE_CLIENT_SECRET
ORANGE_MONEY_API_URL=https://api.orange.com/orange-money-webpay/v1
ORANGE_MONEY_AUTH_URL=https://api.orange.com/oauth/v3/token
ORANGE_MONEY_RETURN_URL=https://carrepremium.com/payment/callback

# ============================================
# MTN MOBILE MONEY (PRODUCTION)
# ============================================
MTN_MOMO_API_KEY=VOTRE_API_KEY
MTN_MOMO_API_SECRET=VOTRE_API_SECRET
MTN_MOMO_SUBSCRIPTION_KEY=VOTRE_SUBSCRIPTION_KEY
MTN_MOMO_ENVIRONMENT=production

# ============================================
# AWS S3 (Pour stockage fichiers)
# ============================================
AWS_ACCESS_KEY_ID=VOTRE_AWS_ACCESS_KEY
AWS_SECRET_ACCESS_KEY=VOTRE_AWS_SECRET_KEY
AWS_DEFAULT_REGION=eu-west-1
AWS_BUCKET=carre-premium-uploads
AWS_USE_PATH_STYLE_ENDPOINT=false

# ============================================
# AMADEUS API (Optionnel - Vols réels)
# ============================================
AMADEUS_API_KEY=VOTRE_AMADEUS_API_KEY
AMADEUS_API_SECRET=VOTRE_AMADEUS_API_SECRET
AMADEUS_ENVIRONMENT=production

# ============================================
# WHATSAPP BUSINESS API (Optionnel)
# ============================================
WHATSAPP_PHONE_NUMBER_ID=VOTRE_PHONE_NUMBER_ID
WHATSAPP_ACCESS_TOKEN=VOTRE_ACCESS_TOKEN
WHATSAPP_VERIFY_TOKEN=VOTRE_VERIFY_TOKEN

# ============================================
# GOOGLE SERVICES (Optionnel)
# ============================================
GOOGLE_MAPS_API_KEY=VOTRE_GOOGLE_MAPS_API_KEY
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX

# ============================================
# OPENAI (Optionnel - Chatbot IA)
# ============================================
OPENAI_API_KEY=sk-VOTRE_OPENAI_API_KEY

# ============================================
# SÉCURITÉ
# ============================================
BCRYPT_ROUNDS=12
LOG_CHANNEL=stack
LOG_STACK=daily
LOG_LEVEL=error
```

---

## 🌐 CONFIGURATION FRONTEND (.env)

### Fichier: `carre-premium-frontend/.env.production`

```env
# ============================================
# API CONFIGURATION
# ============================================
REACT_APP_API_URL=https://api.carrepremium.com/api/v1

# ============================================
# STRIPE PUBLIC KEY (PRODUCTION)
# ============================================
REACT_APP_STRIPE_PUBLIC_KEY=pk_live_VOTRE_CLE_PUBLIQUE_STRIPE

# ============================================
# APPLICATION
# ============================================
REACT_APP_NAME=Carré Premium
REACT_APP_DESCRIPTION=Votre Conciergerie de Voyage Premium Internationale
REACT_APP_URL=https://carrepremium.com

# ============================================
# GOOGLE SERVICES
# ============================================
REACT_APP_GOOGLE_MAPS_API_KEY=VOTRE_GOOGLE_MAPS_API_KEY
REACT_APP_GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX

# ============================================
# CONTACT INFORMATION
# ============================================
REACT_APP_WHATSAPP_NUMBER=+225XXXXXXXXX
REACT_APP_CONTACT_EMAIL=contact@carrepremium.com
REACT_APP_CONTACT_PHONE=+225 XX XX XX XX XX
REACT_APP_CONTACT_ADDRESS=Abidjan, Côte d'Ivoire

# ============================================
# SOCIAL MEDIA
# ============================================
REACT_APP_FACEBOOK_URL=https://facebook.com/carrepremium
REACT_APP_INSTAGRAM_URL=https://instagram.com/carrepremium
REACT_APP_TWITTER_URL=https://twitter.com/carrepremium
REACT_APP_LINKEDIN_URL=https://linkedin.com/company/carrepremium

# ============================================
# FEATURE FLAGS
# ============================================
REACT_APP_ENABLE_CHATBOT=true
REACT_APP_ENABLE_WHATSAPP=true
REACT_APP_ENABLE_RECOMMENDATIONS=true
REACT_APP_ENABLE_ANALYTICS=true

# ============================================
# SEO
# ============================================
REACT_APP_META_TITLE=Carré Premium - Conciergerie de Voyage Premium
REACT_APP_META_DESCRIPTION=Réservez vos vols, événements sportifs et packages touristiques de luxe avec Carré Premium
REACT_APP_META_KEYWORDS=voyage,luxe,billets,événements,vip,conciergerie
```

---

## 🚀 ÉTAPES DE DÉPLOIEMENT

### 1. Préparer le Serveur

```bash
# Installer les dépendances
sudo apt update
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl redis-server

# Installer Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Installer Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

### 2. Déployer le Backend

```bash
# Cloner le projet
cd /var/www
git clone votre-repo.git carre-premium

# Backend
cd carre-premium/carre-premium-backend

# Installer les dépendances
composer install --no-dev --optimize-autoloader

# Copier et configurer .env
cp .env.example .env
nano .env  # Configurer avec les valeurs ci-dessus

# Générer la clé
php artisan key:generate

# Migrations
php artisan migrate --force

# Seeders (données initiales)
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=CurrencySeeder
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=SettingSeeder

# Optimisations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 3. Déployer le Frontend

```bash
cd /var/www/carre-premium/carre-premium-frontend

# Installer les dépendances
npm install

# Créer le fichier .env.production
nano .env.production  # Copier la configuration ci-dessus

# Build production
npm run build

# Les fichiers sont dans le dossier build/
```

### 4. Configurer Nginx

```nginx
# /etc/nginx/sites-available/carrepremium.com

# Backend API
server {
    listen 80;
    server_name api.carrepremium.com;
    root /var/www/carre-premium/carre-premium-backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}

# Frontend
server {
    listen 80;
    server_name carrepremium.com www.carrepremium.com;
    root /var/www/carre-premium/carre-premium-frontend/build;

    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
# Activer les sites
sudo ln -s /etc/nginx/sites-available/carrepremium.com /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 5. Installer SSL (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d carrepremium.com -d www.carrepremium.com -d api.carrepremium.com
```

### 6. Configurer les Tâches Cron

```bash
sudo crontab -e

# Ajouter:
* * * * * cd /var/www/carre-premium/carre-premium-backend && php artisan schedule:run >> /dev/null 2>&1
```

---

## ✅ VÉRIFICATIONS POST-DÉPLOIEMENT

### Tests Backend
```bash
# Test API
curl https://api.carrepremium.com/api/v1/settings
curl https://api.carrepremium.com/api/v1/currencies
curl https://api.carrepremium.com/api/v1/payments/methods
```

### Tests Frontend
1. Ouvrir https://carrepremium.com
2. Vérifier que toutes les pages se chargent
3. Tester la recherche de vols
4. Tester l'ajout au panier

### Tests Paiements
1. Faire une réservation test
2. Tester paiement Stripe (mode test)
3. Vérifier réception emails

---

## 🔒 SÉCURITÉ PRODUCTION

### 1. Firewall
```bash
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

### 2. Fail2Ban
```bash
sudo apt install fail2ban
sudo systemctl enable fail2ban
```

### 3. Backups Automatiques
```bash
# Script backup MySQL
#!/bin/bash
mysqldump -u root -p carre_premium > /backups/carre_premium_$(date +%Y%m%d).sql
```

---

## 📊 MONITORING

### 1. Logs
```bash
# Backend
tail -f /var/www/carre-premium/carre-premium-backend/storage/logs/laravel.log

# Nginx
tail -f /var/log/nginx/error.log
```

### 2. Performance
- Installer New Relic ou Datadog
- Configurer Google Analytics
- Monitorer les temps de réponse API

---

## 🎉 SITE EN PRODUCTION !

Votre site Carré Premium est maintenant en ligne et prêt à accepter des réservations réelles !

**URLs:**
- Site: https://carrepremium.com
- API: https://api.carrepremium.com
- Admin: https://carrepremium.com/admin

**Identifiants Admin:**
- Email: admin@carrepremium.com
- Mot de passe: (défini dans AdminSeeder)

---

## 📞 SUPPORT

Pour toute question:
- Email: support@carrepremium.com
- Documentation: Voir les fichiers MD dans le projet
