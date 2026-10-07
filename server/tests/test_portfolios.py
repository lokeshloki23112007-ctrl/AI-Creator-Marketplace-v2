def test_list_portfolios(client):
    res = client.get("/api/portfolios")
    assert res.status_code == 200
    items = res.json()
    assert len(items) >= 5

def test_add_portfolio_project(client, creator_token):
    res = client.post(
        "/api/portfolios",
        headers={"Authorization": f"Bearer {creator_token}"},
        json={
            "title": "Futuristic Automobile Commercial",
            "contentType": "Video",
            "description": "Commercial produced with Runway and Kling.",
            "tools": ["Runway", "Kling"],
            "skills": ["AI Video", "Automotive"],
            "preview": "https://example.com/automobile-preview.jpg"
        }
    )
    assert res.status_code == 201
    project = res.json()
    assert project["title"] == "Futuristic Automobile Commercial"
    assert project["contentType"] == "Video"
    assert "Runway" in project["tools"]

def test_update_portfolio_project(client, creator_token):
    # First get creator's project
    res = client.get("/api/portfolios?creator_id=1")
    projects = res.json()
    assert len(projects) > 0
    proj_id = projects[0]["id"]

    res = client.put(
        f"/api/portfolios/{proj_id}",
        headers={"Authorization": f"Bearer {creator_token}"},
        json={"title": "Updated Portfolio Project Title"}
    )
    assert res.status_code == 200
    assert res.json()["title"] == "Updated Portfolio Project Title"

def test_delete_portfolio_project(client, creator_token):
    # Add a project to delete
    create_res = client.post(
        "/api/portfolios",
        headers={"Authorization": f"Bearer {creator_token}"},
        json={
            "title": "Temporary Project",
            "contentType": "Image",
            "preview": "https://example.com/temp.jpg",
            "tools": [],
            "skills": []
        }
    )
    assert create_res.status_code == 201
    proj_id = create_res.json()["id"]

    del_res = client.delete(
        f"/api/portfolios/{proj_id}",
        headers={"Authorization": f"Bearer {creator_token}"}
    )
    assert del_res.status_code == 200

    # Ensure deleted
    get_res = client.get(f"/api/portfolios/{proj_id}")
    assert get_res.status_code == 404
