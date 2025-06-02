import warnings
import logging
import os
import pandas as pd
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import JSONResponse
from contextlib import asynccontextmanager

from app.utils.ollama import wait_for_ollama
from app.models.schemas import UserQuery
from app.services.query_handlers import handle_price_query, handle_rating_query

# Suppress specific warnings
warnings.filterwarnings("ignore", category=DeprecationWarning, module="pydantic")
warnings.filterwarnings("ignore", category=UserWarning, module="pydantic")

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    datefmt='%Y-%m-%d %H:%M:%S'
)

logger = logging.getLogger("hotelai")

# Global variables for resources
df = None

@asynccontextmanager
async def lifespan(app: FastAPI):
    # Startup: Initialize resources
    global df
    
    try:
        logger.info("Starting application...")
        
        # Wait for Ollama to be ready
        ollama_url = os.getenv("OLLAMA_BASE_URL", "http://ollama:11434")
        if not wait_for_ollama(ollama_url):
            logger.warning("Ollama is not accessible, but continuing without it")
        
        # Load hotel data
        logger.info("Loading hotel data...")
        df = pd.read_csv("data/hotels_Alanya.csv")
        # Ensure Rating is treated as a numeric value
        df['Rating'] = pd.to_numeric(df['Rating'], errors='coerce')
        # Ensure id is treated as string for consistency in JSON responses
        df['id'] = df['id'].astype(str)
        logger.info(f"Loaded {len(df)} hotels from CSV file")
        
        logger.info("Application started successfully")
        
    except Exception as e:
        logger.error(f"Error during startup: {e}", exc_info=True)
        # Re-raise to prevent app from starting if critical resources fail
        raise

    yield
    
    # Shutdown: Clean up resources
    logger.info("Shutting down application...")

app = FastAPI(lifespan=lifespan)

# Create exception handler
@app.exception_handler(Exception)
async def generic_exception_handler(request, exc):
    logger.error(f"Unhandled exception: {exc}", exc_info=True)
    return JSONResponse(
        status_code=500,
        content={"error": "Internal Server Error", "message": str(exc)},
    )

# Update CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://localhost:3000"],
    allow_credentials=True,
    allow_methods=["GET", "POST"],
    allow_headers=["*"],
)

@app.get("/")
def root():
    return {"message": "Sofia ist bereit! 😊"}

@app.get("/health")
def health_check():
    return {
        "status": "healthy",
        "ollama_url": os.getenv("OLLAMA_BASE_URL", "http://ollama:11434"),
        "hotels_loaded": len(df) if df is not None else 0
    }

# Route to handle user input
@app.post("/hotel-search")
def hotel_search(user_query: UserQuery):
    try:
        query = user_query.query
        logger.info(f"Received query: {query}")

        # Simple introduction detection
        if "who are you" in query.lower() or "wer bist du" in query.lower():
            return {
                "response": "Ich bin Sofia, Ihre intelligente Hotelassistentin. Ich kann Ihnen helfen, Hotels basierend auf Ihren Vorlieben zu finden."
            }
        
        # Try to handle price query directly
        try:
            price_results = handle_price_query(query, df)
            if price_results:
                logger.info(f"Found {len(price_results)} hotels via direct price handling")
                return {"hotels": price_results}
        except Exception as e:
            logger.error(f"Error in price query handling: {e}")
        
        # Try to handle rating query directly
        try:
            rating_results = handle_rating_query(query, df)
            if rating_results:
                logger.info(f"Found {len(rating_results)} hotels via direct rating handling")
                return {"hotels": rating_results}
        except Exception as e:
            logger.error(f"Error in rating query handling: {e}")
        
        # If no specific pattern matches, return all hotels
        try:
            all_hotels = df.to_dict('records')
            logger.info(f"No specific pattern matched, returning all {len(all_hotels)} hotels")
            return {"hotels": all_hotels}
        except Exception as e:
            logger.error(f"Error returning all hotels: {e}")
            return {
                "error": "Failed to retrieve hotels",
                "message": str(e)
            }
            
    except Exception as e:
        logger.error(f"Unhandled exception in hotel_search: {e}", exc_info=True)
        return {
            "error": "An unexpected error occurred",
            "message": str(e)
        }