# 🏡 LuxeEstate — Real Estate Agency Platform

A multilingual, full-stack real estate platform built with **Laravel 12**, **Inertia.js**, **Vue 3**, and **Laravel Jetstream**.

---

## 🏗️ Architecture Overview

```
Laravel 12 + Jetstream + Inertia.js (Vue 3) + Tailwind CSS
```

### Patterns Used
- **Service Layer** — business logic isolated from controllers
- **DTO (Data Transfer Objects)** — typed, immutable data flow between layers
- **Form Request Validation** — clean, auto-authorized controller input
- **Observer Pattern** — model event handling (slugs, caching)
- **Enum-backed types** — `PropertyType`, `PropertyStatus`, `ContactStatus`

---

## 📁 Project Structure

```
app/
├── DTOs/
│   ├── Property/
│   │   ├── CreatePropertyDTO.php
│   │   └── UpdatePropertyDTO.php
│   ├── Blog/
│   │   ├── CreatePostDTO.php
│   │   └── UpdatePostDTO.php
│   ├── Service/
│   │   └── ServiceDTO.php
│   ├── Contact/
│   │   └── ContactInquiryDTO.php
│   └── Setting/
│       └── UpdateSettingDTO.php
│
├── Enums/
│   ├── PropertyType.php
│   ├── PropertyStatus.php
│   └── ContactStatus.php
│
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── PropertyController.php
│   │   │   ├── BlogController.php
│   │   │   ├── ServiceController.php
│   │   │   ├── ContactController.php
│   │   │   ├── UserController.php
│   │   │   ├── SettingController.php
│   │   │   ├── LanguageController.php
│   │   │   ├── CategoryController.php
│   │   │   └── TagController.php
│   │   ├── Auth/
│   │   │   ├── AuthenticatedSessionController.php
│   │   │   └── PasswordResetController.php
│   │   └── Frontend/
│   │       ├── HomeController.php
│   │       ├── PropertyController.php
│   │       ├── BlogController.php
│   │       ├── ServiceController.php
│   │       ├── ContactController.php
│   │       └── PageController.php
│   ├── Middleware/
│   │   ├── AdminMiddleware.php
│   │   ├── HandleInertiaRequests.php
│   │   └── SetLocale.php
│   └── Requests/
│       ├── Admin/
│       │   ├── StorePropertyRequest.php
│       │   ├── StorePostRequest.php
│       │   ├── StoreServiceRequest.php
│       │   └── UpdateSettingRequest.php
│       └── Public/
│           └── ContactRequest.php
│
├── Models/
│   ├── User.php
│   ├── Property.php  ← HasFactory, newFactory()
│   ├── PropertyImage.php
│   ├── PropertyTranslation.php
│   ├── Post.php      ← HasFactory, newFactory()
│   ├── PostTranslation.php
│   ├── Category.php  ← HasFactory, newFactory()
│   ├── Tag.php
│   ├── Service.php
│   ├── ServiceTranslation.php
│   ├── Contact.php   ← HasFactory, newFactory()
│   ├── Setting.php
│   └── Language.php
│
├── Services/
│   ├── Interfaces/
│   │   └── PropertyServiceInterface.php
│   ├── PropertyService.php
│   ├── BlogService.php
│   ├── ContactService.php
│   ├── SettingService.php
│   └── MediaService.php
│
└── Providers/
    └── AppServiceProvider.php

database/
├── factories/
│   ├── UserFactory.php
│   ├── PropertyFactory.php
│   ├── PostFactory.php
│   ├── CategoryFactory.php
│   └── ContactFactory.php
├── migrations/
│   ├── 2024_01_01_000001_create_users_and_sessions_table.php
│   ├── 2024_01_01_000002_create_cache_and_jobs_tables.php
│   ├── 2024_01_01_000003_create_languages_table.php
│   ├── 2024_01_01_000004_create_settings_table.php
│   ├── 2024_01_01_000005_create_properties_table.php
│   ├── 2024_01_01_000006_create_blog_tables.php
│   ├── 2024_01_01_000007_create_services_table.php
│   ├── 2024_01_01_000008_create_contacts_table.php
│   └── 2024_01_01_000009_create_personal_access_tokens_table.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── LanguageSeeder.php
    ├── SettingSeeder.php
    ├── UserSeeder.php
    ├── CategorySeeder.php
    ├── TagSeeder.php
    ├── ServiceSeeder.php
    └── DemoDataSeeder.php

resources/js/
├── Pages/
│   ├── Auth/
│   │   ├── Login.vue
│   │   ├── ForgotPassword.vue
│   │   └── ResetPassword.vue
│   ├── Admin/
│   │   ├── Dashboard.vue
│   │   ├── Properties/  Index.vue + Form.vue
│   │   ├── Blog/        Index.vue + Form.vue
│   │   ├── Services/    Index.vue + Form.vue
│   │   ├── Contacts/    Index.vue + Show.vue
│   │   ├── Users/       Index.vue + Form.vue
│   │   ├── Languages/   Index.vue + Form.vue
│   │   └── Settings/    Index.vue
│   ├── Public/
│   │   ├── Home.vue
│   │   ├── About.vue
│   │   ├── Contact.vue
│   │   ├── PrivacyPolicy.vue
│   │   ├── Terms.vue
│   │   ├── Properties/  Index.vue + Show.vue
│   │   ├── Blog/        Index.vue + Show.vue
│   │   └── Services/    Index.vue
│   └── Errors/
│       ├── 403.vue
│       └── 404.vue
├── Components/
│   ├── Admin/
│   │   ├── Sidebar.vue
│   │   ├── DataTable.vue
│   │   └── ImageUploader.vue
│   └── Public/
│       ├── Navbar.vue
│       ├── Footer.vue
│       ├── PropertyCard.vue
│       ├── PropertyFilter.vue
│       └── LanguageSwitcher.vue
└── Layouts/
    ├── AdminLayout.vue
    └── PublicLayout.vue

tests/
├── TestCase.php                       ← forces SQLite :memory: via putenv()
├── CreatesApplication.php             ← overrides config after app bootstrap
├── Helpers/
│   └── CreatesUsers.php               ← createAdmin(), createEditor(), createViewer()
├── Unit/
│   ├── Models/
│   │   ├── UserTest.php               ← role helpers (isAdmin, isSuperAdmin, isEditor)
│   │   ├── PropertyTest.php           ← trans() helper, enum values/labels/colors
│   │   └── SettingTest.php            ← get/set, type casting, group(), defaults
│   ├── DTOs/
│   │   ├── CreatePropertyDTOTest.php  ← type casting, enum mapping, readonly
│   │   └── ContactInquiryDTOTest.php  ← defaults, casting, readonly
│   └── Services/
│       ├── PropertyServiceTest.php    ← CRUD, translations, featured, pagination filters
│       ├── ContactServiceTest.php     ← submit, stats, paginate, mark replied
│       └── SettingServiceTest.php     ← updateMany, getForFrontend, type casts
└── Feature/
    ├── Auth/
    │   └── LoginTest.php              ← login/logout, wrong credentials, required fields
    ├── Admin/
    │   ├── DashboardTest.php          ← access control for all 3 roles
    │   ├── PropertyAdminTest.php      ← CRUD, translations, slug uniqueness, validation
    │   ├── BlogAdminTest.php          ← CRUD, toggle publish, slug uniqueness
    │   ├── ContactAdminTest.php       ← list, view (auto-read), mark replied, filter
    │   ├── UserAdminTest.php          ← admin-only, create/edit/delete, self-protect
    │   └── SettingsAdminTest.php      ← view, update, required validation
    └── Frontend/
        ├── PublicPropertyTest.php     ← listing, filters, detail, 404, view counter
        ├── PublicBlogTest.php         ← listing, published-only, category filter, 404
        └── ContactFormTest.php        ← submit, required fields, rate limiting
```

