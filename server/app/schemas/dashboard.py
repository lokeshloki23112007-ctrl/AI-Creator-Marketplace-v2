from typing import List, Optional
from pydantic import BaseModel, ConfigDict
from app.schemas.brief import BriefResponse
from app.schemas.portfolio import PortfolioProjectResponse

class CreatorDashboardStats(BaseModel):
    profile_completion: int
    active_proposals: int
    completed_projects: int
    total_views: int
    rating: float
    reviews_count: int
    recent_projects: List[PortfolioProjectResponse] = []

    model_config = ConfigDict(from_attributes=True)

class BrandDashboardStats(BaseModel):
    active_briefs_count: int
    creators_contacted_count: int
    completed_projects_count: int
    total_creators_available: int
    recent_briefs: List[BriefResponse] = []

    model_config = ConfigDict(from_attributes=True)
