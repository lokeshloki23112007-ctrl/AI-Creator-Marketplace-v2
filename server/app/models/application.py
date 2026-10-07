from sqlalchemy import Column, Integer, String, Text, ForeignKey, DateTime
from sqlalchemy.orm import relationship
from sqlalchemy.sql import func
from app.db.base import Base

class Application(Base):
    __tablename__ = "applications"

    id = Column(Integer, primary_key=True, index=True, autoincrement=True)
    brief_id = Column(Integer, ForeignKey("briefs.id", ondelete="CASCADE"), nullable=False)
    creator_id = Column(Integer, ForeignKey("creator_profiles.id", ondelete="CASCADE"), nullable=False)
    pitch = Column(Text, nullable=False)
    proposed_rate = Column(String(100), nullable=True)
    status = Column(String(50), default="Pending", nullable=False)  # "Pending", "Accepted", "Declined"
    created_at = Column(DateTime(timezone=True), server_default=func.now(), nullable=False)

    # Relationships
    brief = relationship("Brief", back_populates="applications")
    creator = relationship("CreatorProfile", back_populates="applications")

    def __repr__(self) -> str:
        return f"<Application id={self.id} brief_id={self.brief_id} creator_id={self.creator_id}>"
