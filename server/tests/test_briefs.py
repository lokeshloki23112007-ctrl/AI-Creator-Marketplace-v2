def test_list_briefs(client):
    res = client.get("/api/briefs")
    assert res.status_code == 200
    briefs = res.json()
    assert len(briefs) >= 4
    assert any(b["campaignName"] == "Summer Launch Campaign" for b in briefs)

def test_create_brief(client, brand_token):
    res = client.post(
        "/api/briefs",
        headers={"Authorization": f"Bearer {brand_token}"},
        json={
            "campaignName": "Autumn Luxury Fragrance",
            "description": "High-end cinematic perfume spot with surreal particle effects.",
            "contentType": "Video",
            "style": "Luxury",
            "platform": "Instagram",
            "format": "9:16",
            "commercialUse": "Yes",
            "budget": "$3,500",
            "deadline": "2026-11-30",
            "reference": "https://example.com/perfume-moodboard"
        }
    )
    assert res.status_code == 201
    brief = res.json()
    assert brief["campaignName"] == "Autumn Luxury Fragrance"
    assert brief["style"] == "Luxury"

def test_apply_to_brief(client, creator_token):
    # Apply to brief #1
    res = client.post(
        "/api/briefs/1/apply",
        headers={"Authorization": f"Bearer {creator_token}"},
        json={
            "pitch": "I have created over 20 similar product videos using Runway and Kling. Check my portfolio!",
            "proposedRate": "$2,200"
        }
    )
    assert res.status_code == 201
    app_data = res.json()
    assert app_data["brief_id"] == 1
    assert app_data["pitch"].startswith("I have created")

    # Verify that getting the brief now shows the application
    brief_res = client.get("/api/briefs/1")
    assert brief_res.status_code == 200
    assert brief_res.json()["applications_count"] >= 1

def test_creator_dashboard(client, creator_token):
    res = client.get(
        "/api/dashboard/creator",
        headers={"Authorization": f"Bearer {creator_token}"}
    )
    assert res.status_code == 200
    data = res.json()
    assert "profile_completion" in data
    assert "active_proposals" in data

def test_brand_dashboard(client, brand_token):
    res = client.get(
        "/api/dashboard/brand",
        headers={"Authorization": f"Bearer {brand_token}"}
    )
    assert res.status_code == 200
    data = res.json()
    assert "active_briefs_count" in data
    assert "recent_briefs" in data
