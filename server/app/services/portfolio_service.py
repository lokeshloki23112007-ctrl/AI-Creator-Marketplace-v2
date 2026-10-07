from typing import List, Optional
from sqlalchemy.orm import Session
from app.models.portfolio import PortfolioProject
from app.models.creator import CreatorProfile
from app.schemas.portfolio import PortfolioProjectCreate, PortfolioProjectUpdate

def get_portfolio_by_id(db: Session, project_id: int) -> Optional[PortfolioProject]:
    return db.query(PortfolioProject).filter(PortfolioProject.id == project_id).first()

def list_portfolios(
    db: Session,
    creator_id: Optional[int] = None,
    content_type: Optional[str] = None,
    limit: int = 100,
    offset: int = 0
) -> List[PortfolioProject]:
    query = db.query(PortfolioProject)
    if creator_id is not None:
        query = query.filter(PortfolioProject.creator_id == creator_id)
    if content_type and content_type.lower() != "all":
        query = query.filter(PortfolioProject.content_type.ilike(f"%{content_type.strip()}%"))
    return query.order_by(PortfolioProject.created_at.desc()).offset(offset).limit(limit).all()

def create_portfolio(
    db: Session,
    creator_id: int,
    project_in: PortfolioProjectCreate
) -> PortfolioProject:
    project = PortfolioProject(
        creator_id=creator_id,
        title=project_in.title.strip(),
        content_type=project_in.content_type.strip(),
        description=project_in.description.strip() if project_in.description else "",
        preview_url=project_in.preview_url.strip(),
        external_link=project_in.external_link.strip() if project_in.external_link else None,
    )
    project.tools = project_in.tools
    project.skills = project_in.skills

    db.add(project)

    # Increment projects_count on creator profile
    creator = db.query(CreatorProfile).filter(CreatorProfile.id == creator_id).first()
    if creator:
        creator.projects_count += 1

    db.commit()
    db.refresh(project)
    return project

def update_portfolio(
    db: Session,
    project: PortfolioProject,
    project_in: PortfolioProjectUpdate
) -> PortfolioProject:
    if project_in.title is not None:
        project.title = project_in.title.strip()
    if project_in.content_type is not None:
        project.content_type = project_in.content_type.strip()
    if project_in.description is not None:
        project.description = project_in.description.strip()
    if project_in.preview_url is not None:
        project.preview_url = project_in.preview_url.strip()
    if project_in.external_link is not None:
        project.external_link = project_in.external_link.strip()
    if project_in.tools is not None:
        project.tools = project_in.tools
    if project_in.skills is not None:
        project.skills = project_in.skills

    db.commit()
    db.refresh(project)
    return project

def delete_portfolio(db: Session, project: PortfolioProject) -> None:
    creator = db.query(CreatorProfile).filter(CreatorProfile.id == project.creator_id).first()
    if creator and creator.projects_count > 0:
        creator.projects_count -= 1
    db.delete(project)
    db.commit()

def serialize_portfolio(project: PortfolioProject) -> dict:
    return {
        "id": project.id,
        "creator_id": project.creator_id,
        "title": project.title,
        "contentType": project.content_type,
        "content_type": project.content_type,
        "description": project.description,
        "tools": project.tools,
        "skills": project.skills,
        "preview": project.preview_url,
        "preview_url": project.preview_url,
        "external_link": project.external_link,
        "created_at": project.created_at,
    }
