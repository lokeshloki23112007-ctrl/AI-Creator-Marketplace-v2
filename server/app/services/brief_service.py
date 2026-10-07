from typing import List, Optional
from sqlalchemy.orm import Session
from app.models.brief import Brief
from app.models.application import Application
from app.models.user import User
from app.schemas.brief import BriefCreate, BriefUpdate, ApplicationCreate

def get_brief_by_id(db: Session, brief_id: int) -> Optional[Brief]:
    return db.query(Brief).filter(Brief.id == brief_id).first()

def list_briefs(
    db: Session,
    brand_id: Optional[int] = None,
    status: Optional[str] = None,
    content_type: Optional[str] = None,
    limit: int = 100,
    offset: int = 0
) -> List[Brief]:
    query = db.query(Brief)
    if brand_id is not None:
        query = query.filter(Brief.brand_id == brand_id)
    if status:
        query = query.filter(Brief.status == status)
    if content_type and content_type.lower() != "all":
        query = query.filter(Brief.content_type.ilike(f"%{content_type.strip()}%"))
    return query.order_by(Brief.created_at.desc()).offset(offset).limit(limit).all()

def create_brief(
    db: Session,
    brand_id: int,
    brief_in: BriefCreate
) -> Brief:
    brief = Brief(
        brand_id=brand_id,
        campaign_name=brief_in.campaign_name.strip(),
        description=brief_in.description.strip(),
        content_type=brief_in.content_type.strip(),
        style=brief_in.style.strip(),
        platform=brief_in.platform.strip(),
        format=brief_in.format.strip(),
        commercial_use=brief_in.commercial_use.strip(),
        budget=brief_in.budget.strip() if brief_in.budget else None,
        deadline=brief_in.deadline.strip() if brief_in.deadline else None,
        reference_url=brief_in.reference_url.strip() if brief_in.reference_url else None,
        status="Active",
    )
    db.add(brief)
    db.commit()
    db.refresh(brief)
    return brief

def update_brief(
    db: Session,
    brief: Brief,
    brief_in: BriefUpdate
) -> Brief:
    if brief_in.campaign_name is not None:
        brief.campaign_name = brief_in.campaign_name.strip()
    if brief_in.description is not None:
        brief.description = brief_in.description.strip()
    if brief_in.content_type is not None:
        brief.content_type = brief_in.content_type.strip()
    if brief_in.style is not None:
        brief.style = brief_in.style.strip()
    if brief_in.platform is not None:
        brief.platform = brief_in.platform.strip()
    if brief_in.format is not None:
        brief.format = brief_in.format.strip()
    if brief_in.commercial_use is not None:
        brief.commercial_use = brief_in.commercial_use.strip()
    if brief_in.budget is not None:
        brief.budget = brief_in.budget.strip()
    if brief_in.deadline is not None:
        brief.deadline = brief_in.deadline.strip()
    if brief_in.reference_url is not None:
        brief.reference_url = brief_in.reference_url.strip()
    if brief_in.status is not None:
        brief.status = brief_in.status.strip()

    db.commit()
    db.refresh(brief)
    return brief

def delete_brief(db: Session, brief: Brief) -> None:
    db.delete(brief)
    db.commit()

def apply_to_brief(
    db: Session,
    brief_id: int,
    creator_id: int,
    app_in: ApplicationCreate
) -> Application:
    application = Application(
        brief_id=brief_id,
        creator_id=creator_id,
        pitch=app_in.pitch.strip(),
        proposed_rate=app_in.proposed_rate.strip() if app_in.proposed_rate else None,
        status="Pending",
    )
    db.add(application)
    db.commit()
    db.refresh(application)
    return application

def serialize_brief(brief: Brief) -> dict:
    return {
        "id": brief.id,
        "brand_id": brief.brand_id,
        "brand_name": brief.brand.full_name if brief.brand else "Brand",
        "campaignName": brief.campaign_name,
        "campaign_name": brief.campaign_name,
        "description": brief.description,
        "contentType": brief.content_type,
        "content_type": brief.content_type,
        "style": brief.style,
        "platform": brief.platform,
        "format": brief.format,
        "commercialUse": brief.commercial_use,
        "commercial_use": brief.commercial_use,
        "budget": brief.budget,
        "deadline": brief.deadline,
        "reference": brief.reference_url,
        "reference_url": brief.reference_url,
        "status": brief.status,
        "created_at": brief.created_at,
        "applications_count": len(brief.applications),
        "applications": [
            {
                "id": a.id,
                "brief_id": a.brief_id,
                "creator_id": a.creator_id,
                "creator_name": a.creator.user.full_name if (a.creator and a.creator.user) else "Creator",
                "creator_avatar": a.creator.avatar_url if a.creator else None,
                "pitch": a.pitch,
                "proposedRate": a.proposed_rate,
                "proposed_rate": a.proposed_rate,
                "status": a.status,
                "created_at": a.created_at,
            }
            for a in brief.applications
        ],
    }