---

## 🚀 Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Install Node dependencies
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Configure your database in .env:
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=realestate
#    DB_USERNAME=root
#    DB_PASSWORD=your_password

# 5. Run migrations and seed default data
php artisan migrate --seed

# 6. Seed demo content (optional)
php artisan db:seed --class=DemoDataSeeder

# 7. Create public storage symlink
php artisan storage:link

# 8. Build frontend assets
npm run dev    # development (with HMR)
npm run build  # production

# 9. Create your first admin user
php artisan admin:create
```

---

## 🧪 Testing

### Test Database Setup

The test suite uses **SQLite in-memory** — no separate database is needed. This is configured automatically in `tests/TestCase.php` and `tests/CreatesApplication.php`, which override the database config after the app bootstraps.

**No additional setup is required.** Just run the tests:

```bash
# Run all tests (recommended)
php artisan test

# Run with verbose output
php artisan test --verbose

# Run a specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run a specific test file
php artisan test tests/Feature/Admin/PropertyAdminTest.php

# Run a specific test method
php artisan test --filter "admin_can_create_a_property"

# Run in parallel (faster on multi-core machines)
php artisan test --parallel

# Run with coverage report (requires Xdebug or PCOV)
php artisan test --coverage
php artisan test --coverage --min=80
```

### How the Test Database Works

```
Your .env (MySQL)          Test Environment
─────────────────          ─────────────────────────────────
DB_CONNECTION=mysql   →    DB_CONNECTION=sqlite   ← forced by TestCase
DB_DATABASE=realestate →   DB_DATABASE=:memory:   ← in-memory, wiped per test
```

`tests/TestCase.php` calls `putenv('DB_CONNECTION=sqlite')` before the app
bootstraps, and `tests/CreatesApplication.php` calls `$app['config']->set()`
after bootstrap. **Both layers together guarantee the test database is always
SQLite in-memory**, regardless of what is in your `.env` file.

Each test class that needs the database uses the `RefreshDatabase` trait, which
runs migrations fresh before each test class and wraps each test in a transaction
that is rolled back afterwards — keeping tests completely isolated.

### If You Prefer a Dedicated MySQL Test Database

If you want to run tests against a real MySQL database instead of SQLite
(e.g. to test MySQL-specific behaviour), create a dedicated database and add
an `.env.testing` file:

```bash
# 1. Create the test database in MySQL
mysql -u root -p -e "CREATE DATABASE realestate_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Create .env.testing in your project root
```

```dotenv
# .env.testing
APP_ENV=testing
APP_KEY=base64:your_key_here

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=realestate_test
DB_USERNAME=root
DB_PASSWORD=your_password

