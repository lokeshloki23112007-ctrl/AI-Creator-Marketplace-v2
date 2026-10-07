from typing import List, Optional
from fastapi import APIRouter, Depends, HTTPException, Query, status
from sqlalchemy.orm import Session
from app.db.session import get_db
from app.schemas.portfolio import (
    PortfolioProjectCreate,
    PortfolioProjectUpdate,
    PortfolioProjectResponse,
)
from app.schemas.common import MessageResponse
from app.services.portfolio_service import (
    list_portfolios,
    get_portfolio_by_id,
    create_portfolio,
    update_portfolio,
    delete_portfolio,
    serialize_portfolio,
)
from app.api.deps import get_current_creator
from app.models.creator import CreatorProfile

router = APIRouter(prefix="/portfolios", tags=["Portfolios"])

@router.get("", response_model=List[PortfolioProjectResponse])
def get_portfolios(
    creator_id: Optional[int] = Query(None, description="Filter by creator ID"),
    contentType: Optional[str] = Query(None, alias="contentType", description="Filter by media type (Video, Image, etc.)"),
    limit: int = Query(50, ge=1, le=100),
    offset: int = Query(0, ge=0),
    db: Session = Depends(get_db)
):
    """Retrieve portfolio projects with optional filtering."""
    projects = list_portfolios(db, creator_id=creator_id, content_type=contentType, limit=limit, offset=offset)
    return [serialize_portfolio(p) for p in projects]

@router.get("/{project_id}", response_model=PortfolioProjectResponse)
def get_single_portfolio(project_id: int, db: Session = Depends(get_db)):
    """Retrieve a single portfolio project by ID."""
    project = get_portfolio_by_id(db, project_id)
    if not project:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Portfolio project with ID {project_id} not found",
        )
    return serialize_portfolio(project)

@router.post("", response_model=PortfolioProjectResponse, status_code=status.HTTP_201_CREATED)
def add_portfolio_project(
    project_in: PortfolioProjectCreate,
    creator: CreatorProfile = Depends(get_current_creator),
    db: Session = Depends(get_db)
):
    """Add a new project to the current creator's portfolio."""
    project = create_portfolio(db, creator_id=creator.id, project_in=project_in)
    return serialize_portfolio(project)

@router.put("/{project_id}", response_model=PortfolioProjectResponse)
def update_portfolio_project(
    project_id: int,
    project_in: PortfolioProjectUpdate,
    creator: CreatorProfile = Depends(get_current_creator),
    db: Session = Depends(get_db)
):
    """Update a portfolio project belonging to the logged-in creator."""
    project = get_portfolio_by_id(db, project_id)
    if not project:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Portfolio project with ID {project_id} not found",
        )
    if project.creator_id != creator.id:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="You do not have permission to modify this portfolio project",
        )
    
    updated = update_portfolio(db, project, project_in)
    return serialize_portfolio(updated)

@router.delete("/{project_id}", response_model=MessageResponse)
def delete_portfolio_project(
    project_id: int,
    creator: CreatorProfile = Depends(get_current_creator),
    db: Session = Depends(get_db)
):
    """Delete a portfolio project belonging to the logged-in creator."""
    project = get_portfolio_by_id(db, project_id)
    if not project:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Portfolio project with ID {project_id} not found",
        )
    if project.creator_id != creator.id:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="You do not have permission to delete this portfolio project",
        )
    
    delete_portfolio(db, project)
    return {"message": "Portfolio project deleted successfully"}
