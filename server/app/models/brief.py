from sqlalchemy import Column, Integer, String, Text, ForeignKey, DateTime
from sqlalchemy.orm import relationship
from sqlalchemy.sql import func
from app.db.base import Base

class Brief(Base):
    __tablename__ = "briefs"

    id = Column(Integer, primary_key=True, index=True, autoincrement=True)
    brand_id = Column(Integer, ForeignKey("users.id", ondelete="CASCADE"), nullable=False)
    campaign_name = Column(String(255), nullable=False)
    description = Column(Text, nullable=False)
    content_type = Column(String(100), default="Video", nullable=False)
    style = Column(String(100), default="Cinematic", nullable=False)
    platform = Column(String(100), default="Instagram", nullable=False)
    format = Column(String(50), default="16:9", nullable=False)
    commercial_use = Column(String(50), default="Yes", nullable=False)
    budget = Column(String(100), nullable=True)
    deadline = Column(String(100), nullable=True)
    reference_url = Column(String(500), nullable=True)
    status = Column(String(50), default="Active", nullable=False)  # "Active", "In Progress", "Completed", "Closed"
    created_at = Column(DateTime(timezone=True), server_default=func.now(), nullable=False)
    updated_at = Column(DateTime(timezone=True), server_default=func.now(), onupdate=func.now(), nullable=False)

    # Relationships
    brand = relationship("User", back_populates="briefs")
    applications = relationship("Application", back_populates="brief", cascade="all, delete-orphan")

    def __repr__(self) -> str:
        return f"<Brief id={self.id} campaign={self.campaign_name} brand_id={self.brand_id}>"
