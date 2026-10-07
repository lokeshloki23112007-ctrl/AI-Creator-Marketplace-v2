import os
from pathlib import Path
from typing import List

# Base directory of the server
BASE_DIR = Path(__file__).resolve().parent.parent.parent

class Settings:
    PROJECT_NAME: str = "AI Creator Marketplace API"
    PROJECT_VERSION: str = "1.0.0"
    API_V1_STR: str = "/api"

    # SQLite Database Configuration
    DATABASE_FILE: str = os.getenv("DATABASE_FILE", str(BASE_DIR / "marketplace.db"))
    DATABASE_URL: str = os.getenv(
        "DATABASE_URL",
        f"sqlite:///{DATABASE_FILE}"
    )

    # JWT Authentication Configuration
    SECRET_KEY: str = os.getenv(
        "SECRET_KEY", 
        "a-very-secret-key-for-ai-creator-marketplace-dev-environment-2026"
    )
    ALGORITHM: str = "HS256"
    ACCESS_TOKEN_EXPIRE_MINUTES: int = 60 * 24  # 24 hours

    # CORS Configuration
    CORS_ORIGINS: List[str] = [
        "http://localhost:5173",
        "http://127.0.0.1:5173",
        "http://localhost:3000",
        "http://127.0.0.1:3000",
        "http://localhost:8000",
        "http://127.0.0.1:8000",
        "*"
    ]

settings = Settings()
