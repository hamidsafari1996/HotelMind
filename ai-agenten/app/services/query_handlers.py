import re
import pandas as pd
import logging

logger = logging.getLogger("hotelai")

def handle_rating_query(query_text, df: pd.DataFrame):
    """Handle rating-based queries with comprehensive pattern matching"""
    # Patterns for rating queries
    patterns = {
        'better_than': r'(?:besser als|better than|higher than|höher als)\s+(\d+[\.,]?\d*)',
        'at_least': r'(?:mindestens|at least|minimum|min)\s+(\d+[\.,]?\d*)',
        'rating_equal': r'(?:mit bewertung|mit rating|with rating|rated)\s+(\d+[\.,]?\d*)',
        'top_rated': r'(?:bestbewertete|top-rated|highest rated|best)'
    }
    
    for pattern_type, regex in patterns.items():
        match = re.search(regex, query_text.lower())
        if match:
            if pattern_type in ['better_than', 'at_least']:
                rating_str = match.group(1).replace(',', '.')
                rating_limit = float(rating_str)
                filtered_df = df[df['Rating'] >= rating_limit]
                return filtered_df.to_dict('records') if not filtered_df.empty else None
            
            elif pattern_type == 'rating_equal':
                rating_str = match.group(1).replace(',', '.')
                rating_value = float(rating_str)
                # Allow some tolerance for floating point comparison
                filtered_df = df[(df['Rating'] >= rating_value - 0.05) & (df['Rating'] <= rating_value + 0.05)]
                return filtered_df.to_dict('records') if not filtered_df.empty else None
            
            elif pattern_type == 'top_rated':
                # Get top 3 rated hotels
                filtered_df = df.sort_values(by='Rating', ascending=False).head(3)
                return filtered_df.to_dict('records') if not filtered_df.empty else None
    
    return None

def handle_price_query(query_text, df: pd.DataFrame):
    """Handle price-based queries with comprehensive pattern matching"""
    # Various patterns for price queries
    patterns = {
        'around': r'(?:around|about|approximately|circa|near|um|ungefähr)\s+(\d+)',
        'less_than': r'(?:unter|below|under|less than|cheaper than|günstiger als|billiger als|maximal)\s+(\d+)',
        'more_than': r'(?:über|above|over|more than|at least|mindestens|teurer als)\s+(\d+)',
        'between': r'(?:zwischen|between)\s+(\d+)\s+(?:und|and|bis|-)\s+(\d+)',
        'exactly': r'(?:genau|exactly|exactly at|precisely|equal to)\s+(\d+)'
    }
    
    # Check for price range patterns
    for pattern_type, regex in patterns.items():
        match = re.search(regex, query_text.lower())
        if match:
            if pattern_type == 'around':
                target_price = int(match.group(1))
                # Define a price range of ±15% around the target price
                price_range = target_price * 0.15
                # Create a copy of the filtered DataFrame to avoid the warning
                filtered_df = df[(df['Price'] >= target_price - price_range) & 
                               (df['Price'] <= target_price + price_range)].copy()
                # Sort by proximity to target price
                if not filtered_df.empty:
                    filtered_df.loc[:, 'price_diff'] = abs(filtered_df['Price'] - target_price)
                    filtered_df = filtered_df.sort_values('price_diff')
                    filtered_df = filtered_df.drop('price_diff', axis=1)
                return filtered_df.to_dict('records') if not filtered_df.empty else None
                
            elif pattern_type == 'less_than':
                price_limit = int(match.group(1))
                filtered_df = df[df['Price'] < price_limit]
                # Sort by price ascending
                filtered_df = filtered_df.sort_values('Price')
                return filtered_df.to_dict('records') if not filtered_df.empty else None
                
            elif pattern_type == 'more_than':
                price_limit = int(match.group(1))
                filtered_df = df[df['Price'] > price_limit]
                # Sort by price ascending
                filtered_df = filtered_df.sort_values('Price')
                return filtered_df.to_dict('records') if not filtered_df.empty else None
                
            elif pattern_type == 'between':
                lower_limit = int(match.group(1))
                upper_limit = int(match.group(2))
                filtered_df = df[(df['Price'] >= lower_limit) & (df['Price'] <= upper_limit)]
                # Sort by price ascending
                filtered_df = filtered_df.sort_values('Price')
                return filtered_df.to_dict('records') if not filtered_df.empty else None
                
            elif pattern_type == 'exactly':
                price_value = int(match.group(1))
                # Allow small tolerance for exact matches
                tolerance = 5
                filtered_df = df[(df['Price'] >= price_value - tolerance) & 
                               (df['Price'] <= price_value + tolerance)]
                return filtered_df.to_dict('records') if not filtered_df.empty else None
    
    # If no direct pattern match, try to extract any number mentioned
    price_match = re.search(r'(\d+)(?:\s*(?:euro|eur|€))?', query_text.lower())
    if price_match:
        target_price = int(price_match.group(1))
        # Define a price range of ±15% around the target price
        price_range = target_price * 0.15
        # Create a copy of the filtered DataFrame to avoid the warning
        filtered_df = df[(df['Price'] >= target_price - price_range) & 
                       (df['Price'] <= target_price + price_range)].copy()
        # Sort by proximity to target price
        if not filtered_df.empty:
            filtered_df.loc[:, 'price_diff'] = abs(filtered_df['Price'] - target_price)
            filtered_df = filtered_df.sort_values('price_diff')
            filtered_df = filtered_df.drop('price_diff', axis=1)
        return filtered_df.to_dict('records') if not filtered_df.empty else None
    
    return None