# API Authentication

## Overview

The API uses Laravel Sanctum for token-based authentication. All protected routes require a valid Bearer token in the `Authorization` header.

## Authentication Endpoints

### Register

Create a new user account.

```http
POST /api/auth/register
```

#### Request Body
```json
{
    "name": "string",
    "email": "string",
    "password": "string",
    "password_confirmation": "string",
    "phone": "string (optional)",
    "device_name": "string (optional)"
}
```

#### Response
```json
{
    "access_token": "string",
    "token_type": "Bearer",
    "user": {
        "id": "integer",
        "name": "string",
        "email": "string",
        "phone": "string|null",
        "avatar": "string|null",
        "email_verified_at": "timestamp|null",
        "created_at": "timestamp",
        "updated_at": "timestamp"
    }
}
```

### Login

Login with existing credentials.

```http
POST /api/auth/login
```

#### Request Body
```json
{
    "email": "string",
    "password": "string",
    "device_name": "string (optional)"
}
```

#### Response
```json
{
    "access_token": "string",
    "token_type": "Bearer",
    "user": {
        "id": "integer",
        "name": "string",
        "email": "string",
        "phone": "string|null",
        "avatar": "string|null",
        "email_verified_at": "timestamp|null",
        "created_at": "timestamp",
        "updated_at": "timestamp"
    }
}
```

### Get User Profile

Get the authenticated user's profile.

```http
GET /api/auth/user
```

#### Headers
```
Authorization: Bearer {token}
```

#### Response
```json
{
    "user": {
        "id": "integer",
        "name": "string",
        "email": "string",
        "phone": "string|null",
        "avatar": "string|null",
        "email_verified_at": "timestamp|null",
        "created_at": "timestamp",
        "updated_at": "timestamp"
    }
}
```

### Update Profile

Update the authenticated user's profile.

```http
PUT /api/auth/profile
```

#### Headers
```
Authorization: Bearer {token}
```

#### Request Body
```json
{
    "name": "string",
    "email": "string",
    "phone": "string (optional)",
    "avatar": "file (optional)"
}
```

#### Response
```json
{
    "message": "Profile updated successfully",
    "user": {
        "id": "integer",
        "name": "string",
        "email": "string",
        "phone": "string|null",
        "avatar": "string|null",
        "email_verified_at": "timestamp|null",
        "created_at": "timestamp",
        "updated_at": "timestamp"
    }
}
```

### Change Password

Change the authenticated user's password.

```http
PUT /api/auth/password
```

#### Headers
```
Authorization: Bearer {token}
```

#### Request Body
```json
{
    "current_password": "string",
    "password": "string",
    "password_confirmation": "string"
}
```

#### Response
```json
{
    "message": "Password changed successfully"
}
```

### Forgot Password

Send a password reset link to user's email.

```http
POST /api/auth/forgot-password
```

#### Request Body
```json
{
    "email": "string"
}
```

#### Response
```json
{
    "message": "Password reset link sent successfully"
}
```

### Reset Password

Reset password using the token from email.

```http
POST /api/auth/reset-password
```

#### Request Body
```json
{
    "token": "string",
    "email": "string",
    "password": "string",
    "password_confirmation": "string"
}
```

#### Response
```json
{
    "message": "Password reset successfully"
}
```

### Logout

Logout and invalidate the current token.

```http
POST /api/auth/logout
```

#### Headers
```
Authorization: Bearer {token}
```

#### Response
```json
{
    "message": "Successfully logged out"
}
```

## Error Responses

All endpoints may return the following error responses:

### Validation Error (422)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field": [
            "Error message"
        ]
    }
}
```

### Authentication Error (401)
```json
{
    "message": "Unauthenticated."
}
```

### Authorization Error (403)
```json
{
    "message": "This action is unauthorized."
}
```