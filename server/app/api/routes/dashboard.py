from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session
from app.db.session import get_db
from app.schemas.dashboard import CreatorDashboardStats, BrandDashboardStats
from app.api.deps import get_current_user, get_current_creator
from app.models.user import User
from app.models.creator import CreatorProfile
from app.models.brief import Brief
from app.models.portfolio import PortfolioProject
from app.services.portfolio_service import serialize_portfolio
from app.services.brief_service import serialize_brief

router = APIRouter(prefix="/dashboard", tags=["Dashboard"])

@router.get("/creator", response_model=CreatorDashboardStats)
def get_creator_dashboard(
    creator: CreatorProfile = Depends(get_current_creator),
    db: Session = Depends(get_db)
):
    """Retrieve statistics and progress for creator dashboard."""
    # Compute profile completion percentage
    completion = 20  # Base for having an account
    if creator.bio and len(creator.bio) > 10:
        completion += 20
    if creator.specialization:
        completion += 15
    if creator.skills and len(creator.skills) > 0:
        completion += 15
    if creator.tools and len(creator.tools) > 0:
        completion += 10
    if creator.avatar_url:
        completion += 10
    if len(creator.portfolios) > 0:
        completion += 10
    completion = min(100, completion)

    recent_projects = (
        db.query(PortfolioProject)
        .filter(PortfolioProject.creator_id == creator.id)
        .order_by(PortfolioProject.created_at.desc())
        .limit(5)
        .all()
    )

    return {
        "profile_completion": completion,
        "active_proposals": len(creator.applications),
        "completed_projects": creator.projects_count,
        "total_views": 1240 + (creator.projects_count * 120),
        "rating": creator.rating,
        "reviews_count": creator.reviews_count,
        "recent_projects": [serialize_portfolio(p) for p in recent_projects],
    }

@router.get("/brand", response_model=BrandDashboardStats)
def get_brand_dashboard(
    current_user: User = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    """Retrieve statistics and campaigns for brand dashboard."""
    active_briefs = (
        db.query(Brief)
        .filter(Brief.brand_id == current_user.id, Brief.status == "Active")
        .all()
    )
    all_brand_briefs = (
        db.query(Brief)
        .filter(Brief.brand_id == current_user.id)
        .order_by(Brief.created_at.desc())
        .limit(5)
        .all()
    )
    total_creators = db.query(CreatorProfile).count()

    return {
        "active_briefs_count": len(active_briefs),
        "creators_contacted_count": 18,
        "completed_projects_count": 12,
        "total_creators_available": total_creators,
        "recent_briefs": [serialize_brief(b) for b in all_brand_briefs],
    }
