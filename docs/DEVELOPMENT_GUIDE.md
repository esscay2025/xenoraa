# Developer Environment & Deployment Guide — gopi.blog

Welcome to the official developer guide for the **Gopi K Portfolio & Blog System** (`gopi.blog`). This document provides comprehensive, step-by-step instructions for setting up your local development environment, understanding the git branching and checkout workflows, and deploying updates to the production server.

---

## 1. Local Development Setup

Follow these steps to set up the project on your local machine.

### Prerequisites
Before you begin, ensure you have the following installed on your machine:
* **PHP 8.2+** (with `mbstring`, `bcmath`, `pdo_mysql`, `gd`, `zip` extensions)
* **Composer** (PHP Package Manager)
* **Node.js 22+** & **npm**
* **MySQL 8.0+** or MariaDB
* **Git**

### Step 1: Clone the Repository
Clone the repository to your local development machine and navigate into the project directory:
```bash
git clone https://github.com/esscay2025/gopi-portfolio.git
cd gopi-portfolio
```

### Step 2: Install Dependencies
Install the required PHP and Node.js dependencies:
```bash
# Install PHP dependencies
composer install

# Install frontend dependencies
npm install
```

### Step 3: Configure Environment Variables
Copy the template environment file to create your local `.env` configuration:
```bash
cp .env.example .env
```

Open `.env` in your text editor and configure your local database credentials:
```env
APP_NAME="Gopi K Portfolio"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gopi_portfolio_local
DB_USERNAME=root
DB_PASSWORD=your_local_password
```

### Step 4: Generate Application Key & Symlink
Generate the unique application encryption key and create the storage symbolic link:
```bash
# Generate the application key
php artisan key:generate

# Create the symlink for public uploads
php artisan storage:link
```

### Step 5: Database Setup
Create a MySQL database named `gopi_portfolio_local` on your local server. Then, run the migrations and seed the database with initial sample data (including the default administrator account, roles, and sample blog posts):
```bash
# Run migrations and seeders
php artisan migrate --seed
```

### Step 6: Start the Development Servers
You will need to run both the PHP built-in server and the Vite development server concurrently:
```bash
# Terminal 1: Run the Laravel backend server
php artisan serve

# Terminal 2: Run the Vite frontend compilation server
npm run dev
```

You can now access the application at [http://localhost:8000](http://localhost:8000).

---

## 2. Git Workflow & Contribution Guidelines

We follow a structured branching strategy to maintain code quality and ensure stable deployments.

### Branching Strategy

| Branch | Stability | Target Environment | Deployment Trigger |
| :--- | :--- | :--- | :--- |
| **`main`** | Production Stable | `https://gopi.blog` | Manual or Tagged Release |
| **`staging`** | Pre-production Testing | `https://staging.gopik.in` | Push to `staging` branch |
| **`develop`** | Active Integration | Local Dev Environments | Manual Pull / CI Tests |

### Step-by-Step Development Flow

#### 1. Sync your Local Repository
Before starting any new work, always pull the latest changes from the remote repository to avoid merge conflicts:
```bash
git checkout develop
git pull origin develop
```

#### 2. Create a Feature Branch
Create a descriptive branch for your feature or bug fix originating from `develop`:
```bash
# Format: feature/feature-name or bugfix/bug-description
git checkout -b feature/newsletter-subscription
```

#### 3. Commit Changes
Make your changes and commit them using clear, concise commit messages. Follow the conventional commits format (e.g., `feat:`, `fix:`, `docs:`, `style:`):
```bash
git add .
git commit -m "feat: add email subscription section to footer and homepage"
```

#### 4. Push and Create Pull Request
Push your feature branch to GitHub and create a Pull Request (PR) targeting the `develop` branch:
```bash
git push origin feature/newsletter-subscription
```
Go to GitHub and open a Pull Request. Ensure all automated CI checks pass before requesting a code review.

---

## 3. Deployment Process

Deployments to production can be handled manually via SSH or automatically using our CI/CD workflow.

### Manual SSH Deployment (Current Production Setup)

To deploy updates manually to the production server at `gopi.blog`, perform the following steps:

#### Step 1: Connect to the Production Server
Establish an SSH connection to the production server:
```bash
ssh username@gopi.blog
```

#### Step 2: Navigate to the Web Root
Navigate to the active project directory:
```bash
cd /home/username/public_html
```

#### Step 3: Pull the Latest Code
Reset any untracked file conflicts and pull the latest changes from the stable `main` branch:
```bash
# Fetch latest references
git fetch origin

# Align local files to main branch
git reset --hard origin/main
```

#### Step 4: Run Database Migrations
If there are any new migrations, run them in production safely using the `--force` flag:
```bash
php artisan migrate --force
```

#### Step 5: Clear and Rebuild Production Cache
To ensure all configuration changes, routes, and views are optimized and updated, clear and cache the application state:
```bash
# Clear existing caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild optimized production caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Step 6: Verify Permissions
Ensure the web server has write permissions to the storage and cache directories:
```bash
chmod -R 755 storage bootstrap/cache
```

---

## 4. Google & Facebook OAuth Configuration

OAuth integrations are pre-configured in `config/services.php` and require specific environment variables in the production `.env` file.

### Environment Variables Matrix

| Provider | Key | Redirect URI |
| :--- | :--- | :--- |
| **Google** | `GOOGLE_CLIENT_ID`<br>`GOOGLE_CLIENT_SECRET` | `https://gopi.blog/auth/google/callback` |
| **Facebook** | `FACEBOOK_APP_ID`<br>`FACEBOOK_APP_SECRET` | `https://gopi.blog/auth/facebook/callback` |

> **Important Deployment Note:** Whenever you modify any OAuth credentials or environment keys in `.env`, you **must** run `php artisan config:cache` on the production server for the changes to take effect.
