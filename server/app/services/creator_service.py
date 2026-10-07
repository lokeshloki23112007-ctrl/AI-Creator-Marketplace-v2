import json
from typing import List, Optional
from sqlalchemy.orm import Session
from sqlalchemy import or_
from app.models.user import User
from app.models.creator import CreatorProfile
from app.schemas.creator import CreatorProfileUpdate

def get_creator_by_id(db: Session, creator_id: int) -> Optional[CreatorProfile]:
    return db.query(CreatorProfile).filter(CreatorProfile.id == creator_id).first()

def get_creator_by_user_id(db: Session, user_id: int) -> Optional[CreatorProfile]:
    return db.query(CreatorProfile).filter(CreatorProfile.user_id == user_id).first()

def list_creators(
    db: Session,
    search: Optional[str] = None,
    filter_tag: Optional[str] = None,
    limit: int = 100,
    offset: int = 0
) -> List[CreatorProfile]:
    query = db.query(CreatorProfile).join(User, CreatorProfile.user_id == User.id)

    if search:
        s = f"%{search.strip().lower()}%"
        query = query.filter(
            or_(
                User.full_name.ilike(s),
                CreatorProfile.role.ilike(s),
                CreatorProfile.bio.ilike(s),
                CreatorProfile.specialization.ilike(s),
                CreatorProfile.skills_raw.ilike(s),
                CreatorProfile.tools_raw.ilike(s)
            )
        )

    if filter_tag and filter_tag.lower() != "all":
        f = f"%{filter_tag.strip().lower()}%"
        query = query.filter(
            or_(
                CreatorProfile.skills_raw.ilike(f),
                CreatorProfile.tools_raw.ilike(f),
                CreatorProfile.role.ilike(f),
                CreatorProfile.specialization.ilike(f)
            )
        )

    return query.order_by(CreatorProfile.rating.desc(), CreatorProfile.id.asc()).offset(offset).limit(limit).all()

def update_creator_profile(
    db: Session,
    creator: CreatorProfile,
    profile_in: CreatorProfileUpdate
) -> CreatorProfile:
    if profile_in.full_name is not None and creator.user:
        creator.user.full_name = profile_in.full_name.strip()
    
    if profile_in.role is not None:
        creator.role = profile_in.role
    if profile_in.bio is not None:
        creator.bio = profile_in.bio
    if profile_in.specialization is not None:
        creator.specialization = profile_in.specialization
    if profile_in.skills is not None:
        creator.skills = profile_in.skills
    if profile_in.tools is not None:
        creator.tools = profile_in.tools
    if profile_in.hourly_rate is not None:
        creator.hourly_rate = profile_in.hourly_rate
    if profile_in.location is not None:
        creator.location = profile_in.location
    if profile_in.avatar_url is not None:
        creator.avatar_url = profile_in.avatar_url
        if creator.user:
            creator.user.avatar_url = profile_in.avatar_url

    db.commit()
    db.refresh(creator)
    return creator

def serialize_creator(creator: CreatorProfile) -> dict:
    """Helper to convert CreatorProfile to response dict compatible with frontend."""
    return {
        "id": creator.id,
        "user_id": creator.user_id,
        "name": creator.user.full_name if creator.user else "Creator",
        "role": creator.role,
        "bio": creator.bio or "",
        "specialization": creator.specialization or "",
        "skills": creator.skills,
        "tools": creator.tools,
        "rating": creator.rating,
        "reviews": creator.reviews_count,
        "reviews_count": creator.reviews_count,
        "projects": creator.projects_count,
        "projects_count": creator.projects_count,
        "image": creator.avatar_url or (creator.user.avatar_url if creator.user else None),
        "avatar_url": creator.avatar_url or (creator.user.avatar_url if creator.user else None),
        "verified": creator.verified,
        "hourly_rate": creator.hourly_rate,
        "hourlyRate": creator.hourly_rate,
        "location": creator.location,
        "created_at": creator.created_at,
        "portfolios": [
            {
                "id": p.id,
                "creator_id": p.creator_id,
                "title": p.title,
                "contentType": p.content_type,
                "content_type": p.content_type,
                "description": p.description,
                "tools": p.tools,
                "skills": p.skills,
                "preview": p.preview_url,
                "preview_url": p.preview_url,
                "external_link": p.external_link,
                "created_at": p.created_at,
            }
            for p in creator.portfolios
        ]
    }
