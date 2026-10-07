# AI Creator Marketplace - Backend (FastAPI + SQLite)

A modular, high-performance backend built with **FastAPI**, **SQLAlchemy ORM**, and **SQLite**.

---

## 🚀 Features

- **SQLite Relational Engine**: Embedded, zero-configuration database stored locally at `server/marketplace.db`.
- **Foreign Key Integrity**: Enforced SQLite foreign key cascades via connection listeners.
- **Auto-Initialization & Seeding**: Automatically creates schema and populates creators, portfolios, and briefs on first launch.
- **Authentication & Security**: Secure bcrypt password hashing and PyJWT tokens.
- **Interactive Documentation**: Swagger UI at `/docs` and ReDoc at `/redoc`.
- **CORS Configured**: Ready for local frontend Vite dev server (`http://localhost:5173`).
- **Comprehensive Pytest Suite**: 23 automated tests covering auth, creators, portfolios, and briefs.

---

## 📁 Architecture Structure

```text
server/
├── app/
│   ├── api/
│   │   ├── routes/
│   │   │   ├── auth.py         # Login, registration, /me
│   │   │   ├── users.py        # User profile endpoints
│   │   │   ├── creators.py     # Explore creators, profile queries, edits
│   │   │   ├── portfolios.py   # Portfolio CRUD operations
│   │   │   ├── briefs.py       # Brand briefs & applications
│   │   │   └── dashboard.py    # Creator & Brand analytics
│   │   ├── deps.py             # Auth dependencies & token parsers
│   │   └── router.py           # Unified API router
│   ├── core/
│   │   ├── config.py           # Database URL, JWT, and CORS settings
│   │   └── security.py         # Bcrypt hashing and JWT encoding/decoding
│   ├── db/
│   │   ├── base.py             # Declarative base
│   │   ├── session.py          # SQLite engine & session maker
│   │   └── init_db.py          # Table initialization & initial seed data
│   ├── models/                 # SQLAlchemy ORM Models
│   │   ├── user.py             # User accounts (creators & brands)
│   │   ├── creator.py          # Creator profile details, skills, tools
│   │   ├── portfolio.py        # Creator portfolio media & links
│   │   ├── brief.py            # Brand campaign requirements
│   │   └── application.py      # Creator proposals for briefs
│   ├── schemas/                # Pydantic schemas (validation & serialization)
│   │   ├── common.py
│   │   ├── user.py
│   │   ├── creator.py
│   │   ├── portfolio.py
│   │   ├── brief.py
│   │   └── dashboard.py
│   ├── services/               # Business logic & database operations
│   │   ├── user_service.py
│   │   ├── creator_service.py
│   │   ├── portfolio_service.py
│   │   └── brief_service.py
│   └── main.py                 # FastAPI application & startup lifecycle
├── tests/                      # Automated test suite
│   ├── conftest.py
│   ├── test_auth.py
│   ├── test_creators.py
│   ├── test_portfolios.py
│   └── test_briefs.py
├── marketplace.db              # SQLite database file (generated automatically)
├── requirements.txt            # Python dependencies
├── run.py                      # Development server launcher
└── seed.py                     # Standalone database initialization / reset script
```

---

## 🛠️ Quick Start

### 1. Install Dependencies
```bash
py -m pip install -r requirements.txt
```

### 2. Start the Backend Server
```bash
py run.py
```
Or with uvicorn directly:
```bash
py -m uvicorn app.main:app --host 127.0.0.1 --port 8000 --reload
```

The server will be available at **`http://127.0.0.1:8000`**.  
Interactive Swagger API documentation: **`http://127.0.0.1:8000/docs`**.

---

## 🔑 Default Seeded Accounts

For testing immediately via `/docs` or frontend:

| Role | Name | Email | Password |
|---|---|---|---|
| **Creator** | Arun Kumar | `arun@example.com` | `password123` |
| **Creator** | Priya Nair | `priya@example.com` | `password123` |
| **Creator** | Rahul Singh | `rahul@example.com` | `password123` |
| **Creator** | Sneha Iyer | `sneha@example.com` | `password123` |
| **Brand** | XYZ Brand | `brand@example.com` | `password123` |

---

## 🗄️ Database Management

- **Database file**: `server/marketplace.db`
- **Re-seed or Reset Data**:
  ```bash
  # Initialize if not present
  py seed.py

  # Reset all tables and reload clean sample data
  py seed.py --reset
  ```

---

## 🧪 Running Tests

Run the complete test suite against an in-memory SQLite database:
```bash
py -m pytest tests/
```

---

## 📡 API Reference

### Authentication (`/api/auth`)
- `POST /api/auth/signup` - Register a new creator or brand account.
- `POST /api/auth/login` - Authenticate and retrieve JWT bearer token.
- `GET /api/auth/me` - Retrieve authenticated user information.

### Creators (`/api/creators`)
- `GET /api/creators` - List creators. Supports query parameters `?search=...` and `?filter=...` (e.g., Runway, Midjourney).
- `GET /api/creators/{id}` - Retrieve creator profile and their portfolio projects.
- `GET /api/creators/me` - Retrieve current creator's profile.
- `PUT /api/creators/me` - Update profile bio, specialization, skills, and tools.

### Portfolios (`/api/portfolios`)
- `GET /api/portfolios` - List portfolio items (supports `?creator_id=...` & `?contentType=...`).
- `GET /api/portfolios/{id}` - Retrieve single project.
- `POST /api/portfolios` - Add project to current creator's portfolio.
- `PUT /api/portfolios/{id}` - Update portfolio project.
- `DELETE /api/portfolios/{id}` - Remove project from portfolio.

### Campaign Briefs (`/api/briefs`)
- `GET /api/briefs` - List active project briefs.
- `GET /api/briefs/{id}` - Retrieve brief details and applicant proposals.
- `POST /api/briefs` - Create new brand brief.
- `PUT /api/briefs/{id}` - Update campaign brief.
- `DELETE /api/briefs/{id}` - Delete campaign brief.
- `POST /api/briefs/{id}/apply` - Creator applies to a brief with a pitch.

### Dashboard Stats (`/api/dashboard`)
- `GET /api/dashboard/creator` - Creator stats (profile completion, proposals, views, rating).
- `GET /api/dashboard/brand` - Brand stats (active briefs count, contacted creators).
