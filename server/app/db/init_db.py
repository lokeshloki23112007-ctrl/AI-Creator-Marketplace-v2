import json
from sqlalchemy.orm import Session
from app.db.base import Base
from app.db.session import engine
from app.models.user import User
from app.models.creator import CreatorProfile
from app.models.portfolio import PortfolioProject
from app.models.brief import Brief
from app.core.security import hash_password

def init_db(db: Session) -> None:
    """Create all tables and seed initial realistic data if database is empty."""
    Base.metadata.create_all(bind=engine)

    # Check if data already exists
    existing_user = db.query(User).first()
    if existing_user:
        return

    default_password = hash_password("password123")

    # 1. Create Brand User
    brand_user = User(
        email="brand@example.com",
        hashed_password=default_password,
        full_name="XYZ Brand",
        role="brand",
        avatar_url="https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?auto=format&fit=crop&w=500&q=80",
        is_active=True,
    )
    db.add(brand_user)
    db.flush()

    # 2. Create Initial Briefs for Brand
    briefs_data = [
        {
            "campaign_name": "Summer Launch Campaign",
            "description": "We need a cinematic promotional video for our new product launch with a premium, modern feel.",
            "content_type": "Video",
            "style": "Cinematic",
            "platform": "Instagram",
            "format": "16:9",
            "commercial_use": "Yes",
            "budget": "$2,500 - $4,000",
            "deadline": "2026-11-15",
            "reference_url": "https://example.com/reference-board",
            "status": "Active",
        },
        {
            "campaign_name": "Spring Cosmetic Social Series",
            "description": "Series of 5 high-converting vertical video snippets showcasing skincare transformation.",
            "content_type": "Video",
            "style": "Minimal",
            "platform": "TikTok",
            "format": "9:16",
            "commercial_use": "Yes",
            "budget": "$1,800",
            "deadline": "2026-11-01",
            "reference_url": "https://example.com/moodboard",
            "status": "Active",
        },
        {
            "campaign_name": "AI Cyberpunk Sneaker Commercial",
            "description": "3D futuristic aesthetic ad highlighting lightweight materials and neon cityscape.",
            "content_type": "Animation",
            "style": "Luxury",
            "platform": "YouTube",
            "format": "16:9",
            "commercial_use": "Yes",
            "budget": "$3,200",
            "deadline": "2026-11-20",
            "reference_url": "https://example.com/sneaker-ref",
            "status": "Active",
        },
        {
            "campaign_name": "SaaS Platform Explainer Video",
            "description": "Clean, high-energy UI motion graphic showing AI workflow for creative agencies.",
            "content_type": "Video",
            "style": "Professional",
            "platform": "Website",
            "format": "16:9",
            "commercial_use": "Yes",
            "budget": "$2,000",
            "deadline": "2026-12-05",
            "reference_url": "https://example.com/saas-ref",
            "status": "Active",
        },
    ]

    for b in briefs_data:
        brief_obj = Brief(brand_id=brand_user.id, **b)
        db.add(brief_obj)

    # 3. Create Creators matching frontend `src/data/creators.js`
    creators_seed = [
        {
            "email": "arun@example.com",
            "name": "Arun Kumar",
            "role": "AI Video Creator",
            "bio": "AI content creator specializing in product advertisements and short-form videos.",
            "specialization": "Product Advertisement",
            "skills": ["AI Video", "Runway", "Kling", "Product Ads"],
            "tools": ["Runway", "Kling", "Premiere Pro", "After Effects"],
            "rating": 4.8,
            "reviews": 18,
            "projects": 14,
            "image": "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=500&q=80",
            "verified": True,
            "hourly_rate": "$85/hr",
            "portfolios": [
                {
                    "title": "AI Shoe Advertisement",
                    "content_type": "Video",
                    "tools": ["Runway", "Kling"],
                    "skills": ["AI Video", "Advertisement"],
                    "preview_url": "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80",
                    "description": "15-second product advertisement created for a sports shoe campaign.",
                },
                {
                    "title": "Food Product Ad",
                    "content_type": "Video",
                    "tools": ["Runway", "ElevenLabs"],
                    "skills": ["AI Video", "Social Ads"],
                    "preview_url": "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80",
                    "description": "Dynamic food commercial blending AI video generation and voice clone narration.",
                },
            ],
        },
        {
            "email": "priya@example.com",
            "name": "Priya Nair",
            "role": "AI Graphic Designer",
            "bio": "Visual artist utilizing cutting-edge generative AI to deliver iconic brand identities and concept art.",
            "specialization": "Branding & Visuals",
            "skills": ["AI Image", "Midjourney", "Adobe Firefly", "Branding"],
            "tools": ["Midjourney", "Adobe Firefly", "Photoshop", "Illustrator"],
            "rating": 4.7,
            "reviews": 14,
            "projects": 21,
            "image": "https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=500&q=80",
            "verified": True,
            "hourly_rate": "$70/hr",
            "portfolios": [
                {
                    "title": "AI Fashion Campaign",
                    "content_type": "Image",
                    "tools": ["Midjourney", "Adobe Firefly"],
                    "skills": ["AI Image", "Branding"],
                    "preview_url": "https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80",
                    "description": "High-fashion visual lookbook developed entirely using Midjourney and Photoshop retouching.",
                }
            ],
        },
        {
            "email": "rahul@example.com",
            "name": "Rahul Singh",
            "role": "AI Animation Creator",
            "bio": "Specializing in 3D AI animation, seamless character movement, and VFX commercials.",
            "specialization": "3D Animation & VFX",
            "skills": ["AI Animation", "Runway", "Blender", "3D"],
            "tools": ["Runway", "Blender", "Cinema 4D", "Stable Video"],
            "rating": 4.9,
            "reviews": 25,
            "projects": 20,
            "image": "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=500&q=80",
            "verified": True,
            "hourly_rate": "$95/hr",
            "portfolios": [
                {
                    "title": "Cyberpunk Hologram Commercial",
                    "content_type": "Animation",
                    "tools": ["Runway", "Blender"],
                    "skills": ["AI Animation", "3D"],
                    "preview_url": "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=900&q=80",
                    "description": "Sci-fi animation test with fluid camera motion and synthetic lighting.",
                }
            ],
        },
        {
            "email": "sneha@example.com",
            "name": "Sneha Iyer",
            "role": "AI Content Creator",
            "bio": "High-impact short-form viral video specialist with AI voiceover and audio synthesis.",
            "specialization": "Social Media Growth",
            "skills": ["AI Video", "Animation", "Social Media", "Ads"],
            "tools": ["Runway", "ElevenLabs", "CapCut", "Pika"],
            "rating": 4.6,
            "reviews": 11,
            "projects": 16,
            "image": "https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=500&q=80",
            "verified": True,
            "hourly_rate": "$60/hr",
            "portfolios": [
                {
                    "title": "Viral SaaS Launch Clip",
                    "content_type": "Video",
                    "tools": ["Runway", "ElevenLabs"],
                    "skills": ["AI Video", "Social Media"],
                    "preview_url": "https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=900&q=80",
                    "description": "Short-form promotional video generating 250K+ organic impressions.",
                }
            ],
        },
    ]

    for c in creators_seed:
        user = User(
            email=c["email"],
            hashed_password=default_password,
            full_name=c["name"],
            role="creator",
            avatar_url=c["image"],
            is_active=True,
        )
        db.add(user)
        db.flush()

        profile = CreatorProfile(
            user_id=user.id,
            role=c["role"],
            bio=c["bio"],
            specialization=c["specialization"],
            skills_raw=json.dumps(c["skills"]),
            tools_raw=json.dumps(c["tools"]),
            rating=c["rating"],
            reviews_count=c["reviews"],
            projects_count=c["projects"],
            avatar_url=c["image"],
            verified=c["verified"],
            hourly_rate=c["hourly_rate"],
            location="Remote",
        )
        db.add(profile)
        db.flush()

        for p in c["portfolios"]:
            proj = PortfolioProject(
                creator_id=profile.id,
                title=p["title"],
                content_type=p["content_type"],
                description=p["description"],
                preview_url=p["preview_url"],
                tools_raw=json.dumps(p["tools"]),
                skills_raw=json.dumps(p["skills"]),
            )
            db.add(proj)

    db.commit()
