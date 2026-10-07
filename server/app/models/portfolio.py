import json
from typing import List
from sqlalchemy import Column, Integer, String, Text, ForeignKey, DateTime
from sqlalchemy.orm import relationship
from sqlalchemy.sql import func
from app.db.base import Base

class PortfolioProject(Base):
    __tablename__ = "portfolio_projects"

    id = Column(Integer, primary_key=True, index=True, autoincrement=True)
    creator_id = Column(Integer, ForeignKey("creator_profiles.id", ondelete="CASCADE"), nullable=False)
    title = Column(String(200), nullable=False)
    content_type = Column(String(100), default="Video", nullable=False)
    description = Column(Text, nullable=True)
    tools_raw = Column(Text, default="[]", nullable=False)
    skills_raw = Column(Text, default="[]", nullable=False)
    preview_url = Column(String(500), nullable=False)
    external_link = Column(String(500), nullable=True)
    created_at = Column(DateTime(timezone=True), server_default=func.now(), nullable=False)
    updated_at = Column(DateTime(timezone=True), server_default=func.now(), onupdate=func.now(), nullable=False)

    # Relationships
    creator = relationship("CreatorProfile", back_populates="portfolios")

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

    def __repr__(self) -> str:
        return f"<PortfolioProject id={self.id} title={self.title} creator_id={self.creator_id}>"
