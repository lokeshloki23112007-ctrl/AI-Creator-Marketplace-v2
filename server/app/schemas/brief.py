from datetime import datetime
from typing import List, Optional
from pydantic import BaseModel, Field, ConfigDict

class ApplicationCreate(BaseModel):
    pitch: str
    proposed_rate: Optional[str] = Field(default=None, alias="proposedRate")

    model_config = ConfigDict(populate_by_name=True)

class ApplicationResponse(BaseModel):
    id: int
    brief_id: int
    creator_id: int
    creator_name: Optional[str] = None
    creator_avatar: Optional[str] = None
    pitch: str
    proposed_rate: Optional[str] = Field(default=None, alias="proposedRate")
    status: str
    created_at: datetime

    model_config = ConfigDict(populate_by_name=True, from_attributes=True)

class BriefBase(BaseModel):
    campaign_name: str = Field(alias="campaignName")
    description: str
    content_type: str = Field(default="Video", alias="contentType")
    style: str = "Cinematic"
    platform: str = "Instagram"
    format: str = "16:9"
    commercial_use: str = Field(default="Yes", alias="commercialUse")
    budget: Optional[str] = None
    deadline: Optional[str] = None
    reference_url: Optional[str] = Field(default=None, alias="reference")

    model_config = ConfigDict(populate_by_name=True, from_attributes=True)

class BriefCreate(BriefBase):
    pass

class BriefUpdate(BaseModel):
    campaign_name: Optional[str] = Field(default=None, alias="campaignName")
    description: Optional[str] = None
    content_type: Optional[str] = Field(default=None, alias="contentType")
    style: Optional[str] = None
    platform: Optional[str] = None
    format: Optional[str] = None
    commercial_use: Optional[str] = Field(default=None, alias="commercialUse")
    budget: Optional[str] = None
    deadline: Optional[str] = None
    reference_url: Optional[str] = Field(default=None, alias="reference")
    status: Optional[str] = None

    model_config = ConfigDict(populate_by_name=True, from_attributes=True)

class BriefResponse(BaseModel):
    id: int
    brand_id: int
    brand_name: Optional[str] = None
    campaign_name: str = Field(alias="campaignName")
    description: str
    content_type: str = Field(alias="contentType")
    style: str
    platform: str
    format: str
    commercial_use: str = Field(alias="commercialUse")
    budget: Optional[str] = None
    deadline: Optional[str] = None
    reference_url: Optional[str] = Field(default=None, alias="reference")
    status: str
    created_at: datetime
    applications_count: Optional[int] = 0
    applications: Optional[List[ApplicationResponse]] = []

    model_config = ConfigDict(populate_by_name=True, from_attributes=True)
