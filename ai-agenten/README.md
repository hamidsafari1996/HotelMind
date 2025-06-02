# Hotel AI Assistant - Sofia

Sofia is an intelligent hotel assistant that helps users find hotels based on their preferences using natural language queries.

## Project Structure

```
hotel-ai-project/
│
├── app/
│   ├── __init__.py
│   ├── main.py                 # Main FastAPI application
│   ├── services/
│   │   ├── __init__.py
│   │   └── query_handlers.py   # Price and rating query handlers
│   ├── models/
│   │   ├── __init__.py
│   │   └── schemas.py          # Pydantic models
│   └── utils/
│       ├── __init__.py
│       └── ollama.py           # Ollama utility functions
│
├── data/
│   └── hotels_Alanya.csv      # Hotel dataset
│
├── Dockerfile                  # Docker configuration
├── requirements.txt            # Python dependencies
├── .gitignore                 # Git ignore rules
├── README.md                  # This file
└── start.sh                   # Startup script
```

## Features

- **Natural Language Processing**: Understands queries in German and English
- **Price Filtering**: Find hotels by price range, exact price, or relative price queries
- **Rating Filtering**: Search hotels by rating criteria
- **Smart Fallbacks**: Returns all hotels when no specific criteria match
- **RESTful API**: Clean FastAPI endpoints with proper error handling
- **Docker Support**: Containerized application with Ollama integration

## API Endpoints

### GET `/`
Returns a welcome message from Sofia.

### GET `/health`
Health check endpoint showing application status and loaded hotel count.

### POST `/hotel-search`
Main search endpoint that accepts natural language queries.

**Request Body:**
```json
{
  "query": "Hotels under 100 euros"
}
```

**Response:**
```json
{
  "hotels": [
    {
      "id": "1",
      "name": "Hotel Name",
      "price": 85,
      "rating": 4.2,
      ...
    }
  ]
}
```

## Query Examples

### Price Queries
- "Hotels around 100 euros"
- "Cheaper than 150"
- "Between 80 and 120"
- "Exactly 95 euros"

### Rating Queries
- "Hotels better than 4.0"
- "Top rated hotels"
- "Hotels with rating 4.5"
- "At least 3.5 rating"

### General Queries
- "Who are you?" / "Wer bist du?" - Introduction
- Any other query returns all available hotels

## Installation & Setup

### Using Docker (Recommended)

1. Build and run with Docker Compose:
```bash
docker-compose up --build
```

### Local Development

1. Install dependencies:
```bash
pip install -r requirements.txt
```

2. Run the application:
```bash
uvicorn app.main:app --reload --host 0.0.0.0 --port 8000
```

Or use the startup script:
```bash
chmod +x start.sh
./start.sh
```

## Environment Variables

- `OLLAMA_BASE_URL`: Ollama service URL (default: http://ollama:11434)

## Development

The application follows clean architecture principles:

- **app/main.py**: Application setup, middleware, and route definitions
- **app/services/**: Business logic for query processing
- **app/models/**: Data models and schemas
- **app/utils/**: Utility functions and external service integrations

## Logging

The application uses structured logging with timestamps. Logs include:
- Application startup/shutdown events
- Query processing information
- Error handling and debugging information

## Error Handling

- Comprehensive exception handling at all levels
- Graceful degradation when external services are unavailable
- Detailed error responses for debugging

## Contributing

1. Follow the existing code structure
2. Add proper logging for new features
3. Include error handling for all new functionality
4. Update this README for any new features or changes 