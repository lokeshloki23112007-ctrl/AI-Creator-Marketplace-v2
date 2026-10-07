import json
from typing import List
from sqlalchemy import Column, Integer, String, Boolean, Float, Text, ForeignKey, DateTime
from sqlalchemy.orm import relationship
from sqlalchemy.sql import func
from app.db.base import Base

class CreatorProfile(Base):
    __tablename__ = "creator_profiles"

    id = Column(Integer, primary_key=True, index=True, autoincrement=True)
    user_id = Column(Integer, ForeignKey("users.id", ondelete="CASCADE"), unique=True, nullable=False)
    role = Column(String(100), default="AI Creator", nullable=False)  # e.g. "AI Video Creator"
    bio = Column(Text, nullable=True)
    specialization = Column(String(150), nullable=True)
    skills_raw = Column(Text, default="[]", nullable=False)
    tools_raw = Column(Text, default="[]", nullable=False)
    rating = Column(Float, default=4.8, nullable=False)
    reviews_count = Column(Integer, default=0, nullable=False)
    projects_count = Column(Integer, default=0, nullable=False)
    avatar_url = Column(String(500), nullable=True)
    verified = Column(Boolean, default=True, nullable=False)
    hourly_rate = Column(String(50), default="$75/hr", nullable=True)
    location = Column(String(100), default="Remote", nullable=True)
    created_at = Column(DateTime(timezone=True), server_default=func.now(), nullable=False)
    updated_at = Column(DateTime(timezone=True), server_default=func.now(), onupdate=func.now(), nullable=False)

    # Relationships
    user = relationship("User", back_populates="creator_profile")
    portfolios = relationship("PortfolioProject", back_populates="creator", cascade="all, delete-orphan")
    applications = relationship("Application", back_populates="creator", cascade="all, delete-orphan")

    @property
    def skills(self) -> List[str]:
        if not self.skills_raw:
            return []
        try:
            return json.loads(self.skills_raw)
        except Exception:
            return [s.strip() for s in self.skills_raw.split(",") if s.strip()]

    @skills.setter
    def skills(self, value: List[str]) -> None:
        if isinstance(value, list):
            self.skills_raw = json.dumps(value)
        elif isinstance(value, str):
            try:
                parsed = json.loads(value)
                if isinstance(parsed, list):
                    self.skills_raw = json.dumps(parsed)
                else:
                    self.skills_raw = json.dumps([s.strip() for s in value.split(",") if s.strip()])
            except Exception:
                self.skills_raw = json.dumps([s.strip() for s in value.split(",") if s.strip()])
        else:
            self.skills_raw = "[]"

    @property
    def tools(self) -> List[str]:
        if not self.tools_raw:
            return []
        try:
            return json.loads(self.tools_raw)
        except Exception:
            return [t.strip() for t in self.tools_raw.split(",") if t.strip()]

    @tools.setter
    def tools(self, value: List[str]) -> None:
        if isinstance(value, list):
            self.tools_raw = json.dumps(value)
        elif isinstance(value, str):
            try:
                parsed = json.loads(value)
                if isinstance(parsed, list):
                    self.tools_raw = json.dumps(parsed)
                else:
                    self.tools_raw = json.dumps([t.strip() for t in value.split(",") if t.strip()])
            except Exception:
                self.tools_raw = json.dumps([t.strip() for t in value.split(",") if t.strip()])
        else:
            self.tools_raw = "[]"

    def __repr__(self) -> str:
        return f"<CreatorProfile id={self.id} user_id={self.user_id} role={self.role}>"
