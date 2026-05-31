# Developer Environment & Deployment Guide — gopi.blog

Welcome to the developer guide for the **Gopi K Portfolio & Blog System** (`gopi.blog`). This system is a high-performance personal portfolio, blog, and job listing system built on Laravel 10, Tailwind CSS, and MySQL. This document provides step-by-step instructions to configure your local developer machine, use our Git collaboration workflow, check in code safely, and manage the deployment process to the production server.

---

## 1. Local Development Environment Configuration

To configure your developer machine, follow this sequential guide to set up all system dependencies, databases, and assets.

### System Prerequisites
Before you begin, verify that your local development machine has the following system dependencies installed:
* **PHP 8.2+** (with mandatory extensions: `mbstring`, `bcmath`, `pdo_mysql`, `gd`, `zip`, `xml`)
* **Composer** (PHP Package Manager)
* **Node.js 22+** & **npm**
* **MySQL 8.0+** or MariaDB
* **Git**

### Step 1 — Clone the Repository
Clone the repository to your local developer machine and navigate into the project root directory:
```bash
git clone https://github.com/esscay2025/gopi-portfolio.git
cd gopi-portfolio
```

### Step 2 — Install Backend & Frontend Dependencies
Run the package managers to install all vendor and node modules required for the application:
```bash
# Install PHP dependencies via Composer
composer install

# Install Node.js modules via npm
npm install
```

### Step 3 — Configure Environment Variables
Copy the template configuration file to create your local `.env` environment file:
```bash
cp .env.example .env
```

Open `.env` in your text editor and update your local database connection parameters:
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
DB_PASSWORD=your_local_mysql_password
```

### Step 4 — Generate App Encryption Key & Public Symlink
Generate a unique application key for cookie and session encryption, and create the public storage link to enable file uploads (e.g., job resumes, blog images):
```bash
# Generate the encryption key
php artisan key:generate

# Create the public storage symbolic link
php artisan storage:link
```

### Step 5 — Local Database Initialization
Create a MySQL database named `gopi_portfolio_local` on your local server. Run the migrations to build the tables, and seed the database with initial admin credentials, user roles, and sample content:
```bash
# Execute database migrations and seeders
php artisan migrate --seed
```

### Step 6 — Run Development Servers
Start both the Laravel backend server and the Vite frontend asset compiler concurrently:
```bash
# Terminal 1: Run the Laravel built-in development server
php artisan serve

# Terminal 2: Run the Vite asset compiler for Tailwind CSS
npm run dev
```

You can now access the local application in your browser at [http://localhost:8000](http://localhost:8000).

---

## 2. Git Collaboration Workflow (Pull, Push, and Check-In)

We use a structured branching and contribution model to ensure code stability. The project contains three core branches:

| Branch | Stability | Target Environment | Deployment Trigger |
| :--- | :--- | :--- | :--- |
| **`main`** | Production Stable | `https://gopi.blog` | Tagged Release / Manual pull |
| **`staging`** | Pre-production Testing | `https://staging.gopik.in` | Automated push to `staging` |
| **`develop`** | Active Integration | Local developer machines | Pull Requests & Merge reviews |

### Daily Development Workflow

#### Step 1 — Pull Latest Changes
Before starting any development task, checkout the `develop` branch and pull the latest changes from the remote repository to ensure your local copy is completely up to date:
```bash
# Switch to develop branch
git checkout develop

# Pull latest changes from GitHub
git pull origin develop
```

#### Step 2 — Create a Feature or Bugfix Branch
Always work on a separate branch dedicated to a specific task. Name your branch using the format `feature/your-feature-name` or `bugfix/your-bug-description`:
```bash
git checkout -b feature/email-subscription-form
```

#### Step 3 — Develop and Test Locally
Write your code, style your components, and perform comprehensive testing on your local server. Make sure that you do not leave any temporary debugging code (such as `dd()` or `console.log`) in your commits.

#### Step 4 — Commit Your Changes
Stage your files and commit them using the **Conventional Commits** standard. This keeps the repository history readable and professional:
* `feat:` for new features (e.g., `feat: add newsletter subscribe block to about page`)
* `fix:` for bug fixes (e.g., `fix: resolve services config mapping for google oauth`)
* `docs:` for documentation updates (e.g., `docs: update development instructions`)
* `style:` for formatting, missing semi-colons, or minor UI adjustments

```bash
# Stage all changes
git add .

# Commit with a descriptive conventional message
git commit -m "feat: implement newsletter subscribe section on homepage and footer"
```

#### Step 5 — Pull Again Before Pushing
To prevent merge conflicts, switch back to `develop`, pull the latest remote changes, merge them into your feature branch, and resolve any conflicts locally:
```bash
# Fetch latest develop changes
git checkout develop
git pull origin develop

# Re-merge into your feature branch
git checkout feature/email-subscription-form
git merge develop
```

#### Step 6 — Push Your Branch & Create a Pull Request (PR)
Push your branch to GitHub and open a Pull Request targeting the `develop` branch:
```bash
git push origin feature/email-subscription-form
```
Go to the GitHub repository page, click **New Pull Request**, choose `develop` as the base branch, write a summary of your changes, and request a code review. Once approved, the PR will be merged into `develop`.

---

## 3. Deployment Process

Deployments to production can be handled manually via SSH (the current primary method) or automated via GitHub Actions once secrets are configured.

### Manual SSH Production Deployment (gopi.blog)

Perform these steps to deploy stable code from the `main` branch to the production server:

#### Step 1 — SSH into the Server
Open your terminal and establish a secure shell connection to the production server:
```bash
ssh username@gopi.blog
```

#### Step 2 — Navigate to the Web Root
Navigate to the directory where the portfolio application is installed:
```bash
cd /home/username/public_html
```

#### Step 3 — Update the Codebase
Fetch the latest changes from GitHub and perform a hard reset to match the remote `main` branch exactly, which ensures all local modifications or untracked files are cleared:
```bash
# Fetch remote branches
git fetch origin

# Reset the working directory to match the production main branch
git reset --hard origin/main
```

#### Step 4 — Install Dependencies & Migrate
Install production dependencies and run any outstanding database migrations:
```bash
# Install Composer dependencies optimized for production
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Run database migrations securely
php artisan migrate --force
```

#### Step 5 — Clear and Rebuild Production Cache
Laravel caches must be cleared and re-cached whenever files are pulled or `.env` is updated to prevent old configurations from being used:
```bash
# Clear all existing caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate optimized configuration, route, and view caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Step 6 — Verify Folder Permissions
Ensure the web server (Nginx/Apache) has proper write access to the storage and bootstrap cache folders:
```bash
chmod -R 755 storage bootstrap/cache
```

---

### Automated Deployment (CI/CD Setup)

The repository contains a pre-configured GitHub Actions workflow located in `docs/ci-cd-workflow.yml`. 

To activate automated deployments on push:
1. Copy the file to the correct workflows directory:
   ```bash
   mkdir -p .github/workflows
   cp docs/ci-cd-workflow.yml .github/workflows/deploy.yml
   ```
2. Configure the following secrets in your GitHub repository settings under **Settings → Secrets and variables → Actions**:
   * `PROD_HOST` — Production server IP address or hostname
   * `PROD_USER` — SSH username
   * `PROD_SSH_KEY` — Private SSH key used to connect to the production server

Once active, pushing code to the `staging` branch automatically deploys to the staging server, and pushing to the `main` branch or publishing a GitHub Release automatically deploys to the production server (`gopi.blog`).
