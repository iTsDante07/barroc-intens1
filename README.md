# Barroc Intens - Installatie

## Vereisten

- PHP >= 8.1
- Composer
- Node.js >= 16.x & NPM
- MySQL/MariaDB
- Git

## Installatie

### Stap 1: Project ophalen

```bash
git clone <repository-url>
cd barroc-intens1
```

### Stap 2: Dependencies installeren

```bash
composer install
npm install
```

### Stap 3: Omgeving configureren

```bash
# Windows
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

### Stap 4: Applicatie sleutel genereren

```bash
php artisan key:generate
```

### Stap 5: Database configureren

1. Maak database aan:

```sql
CREATE DATABASE barroc_intens;
```

2. Bewerk `.env`:

```env
DB_DATABASE=barroc_intens
DB_USERNAME=jouw_gebruikersnaam
DB_PASSWORD=jouw_wachtwoord
```

### Stap 6: Database vullen

```bash
php artisan migrate:fresh --seed
```

### Stap 7: Storage link maken

```bash
php artisan storage:link
```

### Stap 8: Assets builden

```bash
npm run dev  # Development
# OF
npm run build  # Productie
```

### Stap 9: Applicatie starten

**Met Laravel Herd:**

- Open: `http://barroc-intens1.test`

**Met Artisan serve:**

```bash
php artisan serve
```

- Open: `http://localhost:8000`

## Extra Commando's

### Cache wissen

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Tests uitvoeren

```bash
php artisan test
```

### Queue worker

```bash
php artisan queue:work
```

## Troubleshooting

**Permissie fouten (Linux/Mac):**

```bash
chmod -R 775 storage bootstrap/cache
```

**Node modules opnieuw installeren:**

```bash
rm -rf node_modules package-lock.json
npm install
```

**Composer opnieuw installeren:**

```bash
rm -rf vendor composer.lock
composer install
```
