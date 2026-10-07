from contextlib import asynccontextmanager
from fastapi import FastAPI, Depends, status
from fastapi.middleware.cors import CORSMiddleware
from sqlalchemy.orm import Session
from sqlalchemy import text
from app.core.config import settings
from app.db.session import SessionLocal, get_db
from app.db.init_db import init_db
from app.api.router import api_router

@asynccontextmanager
async def lifespan(app: FastAPI):
    # Startup: Ensure SQLite tables exist and seed initial data
    with SessionLocal() as db:
        init_db(db)
    yield
    # Shutdown logic if any

app = FastAPI(
    title=settings.PROJECT_NAME,
    description="Production-ready SQLite backend for the AI Creator Marketplace",
    version=settings.PROJECT_VERSION,
    lifespan=lifespan,
    docs_url="/docs",
    redoc_url="/redoc"
)

# Configure CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=settings.CORS_ORIGINS,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Include API Router
app.include_router(api_router, prefix=settings.API_V1_STR)

@app.get("/", tags=["General"])
def root():
    return {
        "status": "online",
        "message": "AI Creator Marketplace Backend is running with SQLite!",
        "version": settings.PROJECT_VERSION,
        "docs": "/docs",
        "api_v1": settings.API_V1_STR
    }

@app.get("/api/health", tags=["General"])
def health_check(db: Session = Depends(get_db)):
    """Health check endpoint that verifies SQLite database connectivity."""
    try:
        db.execute(text("SELECT 1"))
        return {
            "status": "healthy",
            "database": "sqlite",
            "connected": True
        }
    except Exception as e:
        return {
            "status": "unhealthy",
            "database": "sqlite",
            "connected": False,
            "error": str(e)
        }