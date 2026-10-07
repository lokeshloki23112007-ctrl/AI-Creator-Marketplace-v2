def test_root_endpoint(client):
    res = client.get("/")
    assert res.status_code == 200
    data = res.json()
    assert data["status"] == "online"
    assert "SQLite" in data["message"]

def test_health_check(client):
    res = client.get("/api/health")
    assert res.status_code == 200
    data = res.json()
    assert data["status"] == "healthy"
    assert data["connected"] is True

def test_signup_success(client):
    res = client.post(
        "/api/auth/signup",
        json={
            "fullName": "Test User",
            "email": "testuser@example.com",
            "password": "secretpassword",
            "confirmPassword": "secretpassword",
            "role": "creator"
        }
    )
    assert res.status_code == 201
    data = res.json()
    assert "access_token" in data
    assert data["user"]["email"] == "testuser@example.com"
    assert data["user"]["full_name"] == "Test User"

def test_signup_duplicate_email(client):
    res = client.post(
        "/api/auth/signup",
        json={
            "fullName": "Duplicate User",
            "email": "arun@example.com",
            "password": "secretpassword",
        }
    )
    assert res.status_code == 400
    assert "already exists" in res.json()["detail"]

def test_login_success(client):
    res = client.post(
        "/api/auth/login",
        json={"email": "arun@example.com", "password": "password123"}
    )
    assert res.status_code == 200
    data = res.json()
    assert "access_token" in data
    assert data["user"]["email"] == "arun@example.com"

def test_login_invalid_password(client):
    res = client.post(
        "/api/auth/login",
        json={"email": "arun@example.com", "password": "wrongpassword"}
    )
    assert res.status_code == 401

def test_get_me_authenticated(client, creator_token):
    res = client.get(
        "/api/auth/me",
        headers={"Authorization": f"Bearer {creator_token}"}
    )
    assert res.status_code == 200
    data = res.json()
    assert data["email"] == "arun@example.com"

def test_get_me_unauthenticated(client):
    res = client.get("/api/auth/me")
    assert res.status_code == 401
