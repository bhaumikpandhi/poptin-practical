## Tech Stack

- **Backend**: Laravel 12 with PHP 8.2+
- **WebSocket Server**: Laravel Reverb
- **Frontend**: Vue/Blade templates with Vite bundler
- **Real-Time Communication**: Laravel Echo + WebSocket protocol
- **Database**: Configured for MySQL
- **Package Manager**: Composer (PHP) & npm (Node.js)
- **Testing**: PHPUnit

## Installation & Setup

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js (v22+) and npm
- MySQL database
- Redis (for queue/cache, optional)
- Nginx (for WebSocket proxy)

### Step 1: Clone and Install Dependencies

```bash
# Clone the repository
git clone <repository-url>
cd poptin-practical

# Install PHP dependencies
composer install

# Create environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Install Node.js dependencies
npm install
```

### Step 2: Configure Environment Variables

Edit `.env` file and set the following (with actual value)

```env
# Application
APP_NAME="Poptin Polls"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=poptin_practical
DB_USERNAME=root
DB_PASSWORD=

# Reverb WebSocket Configuration
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=0.0.0.0
REVERB_PORT=8080
REVERB_SCHEME=http
```

### Step 3: Database Setup

```bash
# Run migrations
php artisan migrate

# Seed initial data (roles and users)
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=UserSeeder
```

### Step 4: Build Frontend Assets

```bash
# Development build with watch mode
npm run dev

# Production build
npm run build
```

## Running the Application

Run each service in separate terminals:

#### Terminal 1 - Laravel Web Server
```bash
php artisan serve
```
Access the application at `http://localhost:8000`

#### Terminal 2 - WebSocket Server (Reverb)
```bash
php artisan reverb:start --host=0.0.0.0 --port=8080
```
The WebSocket server listens on `ws://localhost:8080`

#### Terminal 3 - Queue Worker
```bash
php artisan queue:listen --tries=1 --timeout=0
```

#### Terminal 4 - Frontend Development Server
```bash
npm run dev
```

## Nginx Configuration for WebSocket [optional]

This configuration enables WebSocket proxying if real-time update is not working

### Basic HTTP Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;

    # WebSocket proxy for Reverb (primary endpoint)
    location /app {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "Upgrade";
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
        proxy_read_timeout 60;
        proxy_send_timeout 60;
    }

    # Alternative WebSocket endpoint
    location /apps {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
    }

    # Laravel application (works with php artisan serve on port 8000)
    location / {
        proxy_pass http://127.0.0.1:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### HTTPS Configuration with SSL

```nginx
# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$host$request_uri;
}

# Main HTTPS server
server {
    listen 443 ssl;
    server_name your-domain.com;
    
    ssl_certificate "/path/to/certificate.crt";
    ssl_certificate_key "/path/to/certificate.key";

    # WebSocket proxy for Reverb (primary endpoint)
    location /app {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "Upgrade";
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
        proxy_read_timeout 60;
        proxy_send_timeout 60;
    }

    # Alternative WebSocket endpoint
    location /apps {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
    }

    # Laravel application (works with php artisan serve on port 8000)
    location / {
        proxy_pass http://127.0.0.1:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### Automated Testing

```bash
php artisan test
```

### Default Test Users

Created by seeders:
- **Admin**: admin@poptin.com / DefaultP@ssword1
- **Users**: user@poptin.com / DefaultP@ssword1

