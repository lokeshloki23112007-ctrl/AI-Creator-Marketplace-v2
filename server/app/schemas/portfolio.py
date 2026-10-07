from datetime import datetime
from typing import List, Optional, Union
from pydantic import BaseModel, Field, ConfigDict, field_validator

class PortfolioProjectBase(BaseModel):
    title: str
    content_type: str = Field(default="Video", alias="contentType")
    description: Optional[str] = None
    tools: List[str] = []
    skills: List[str] = []
    preview_url: str = Field(alias="preview")
    external_link: Optional[str] = None

    model_config = ConfigDict(populate_by_name=True, from_attributes=True)

    @field_validator("tools", "skills", mode="before")
    @classmethod
    def parse_str_list(cls, v: Union[List[str], str, None]) -> List[str]:
        if v is None:
            return []
        if isinstance(v, list):
            return [str(item).strip() for item in v if str(item).strip()]
        if isinstance(v, str):
            return [s.strip() for s in v.split(",") if s.strip()]
        return []

class PortfolioProjectCreate(PortfolioProjectBase):
    pass

class PortfolioProjectUpdate(BaseModel):
    title: Optional[str] = None
    content_type: Optional[str] = Field(default=None, alias="contentType")
    description: Optional[str] = None
    tools: Optional[Union[List[str], str]] = None
    skills: Optional[Union[List[str], str]] = None
    preview_url: Optional[str] = Field(default=None, alias="preview")
    external_link: Optional[str] = None

    model_config = ConfigDict(populate_by_name=True, from_attributes=True)

    @field_validator("tools", "skills", mode="before")
    @classmethod
    def parse_str_list_opt(cls, v: Union[List[str], str, None]) -> Optional[List[str]]:
        if v is None:
            return None
        if isinstance(v, list):
            return [str(item).strip() for item in v if str(item).strip()]
        if isinstance(v, str):
            return [s.strip() for s in v.split(",") if s.strip()]
        return []

class PortfolioProjectResponse(BaseModel):
    id: int
    creator_id: int
    title: str
    content_type: str = Field(alias="contentType")
    description: Optional[str] = None
    tools: List[str] = []
    skills: List[str] = []
    preview_url: str = Field(alias="preview")
    external_link: Optional[str] = None
    created_at: datetime

    model_config = ConfigDict(populate_by_name=True, from_attributes=True)
