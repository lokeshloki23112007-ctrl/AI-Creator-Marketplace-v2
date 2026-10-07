"""Standalone script to initialize or reset and reseed the SQLite database."""
import sys
from pathlib import Path

# Add server directory to path
sys.path.insert(0, str(Path(__file__).resolve().parent))

from app.db.session import engine, SessionLocal
from app.db.base import Base
from app.db.init_db import init_db

def main(reset: bool = False):
    if reset:
        print("Dropping existing tables...")
        Base.metadata.drop_all(bind=engine)
    
    print("Creating tables and seeding SQLite database...")
    with SessionLocal() as db:
        init_db(db)
    print("Database initialization complete! File: marketplace.db")

if __name__ == "__main__":
    should_reset = "--reset" in sys.argv
    main(reset=should_reset)
