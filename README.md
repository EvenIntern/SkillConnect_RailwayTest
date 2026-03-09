<div align="center">
    <img src="https://github.com/OddIntern/SkillConnect/blob/main/public/images/skillconnect-logo.png?raw=true" alt="SkillConnect Logo" width="350" />
    
  <p align="center">
    Connecting passionate volunteers with meaningful opportunities to build skills and experience.
  </p>
  
  <p align="center">
    <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel">
    <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php" alt="PHP">
    <img src="https://img.shields.io/badge/PostgreSQL-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL">
    <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  </p>
</div>

---

### 📝 About The Project

**SkillConnect** is a social media platform designed to be the bridge between volunteers and project organizers. Our main goal is to help users—from students to professionals—build their portfolios, sharpen new skills, and gain real-world experience by participating in various community projects.

This platform allows users to:
- **Discover Opportunities:** Find and browse a wide range of projects that match their interests and expertise.
- **Build a Reputation:** Create a professional profile that showcases their experience, skills, and contributions.
- **Collaborate:** Connect and communicate directly with project owners and fellow volunteers.

### ✨ Key Features

- ✅ **User Sign-up & Login:** A secure authentication system.
- 🔍 **Browse Projects:** View a list of available projects with search and filter capabilities.
- 📄 **Project Details:** See comprehensive information about each opportunity.
- ➕ **Create Projects:** Users can post their own projects to find volunteers.
- 📩 **Apply to Projects:** Apply to opportunities of interest.
- 💬 **Direct Messaging:** Communicate with project owners.
- 👤 **User Profiles:** A profile page that displays a user's activity, skills, and experience.
---
### ✅ Prerequisites

Before you begin, ensure you have the following installed on your system:
- **PHP** (version 8.2 or higher) with `pdo_pgsql` enabled
- **Composer**
- **Node.js & npm**
- **PostgreSQL** (local development) or a managed Postgres instance


Follow these steps carefully to get your development environment up and running.

**1. Clone the Repository**
   Open your terminal and clone the repository using Git.
   ```bash
   git clone [URL_OF_YOUR_GITHUB_REPOSITORY]
   cd skillconnect
   ```
   > **Note:** Replace `[URL_OF_YOUR_GITHUB_REPOSITORY]` with the actual URL and `skillconnect` with the project's folder name.

**2. Install PHP Dependencies**
   Install all the required PHP packages with Composer.
   ```bash
   composer install
   ```

**3. Install JavaScript Dependencies**
   Install the necessary frontend packages with npm.
   ```bash
   npm install
   ```

**4. Create Environment File**
   Copy the example environment file. This file stores your application's configuration.
   ```bash
   cp .env.example .env
   ```

**5. Generate Application Key**
   Generate a unique, secure key for your Laravel application.
   ```bash
   php artisan key:generate
   ```

**6. Configure Your Database**

Create a Postgres database named `skillconnect`, then update `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=skillconnect
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

**7. Run Database Migrations**
   Create all the necessary tables in your database.
   ```bash
   php artisan migrate
   ```

**8. Create The Public Storage Symlink**
   User avatars and banners are served from the configured filesystem disk.
   ```bash
   php artisan storage:link
   ```

**9. Build Frontend Assets**
   For local development, use `npm run dev` to compile assets and watch for changes.
   ```bash
   npm run dev
   ```

**10. Run the Development Server**
   Start the Laravel development server.
   ```bash
   php artisan serve
   ```

**11. Access the Application**
    You're all set! Open your web browser and go to the URL provided, usually:
    [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

### Production Direction

The near-term production target is a **Railway monolith deployment** backed by **Railway Postgres**. After launch, the intended split is:

- frontend on Vercel
- backend/API on Railway
- database on Railway Postgres

#### Railway Monolith Checklist

1. Provision a Railway Postgres service.
2. Set `DB_CONNECTION=pgsql`.
3. Set either `DATABASE_URL` or `DB_URL` from Railway's Postgres connection string.
4. Set `APP_ENV=production` and `APP_DEBUG=false`.
5. Run `php artisan migrate --force` during deployment.
6. Build frontend assets with `npm run build`.
7. Expose the Laravel health endpoint at `/up`.

#### Media Storage Best Practice

For the first Railway monolith deployment, you can run on the `public` disk and attach a persistent volume to `storage/app/public`.

For a production-grade multi-service architecture, switch `FILESYSTEM_DISK` to `s3` (or another S3-compatible object store) before scaling horizontally. Object storage is the ethical and operationally safe default for user-uploaded media because it avoids accidental data loss on redeploy or node replacement.
