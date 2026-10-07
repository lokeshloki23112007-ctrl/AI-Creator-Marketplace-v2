from fastapi import APIRouter
from app.api.routes import auth, users, creators, portfolios, briefs, dashboard

api_router = APIRouter()

api_router.include_router(auth.router)
api_router.include_router(users.router)
api_router.include_router(creators.router)
api_router.include_router(portfolios.router)
api_router.include_router(briefs.router)
api_router.include_router(dashboard.router)
