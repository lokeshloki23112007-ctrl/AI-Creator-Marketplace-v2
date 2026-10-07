from typing import List, Optional
from fastapi import APIRouter, Depends, HTTPException, Query, status
from sqlalchemy.orm import Session
from app.db.session import get_db
from app.schemas.creator import CreatorProfileResponse, CreatorProfileUpdate
from app.services.creator_service import (
    list_creators,
    get_creator_by_id,
    update_creator_profile,
    serialize_creator,
)
from app.api.deps import get_current_user, get_current_creator
from app.models.user import User
from app.models.creator import CreatorProfile

router = APIRouter(prefix="/creators", tags=["Creators"])

@router.get("", response_model=List[CreatorProfileResponse])
def get_creators(
    search: Optional[str] = Query(None, description="Search term for name, skills, bio, tools"),
    filter: Optional[str] = Query(None, alias="filter", description="Category or skill filter"),
    limit: int = Query(50, ge=1, le=100),
    offset: int = Query(0, ge=0),
    db: Session = Depends(get_db)
):
    """List creators with optional search and filtering."""
    creators = list_creators(db, search=search, filter_tag=filter, limit=limit, offset=offset)
    return [serialize_creator(c) for c in creators]

@router.get("/me", response_model=CreatorProfileResponse)
def get_my_creator_profile(
    creator: CreatorProfile = Depends(get_current_creator)
):
    """Retrieve creator profile of the currently logged-in user."""
    return serialize_creator(creator)

@router.put("/me", response_model=CreatorProfileResponse)
def update_my_creator_profile(
    profile_in: CreatorProfileUpdate,
    creator: CreatorProfile = Depends(get_current_creator),
    db: Session = Depends(get_db)
):
    """Update profile of the currently logged-in creator."""
    updated = update_creator_profile(db, creator, profile_in)
    return serialize_creator(updated)

@router.get("/{creator_id}", response_model=CreatorProfileResponse)
def get_creator_by_id_endpoint(creator_id: int, db: Session = Depends(get_db)):
    """Retrieve a creator's public profile and their portfolio projects."""
    creator = get_creator_by_id(db, creator_id)
    if not creator:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Creator with ID {creator_id} not found",
        )
    return serialize_creator(creator)
