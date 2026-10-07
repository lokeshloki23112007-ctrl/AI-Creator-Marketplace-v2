from datetime import datetime
from typing import Optional
from pydantic import BaseModel, EmailStr, Field, ConfigDict

class UserBase(BaseModel):
    email: EmailStr
    full_name: str = Field(alias="fullName")
    role: str = "creator"  # "creator" or "brand"
    avatar_url: Optional[str] = None

    model_config = ConfigDict(populate_by_name=True, from_attributes=True)

class UserCreate(BaseModel):
    email: EmailStr
    password: str
    full_name: str = Field(alias="fullName")
    role: Optional[str] = "creator"
    confirm_password: Optional[str] = Field(default=None, alias="confirmPassword")

    model_config = ConfigDict(populate_by_name=True)

class UserLogin(BaseModel):
    email: EmailStr
    password: str

class UserUpdate(BaseModel):
    full_name: Optional[str] = Field(default=None, alias="fullName")
    email: Optional[EmailStr] = None
    avatar_url: Optional[str] = None

    model_config = ConfigDict(populate_by_name=True, from_attributes=True)

class UserResponse(BaseModel):
    id: int
    email: EmailStr
    full_name: str
    role: str
    avatar_url: Optional[str] = None
    is_active: bool
    created_at: datetime

    model_config = ConfigDict(from_attributes=True, populate_by_name=True)

class Token(BaseModel):
    access_token: str
    token_type: str = "bearer"
    user: UserResponse

class TokenPayload(BaseModel):
    sub: Optional[str] = None
    user_id: Optional[int] = None
    role: Optional[str] = None
