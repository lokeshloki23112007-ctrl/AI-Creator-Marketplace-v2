from typing import List, Optional
from fastapi import APIRouter, Depends, HTTPException, Query, status
from sqlalchemy.orm import Session
from app.db.session import get_db
from app.schemas.brief import (
    BriefCreate,
    BriefUpdate,
    BriefResponse,
    ApplicationCreate,
    ApplicationResponse,
)
from app.schemas.common import MessageResponse
from app.services.brief_service import (
    list_briefs,
    get_brief_by_id,
    create_brief,
    update_brief,
    delete_brief,
    apply_to_brief,
    serialize_brief,
)
from app.api.deps import get_current_user, get_current_creator
from app.models.user import User
from app.models.creator import CreatorProfile

router = APIRouter(prefix="/briefs", tags=["Briefs"])

@router.get("", response_model=List[BriefResponse])
def get_briefs(
    brand_id: Optional[int] = Query(None, description="Filter by brand user ID"),
    status: Optional[str] = Query(None, description="Filter by status (Active, Completed, etc.)"),
    contentType: Optional[str] = Query(None, alias="contentType", description="Filter by content type"),
    limit: int = Query(50, ge=1, le=100),
    offset: int = Query(0, ge=0),
    db: Session = Depends(get_db)
):
    """List project campaign briefs."""
    briefs = list_briefs(db, brand_id=brand_id, status=status, content_type=contentType, limit=limit, offset=offset)
    return [serialize_brief(b) for b in briefs]

@router.get("/{brief_id}", response_model=BriefResponse)
def get_single_brief(brief_id: int, db: Session = Depends(get_db)):
    """Retrieve details for a specific brief, including submitted proposals."""
    brief = get_brief_by_id(db, brief_id)
    if not brief:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Brief with ID {brief_id} not found",
        )
    return serialize_brief(brief)

@router.post("", response_model=BriefResponse, status_code=status.HTTP_201_CREATED)
def create_new_brief(
    brief_in: BriefCreate,
    current_user: User = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    """Create a new project brief campaign."""
    brief = create_brief(db, brand_id=current_user.id, brief_in=brief_in)
    return serialize_brief(brief)

@router.put("/{brief_id}", response_model=BriefResponse)
def update_brief_endpoint(
    brief_id: int,
    brief_in: BriefUpdate,
    current_user: User = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    """Update an existing campaign brief."""
    brief = get_brief_by_id(db, brief_id)
    if not brief:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Brief with ID {brief_id} not found",
        )
    if brief.brand_id != current_user.id:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="You do not have permission to modify this brief",
        )
    
    updated = update_brief(db, brief, brief_in)
    return serialize_brief(updated)

@router.delete("/{brief_id}", response_model=MessageResponse)
def delete_brief_endpoint(
    brief_id: int,
    current_user: User = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    """Delete a campaign brief."""
    brief = get_brief_by_id(db, brief_id)
    if not brief:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Brief with ID {brief_id} not found",
        )
    if brief.brand_id != current_user.id:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="You do not have permission to delete this brief",
        )
    
    delete_brief(db, brief)
    return {"message": "Brief deleted successfully"}

@router.post("/{brief_id}/apply", response_model=ApplicationResponse, status_code=status.HTTP_201_CREATED)
def apply_to_brief_endpoint(
    brief_id: int,
    app_in: ApplicationCreate,
    creator: CreatorProfile = Depends(get_current_creator),
    db: Session = Depends(get_db)
):
    """Apply to a brief with a pitch and proposed rate."""
    brief = get_brief_by_id(db, brief_id)
    if not brief:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Brief with ID {brief_id} not found",
        )
    
    application = apply_to_brief(db, brief_id=brief_id, creator_id=creator.id, app_in=app_in)
    return {
        "id": application.id,
        "brief_id": application.brief_id,
        "creator_id": application.creator_id,
        "creator_name": creator.user.full_name if creator.user else "Creator",
        "creator_avatar": creator.avatar_url,
        "pitch": application.pitch,
        "proposedRate": application.proposed_rate,
        "proposed_rate": application.proposed_rate,
        "status": application.status,
        "created_at": application.created_at,
    }
