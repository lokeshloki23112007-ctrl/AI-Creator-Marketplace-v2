from typing import Optional
from sqlalchemy.orm import Session
from app.models.user import User
from app.models.creator import CreatorProfile
from app.schemas.user import UserCreate, UserUpdate
from app.core.security import hash_password, verify_password

def get_user_by_email(db: Session, email: str) -> Optional[User]:
    return db.query(User).filter(User.email == email.lower().strip()).first()

def get_user_by_id(db: Session, user_id: int) -> Optional[User]:
    return db.query(User).filter(User.id == user_id).first()

def create_user(db: Session, user_in: UserCreate) -> User:
    hashed_pwd = hash_password(user_in.password)
    user = User(
        email=user_in.email.lower().strip(),
        hashed_password=hashed_pwd,
        full_name=user_in.full_name.strip(),
        role=user_in.role.lower() if user_in.role else "creator",
        is_active=True,
    )
    db.add(user)
    db.commit()
    db.refresh(user)

    # If the user registered as creator, automatically create a default profile
    if user.role == "creator":
        creator_profile = CreatorProfile(
            user_id=user.id,
            role="AI Creator",
            bio=f"AI creator specializing in digital content and generative media.",
            specialization="AI Media Production",
            skills_raw='["AI Video", "Runway", "Midjourney"]',
            tools_raw='["Runway", "Midjourney"]',
            rating=5.0,
            reviews_count=0,
            projects_count=0,
            avatar_url=None,
            verified=False,
            hourly_rate="$65/hr",
        )
        db.add(creator_profile)
        db.commit()

    return user

def authenticate_user(db: Session, email: str, password: str) -> Optional[User]:
    user = get_user_by_email(db, email)
    if not user:
        return None
    if not verify_password(password, user.hashed_password):
        return None
    return user

def update_user(db: Session, user: User, user_in: UserUpdate) -> User:
    if user_in.full_name is not None:
        user.full_name = user_in.full_name
    if user_in.email is not None:
        user.email = user_in.email.lower().strip()
    if user_in.avatar_url is not None:
        user.avatar_url = user_in.avatar_url
    db.commit()
    db.refresh(user)
    return user
