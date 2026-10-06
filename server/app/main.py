from fastapi import FastAPI

app = FastAPI(
    title="AI Creator Marketplace API",
    description="Backend API for AI Creator Marketplace",
    version="1.0.0"
)

@app.get("/")
def root():
    return {
        "message": "AI Creator Marketplace Backend is running!"
    }