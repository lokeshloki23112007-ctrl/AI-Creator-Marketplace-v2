def test_list_creators(client):
    res = client.get("/api/creators")
    assert res.status_code == 200
    creators = res.json()
    assert len(creators) >= 4
    names = [c["name"] for c in creators]
    assert "Arun Kumar" in names
    assert "Priya Nair" in names

def test_search_creators(client):
    res = client.get("/api/creators?search=Blender")
    assert res.status_code == 200
    creators = res.json()
    assert len(creators) >= 1
    assert any(c["name"] == "Rahul Singh" for c in creators)

def test_filter_creators_by_skill(client):
    res = client.get("/api/creators?filter=Midjourney")
    assert res.status_code == 200
    creators = res.json()
    assert len(creators) >= 1
    assert any(c["name"] == "Priya Nair" for c in creators)

def test_get_creator_by_id(client):
    res = client.get("/api/creators/1")
    assert res.status_code == 200
    creator = res.json()
    assert creator["id"] == 1
    assert creator["name"] == "Arun Kumar"
    assert len(creator["portfolios"]) > 0

def test_get_nonexistent_creator(client):
    res = client.get("/api/creators/99999")
    assert res.status_code == 404

def test_update_creator_profile(client, creator_token):
    res = client.put(
        "/api/creators/me",
        headers={"Authorization": f"Bearer {creator_token}"},
        json={
            "fullName": "Arun Kumar Updated",
            "bio": "Updated bio for test suite",
            "specialization": "Hyper-realistic AI Video Ads",
            "skills": ["Runway Gen-3", "Luma Dream Machine", "Sound Design"]
        }
    )
    assert res.status_code == 200
    updated = res.json()
    assert updated["name"] == "Arun Kumar Updated"
    assert updated["bio"] == "Updated bio for test suite"
    assert "Runway Gen-3" in updated["skills"]
