# AI Creator Marketplace (PHP & MySQL - XAMPP Ready)

A full-stack marketplace connecting brands with elite AI content creators specializing in **30-second video advertisements**, generative visuals, AI animations, and brand campaigns.

---

## 🚀 Key Features

1. **MySQL Database Architecture**: Fully migrated from SQLite to relational MySQL (`database.sql`).
2. **Zero Hardcoded Data**: All creators, portfolios, stats, and campaign briefs are loaded dynamically from MySQL using PDO.
3. **Profile Updates with Photo Upload (All User Logins)**:
   - **Creators**: Can edit name, role title, bio, specialization, skills, rate, location, and upload their profile picture.
   - **Brands**: Can edit brand name, contact email, company bio, website, and upload their brand logo.
4. **30-Second Advertisement Video Upload**:
   - Creators can upload video advertisements (`.mp4`, `.webm`, `.mov`).
   - Built-in client-side and server-side validation ensuring video duration does not exceed **30 seconds**.
   - Live video preview player with duration badge (`⚡ 30s Ad Video`).
5. **Brand Login & Personalized Welcome**:
   - Dedicated Brand Portal login.
   - Immediately mentions and greets the brand by name upon login (e.g. `Welcome, XYZ Brand! 👋`).
   - Top navigation and dashboard header dynamically display the authenticated brand's name and logo.
6. **100% Preserved UI Model**: The sleek cyber-neon dark aesthetic, Tailwind layout, glassmorphic cards, gradient accents, and sidebar navigation are completely preserved.
7. **Native XAMPP Support**: Zero build steps or Node dependencies needed. Simply drop into `htdocs` and run!

---

## 🛠️ How to Host in XAMPP

### Step 1: Place Project in `htdocs`
Copy or extract the `ai-creator-marketplace` folder into your XAMPP web root directory:
```text
C:\xampp\htdocs\ai-creator-marketplace
```

### Step 2: Start Apache and MySQL in XAMPP
1. Open the **XAMPP Control Panel**.
2. Click **Start** next to **Apache**.
3. Click **Start** next to **MySQL**.

### Step 3: Import the Database in phpMyAdmin (or Auto-Initialize)
1. Open your browser and go to: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Click on the **Import** tab.
3. Choose the file `database.sql` located inside the project root folder.
4. Click **Go** to create the `ai_creator_marketplace` database with pre-seeded creators, briefs, and portfolios.

*(Note: If you visit the site before importing, `config/db.php` will automatically create the database and execute `database.sql` for you!)*

### Step 4: Open the Web Application
Open your browser and navigate to:
```text
http://localhost/ai-creator-marketplace/
```

---

## 🔑 Pre-seeded Demo Accounts

| User Type | Name / Brand | Email | Password | Features to Test |
|---|---|---|---|---|
| **Brand** | XYZ Brand | `brand@example.com` | `password123` | Brand Login, "Welcome, XYZ Brand!" greeting, Edit Brand Logo, Create Brief, Hire Creator |
| **Brand** | Apex Athletics | `nike@example.com` | `password123` | Secondary brand account, active campaigns |
| **Creator** | Arun Kumar | `arun@example.com` | `password123` | Creator Dashboard, Edit Profile & Photo, Upload 30s Ad Video |
| **Creator** | Priya Nair | `priya@example.com` | `password123` | Visual AI Specialist, Midjourney Lookbooks |
| **Creator** | Rahul Singh | `rahul@example.com` | `password123` | 3D AI Animation Specialist, 30s VFX Ads |
| **Creator** | Sneha Iyer | `sneha@example.com` | `password123` | Short-form Viral Ads & Social Media |

---

## 📁 Project File Structure

```text
ai-creator-marketplace/
├── database.sql                  # Complete MySQL schema & initial seed data
├── README.md                     # XAMPP setup and usage guide
├── config/
│   └── db.php                    # PDO MySQL database connection & file upload helpers
├── includes/
│   ├── header.php                # Top navigation header & user avatar dropdown
│   ├── footer.php                # Platform footer with AI Chatbot inclusion
│   ├── chatbot.php               # Floating AI Assistant chatbot component
│   ├── creator_sidebar.php       # Creator navigation sidebar
│   └── brand_sidebar.php         # Brand navigation sidebar
├── uploads/
│   ├── avatars/                  # Uploaded profile photos and brand logos
│   └── videos/                   # Uploaded 30-second advertisement videos
├── index.php                     # Marketplace home page (Dynamic featured creators)
├── login.php                     # Brand & Creator login with welcome notification
├── signup.php                    # User registration (Creator or Brand)
├── logout.php                    # Session cleanup & logout handler
├── chatbot.php                   # Full-page interactive AI Marketplace Assistant
├── chatbot_api.php               # Chatbot backend API for recommendations & Q&A
├── creator_dashboard.php         # Creator dashboard with dynamic statistics
├── creator_profile.php           # Edit profile & upload profile picture
├── creator_portfolio.php         # Portfolio list with HTML5 video ad players
├── creator_portfolio_add.php     # Upload 30-second advertisement video with duration validation
├── creator_tools.php             # Manage AI tools (Runway, Kling, etc.) and models
├── creator_view.php              # Public creator profile view for brands to hire
├── brand_dashboard.php           # Brand dashboard greeting brand by name
├── brand_profile.php             # Edit brand details & upload company logo
├── brand_brief.php               # Create advertising brief with video reference
├── brand_creators.php            # Search and 12 Brand Filters from MySQL
├── about.php                     # About page
├── privacy.php                   # Privacy policy
├── terms.php                     # Terms of service
└── support.php                   # Support documentation
```

---

## 🎬 Testing 30-Second Video Advertisement Upload
1. Log in as a Creator (`arun@example.com` / `password123`).
2. Navigate to **Upload 30s Ad Video** (`creator_portfolio_add.php`).
3. Select an MP4 or WebM video:
   - If the video is **30 seconds or less**, the duration badge will confirm `Duration: Xs (Valid 30s advertisement)` and show a live player preview.
   - If the video **exceeds 30 seconds**, the browser will alert you that it exceeds the limit and reject the file.
4. Click **Publish Project** to save it to MySQL and view it in your portfolio!
