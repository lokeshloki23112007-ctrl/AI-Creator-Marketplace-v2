from datetime import datetime
from typing import List, Optional, Union
from pydantic import BaseModel, Field, ConfigDict, field_validator
from app.schemas.portfolio import PortfolioProjectResponse

class CreatorProfileBase(BaseModel):
    role: str = "AI Creator"
    bio: Optional[str] = None
    specialization: Optional[str] = None
    skills: List[str] = []
    tools: List[str] = []
    hourly_rate: Optional[str] = Field(default="$75/hr", alias="hourlyRate")
    location: Optional[str] = "Remote"
    avatar_url: Optional[str] = Field(default=None, alias="image")

    model_config = ConfigDict(populate_by_name=True, from_attributes=True)

    @field_validator("skills", "tools", mode="before")
    @classmethod
    def parse_str_list(cls, v: Union[List[str], str, None]) -> List[str]:
        if v is None:
            return []
        if isinstance(v, list):
            return [str(item).strip() for item in v if str(item).strip()]
        if isinstance(v, str):
            return [s.strip() for s in v.split(",") if s.strip()]
        return []

class CreatorProfileCreate(CreatorProfileBase):
    pass

class CreatorProfileUpdate(BaseModel):
    full_name: Optional[str] = Field(default=None, alias="fullName")
    role: Optional[str] = None
    bio: Optional[str] = None
    specialization: Optional[str] = None
    skills: Optional[Union[List[str], str]] = None
    tools: Optional[Union[List[str], str]] = None
    hourly_rate: Optional[str] = Field(default=None, alias="hourlyRate")
    location: Optional[str] = None
    avatar_url: Optional[str] = Field(default=None, alias="image")

    model_config = ConfigDict(populate_by_name=True, from_attributes=True)

    @field_validator("skills", "tools", mode="before")
    @classmethod
    def parse_str_list_opt(cls, v: Union[List[str], str, None]) -> Optional[List[str]]:
        if v is None:
            return None
        if isinstance(v, list):
            return [str(item).strip() for item in v if str(item).strip()]
        if isinstance(v, str):
            return [s.strip() for s in v.split(",") if s.strip()]
        return []

class CreatorProfileResponse(BaseModel):
    id: int
    user_id: int
    name: str
    role: str
    bio: Optional[str] = None
    specialization: Optional[str] = None
    skills: List[str] = []
    tools: List[str] = []
    rating: float
    reviews: int = Field(alias="reviews_count")
    projects: int = Field(alias="projects_count")
    image: Optional[str] = Field(alias="avatar_url")
    verified: bool
    hourly_rate: Optional[str] = Field(alias="hourlyRate")
    location: Optional[str] = None
    created_at: datetime
    portfolios: Optional[List[PortfolioProjectResponse]] = []

    model_config = ConfigDict(populate_by_name=True, from_attributes=True)
