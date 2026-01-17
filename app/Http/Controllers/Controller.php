<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Senior Dental Backend API",
    description: "API documentation for the Senior Dental Backend application",
    contact: new OA\Contact(
        name: "Senior Dental Team",
        email: "support@seniordental.com"
    )
)]
#[OA\Server(
    url: "http://46.224.222.138/api",
    description: "Production Server"
)]
#[OA\Server(
    url: "http://localhost:8000/api",
    description: "Local Development Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
#[OA\Tag(name: "Health", description: "Health check endpoints")]
#[OA\Tag(name: "Auth", description: "Authentication endpoints")]
#[OA\Tag(name: "Customer", description: "Customer endpoints")]
#[OA\Tag(name: "Doctor", description: "Doctor endpoints")]
#[OA\Tag(name: "Chat", description: "AI Chatbot endpoints")]
#[OA\Tag(name: "AI", description: "AI Services endpoints")]
abstract class Controller
{
    //
}