CACHE_STORE=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
MAIL_MAILER=array
BCRYPT_ROUNDS=4
```

```bash
# 3. Run tests — Laravel automatically loads .env.testing when APP_ENV=testing
php artisan test
```

> **Note:** You must also remove the `putenv()` overrides in `tests/TestCase.php`
> if you want `.env.testing` to take full control.

### Test Structure

```
80+ test cases across 21 files
│
├── Unit Tests (no HTTP, pure logic)
│   ├── Models    — role checks, enum values, translation helpers, setting casts
│   ├── DTOs      — type casting, immutability, factory method
│   └── Services  — CRUD operations, filter logic, stats
│
└── Feature Tests (full HTTP + database)
    ├── Auth      — login/logout, wrong credentials, validation
    ├── Admin     — CRUD for every resource, access control per role
    └── Frontend  — public pages, filters, pagination, rate limiting
```

### Writing a New Test

#### Unit Test (no database needed)

```php
// tests/Unit/Services/MyServiceTest.php
namespace Tests\Unit\Services;

use App\Services\MyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyServiceTest extends TestCase
{
    use RefreshDatabase;  // only needed if you query the DB

    private MyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MyService();
    }

    /** @test */
    public function it_does_something_correctly(): void
    {
        $result = $this->service->doSomething('input');

        $this->assertSame('expected', $result);
    }
}
```

#### Feature Test (HTTP requests)

```php
// tests/Feature/Admin/MyResourceTest.php
namespace Tests\Feature\Admin;

use App\Models\MyModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\CreatesUsers;
use Tests\TestCase;

class MyResourceTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    /** @test */
    public function admin_can_view_resource_list(): void
    {
        $admin = $this->createAdmin();       // creates an admin user
        MyModel::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get('/admin/my-resource');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/MyResource/Index')
            ->has('items')
        );
    }

    /** @test */
    public function editor_can_create_resource(): void
    {
        $editor = $this->createEditor();     // editor role

        $response = $this->actingAs($editor)->post('/admin/my-resource', [
            'name' => 'My New Item',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('my_resources', ['name' => 'My New Item']);
    }

    /** @test */
    public function guest_is_redirected_to_login(): void
    {
        $this->get('/admin/my-resource')->assertRedirect('/login');
    }
}
```

#### Available Test Helpers

```php
// In any test class that uses Tests\Helpers\CreatesUsers:

$admin  = $this->createAdmin();   // role: admin  — full access
$editor = $this->createEditor();  // role: editor — content only
$viewer = $this->createViewer();  // role: viewer — read only

// Pass overrides:
$admin = $this->createAdmin(['email' => 'boss@example.com']);

// Seed the 3 default languages (en, ar, tr) needed by locale-aware tests:
$this->seedLanguages();
```

#### Creating Factories

All model factories live in `database/factories/`. To create a new one:

```bash
php artisan make:factory MyModelFactory --model=MyModel
```

Example factory:
```php
// database/factories/MyModelFactory.php
namespace Database\Factories;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class MyModelFactory extends Factory
{
    protected $model = MyModel::class;

    public function definition(): array
    {
        return [
            'name'   => $this->faker->words(3, true),
            'status' => 'active',
        ];
    }
}
```

Then add `HasFactory` and `newFactory()` to your model:

```php
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MyModel extends Model
{
    use HasFactory;

    // Required — use fully-qualified class name to avoid autoload issues
    protected static function newFactory()
    {
        return \Database\Factories\MyModelFactory::new();
    }
}
```

> **Why `newFactory()` instead of just `HasFactory`?**
> Laravel normally resolves factory classes by naming convention at runtime, which
> requires `composer dump-autoload` to have been run. Using an explicit
> `newFactory()` with a fully-qualified class name bypasses the autoloader map
> entirely and always works.

---

## 🌍 Multi-Language Setup

Languages supported out of the box: **English (en)**, **Arabic (ar)**, **Turkish (tr)**

All translatable content uses the translations table pattern:

| Model    | Translation Table          | Translatable Fields             |
|----------|----------------------------|---------------------------------|
| Property | `property_translations`    | title, description, address, features |
| Post     | `post_translations`        | title, excerpt, content         |
| Service  | `service_translations`     | title, description, content     |
| Category | `locale_name` (JSON col)   | name                            |

Translation keys for UI strings: `lang/{en,ar,tr}/messages.php`

---

## 🔒 Security

- Public registration **disabled** — accounts created by admin only
- All admin routes protected by `auth` + `AdminMiddleware`
- Role hierarchy: `admin` > `editor` > `viewer`
- CSRF protection on all forms via Inertia
- Rate limiting on contact form (5 requests/minute per IP)
- Passwords hashed with bcrypt (rounds: 12 prod, 4 test)

---

## 👥 Roles & Permissions

| Feature              | admin | editor | viewer |
|----------------------|:-----:|:------:|:------:|
| View dashboard       | ✅    | ✅     | ✅     |
| Manage properties    | ✅    | ✅     | ❌     |
| Manage blog          | ✅    | ✅     | ❌     |
| Manage services      | ✅    | ✅     | ❌     |
| View contacts        | ✅    | ✅     | ✅     |
| Reply to contacts    | ✅    | ✅     | ❌     |
| Manage users         | ✅    | ❌     | ❌     |
| Manage languages     | ✅    | ❌     | ❌     |
| Manage settings      | ✅    | ❌     | ❌     |

---

## 📲 WhatsApp Notifications (Twilio)

When a visitor submits the contact form, the admin receives both an **email** and a **WhatsApp message** simultaneously via the Twilio API.

### How It Works

The `ContactService::submit()` method fires two notifications after saving a contact inquiry:

1. **Email** — queued via `ContactInquiryMail` (existing behaviour)
2. **WhatsApp** — sent via `WhatsAppService` using the Twilio REST API

```php
// Notify admin via email
$adminEmail = Setting::get('contact_email');
if ($adminEmail) {
    Mail::to($adminEmail)->queue(new ContactInquiryMail($contact));
}

// Notify admin via WhatsApp
$adminWhatsApp = Setting::get('contact_whatsapp');
if ($adminWhatsApp) {
    app(WhatsAppService::class)->sendContactInquiryNotification($contact);
}
```

### Setup

**1. Install the Twilio SDK**

```bash
composer require twilio/sdk
```

**2. Add credentials to `.env`**

```env
TWILIO_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_AUTH_TOKEN=your_auth_token_here
TWILIO_WHATSAPP_FROM=your_twilio_number
TWILIO_WHATSAPP_TO=your_number
```

> **Where to find your Auth Token:** Log in to [console.twilio.com](https://console.twilio.com) → Dashboard. Click the 👁 eye icon next to **Auth Token** to reveal it. Never commit it to Git.

**3. Add to `config/services.php`**

```php
'twilio' => [
    'sid'            => env('TWILIO_SID'),
    'token'          => env('TWILIO_AUTH_TOKEN'),
    'whatsapp_from'  => env('TWILIO_WHATSAPP_FROM'),
    'whatsapp_to'    => env('TWILIO_WHATSAPP_TO'),
],
```

**4. Add `contact_whatsapp` to your settings**

Add the admin's WhatsApp number to the `settings` table (key: `contact_whatsapp`) via the Settings panel or seeder.

### WhatsApp Message Format

```
📩 New Contact Inquiry
Name: John Smith
Email: john@example.com
Phone: +447700000000
Subject: Property Enquiry
Message: I'm interested in the listing on...
```

### Notes

- The recipient number must have **opted in** to your Twilio WhatsApp sandbox, or you must use an approved WhatsApp Business sender.
- To avoid blocking the HTTP request on slow Twilio responses, consider wrapping `WhatsAppService` in a queued Job.
- To use template messages instead, replace `body` with `contentSid` and `contentVariables` in `WhatsAppService::sendContactInquiryNotification()`.

---

## 🔧 Key Commands

```bash
# Create admin user interactively
php artisan admin:create

# Fresh database with seed data
php artisan migrate:fresh --seed

# Seed demo properties and blog posts
php artisan db:seed --class=DemoDataSeeder

# Clear all application caches
php artisan optimize:clear

# Run tests
php artisan test
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
php artisan test --parallel
php artisan test --coverage
```
