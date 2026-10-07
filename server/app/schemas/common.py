from pydantic import BaseModel
from typing import Optional, Any

class MessageResponse(BaseModel):
    message: str
    detail: Optional[str] = None

class PaginatedResponse(BaseModel):
    total: int
    items: list[Any]
