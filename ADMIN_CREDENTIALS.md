# 🔐 IDENTIFIANTS D'ACCÈS - CARRÉ PREMIUM

## 👨‍💼 Comptes Administrateurs

### Super Administrateur
- **Email** : `admin@carrepremium.com`
- **Mot de passe** : `Admin@2024`
- **Rôle** : Super Admin
- **Permissions** : Accès complet à toutes les fonctionnalités

### Administrateur Manager
- **Email** : `manager@carrepremium.com`
- **Mot de passe** : `Manager@2024`
- **Rôle** : Admin
- **Permissions** : Gestion des contenus et réservations

### Modérateur
- **Email** : `moderator@carrepremium.com`
- **Mot de passe** : `Moderator@2024`
- **Rôle** : Moderator
- **Permissions** : Modération des avis et contenus

---

## 💰 Devises Configurées

| Code | Nom | Symbole | Taux de Change | Par Défaut |
|------|-----|---------|----------------|------------|
| XOF | Franc CFA | CFA | 1.000000 | ✅ Oui |
| EUR | Euro | € | 655.957 | Non |
| USD | US Dollar | $ | 600.000 | Non |
| GBP | British Pound | £ | 760.000 | Non |

---

## 🎨 Charte Graphique

### Couleurs Principales
- **Violet (Primaire)** : `#9333EA`
- **Doré (Secondaire)** : `#D4AF37`
- **Blanc (Fond)** : `#FFFFFF`
- **Footer** : `#9333EA` (Violet)

### Typographie
- **Titres** : Montserrat (Bold, SemiBold)
- **Corps** : Poppins (Regular, Medium)

---

## 📂 Catégories Créées

### Catégories Principales
1. **Vols** (`flights`)
2. **Événements Sportifs** (`sports-events`)
   - Tennis
   - Football
   - Formule 1
   - Basketball
3. **Événements Culturels** (`cultural-events`)
   - Concerts
   - Festivals
   - Théâtre
4. **Packages Touristiques** (`tour-packages`)
5. **Hélicoptère** (`helicopter`)
6. **Jet Privé** (`private-jet`)

---

## 🗄️ Base de Données

### Informations de Connexion
- **Type** : SQLite (développement) / MySQL (production)
- **Fichier SQLite** : `carre-premium-backend/database/database.sqlite`
- **Tables créées** : 28 tables

### Pour MySQL (Production)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carre_premium
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

---

## 🚀 Commandes Rapides

### Démarrer le serveur
```bash
cd carre-premium-backend
php artisan serve
```

### Réinitialiser la base de données
```bash
php artisan migrate:fresh --seed
```

### Accéder à l'application
- **Frontend** : http://localhost:3000 (à venir)
- **Backend API** : http://localhost:8000/api
- **Admin Panel** : http://localhost:8000/admin (à venir)

---

## 📞 Contact & Support

- **Email** : contact@carrepremium.com
- **Téléphone** : +225 XX XX XX XX XX
- **Adresse** : Abidjan, Côte d'Ivoire

---

## ⚠️ IMPORTANT - SÉCURITÉ

> **Ces identifiants sont pour le développement uniquement !**
> 
> En production, assurez-vous de :
> - Changer tous les mots de passe
> - Utiliser des mots de passe forts
> - Activer l'authentification à deux facteurs
> - Configurer les permissions appropriées
> - Sécuriser les variables d'environnement

---

**Dernière mise à jour** : 3 Octobre 2025
