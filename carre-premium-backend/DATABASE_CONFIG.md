# Configuration de la Base de Données

## Étapes de Configuration

### 1. Configurer le fichier .env

Ouvrez le fichier `carre-premium-backend/.env` et modifiez les paramètres de base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carre_premium
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 2. Créer la base de données MySQL

Exécutez ces commandes dans votre terminal MySQL :

```sql
CREATE DATABASE carre_premium CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Ou utilisez cette commande depuis le terminal :

```bash
mysql -u root -p -e "CREATE DATABASE carre_premium CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 3. Exécuter les migrations

```bash
cd carre-premium-backend
php artisan migrate
```

### 4. Alternative : Utiliser SQLite (pour développement rapide)

Si vous préférez SQLite pour le développement, le fichier .env est déjà configuré par défaut :

```env
DB_CONNECTION=sqlite
```

La base de données SQLite est déjà créée dans `database/database.sqlite`

## Commandes Utiles

### Réinitialiser la base de données
```bash
php artisan migrate:fresh
```

### Réinitialiser et remplir avec des données de test
```bash
php artisan migrate:fresh --seed
```

### Vérifier l'état des migrations
```bash
php artisan migrate:status
```

### Rollback de la dernière migration
```bash
php artisan migrate:rollback
```

## Prochaines Étapes

Après avoir configuré la base de données et exécuté les migrations, nous allons :

1. ✅ Créer les Seeders (données initiales)
2. ✅ Créer les Modèles Eloquent
3. ✅ Créer les Contrôleurs Admin
4. ✅ Créer les API Controllers
5. ✅ Configurer l'authentification JWT
6. ✅ Créer les routes
7. ✅ Créer les Services
