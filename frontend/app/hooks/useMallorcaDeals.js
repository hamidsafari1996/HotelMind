// hooks/useMallorcaDeals.js
import { useEffect, useState } from "react";

const API_URL = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api/hotels";

export default function useMallorcaDeals() {
  const [deals, setDeals] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch(API_URL)
      .then(res => {
        if (!res.ok) throw new Error(`API request failed with status ${res.status}`);
        return res.json();
      })
      .then(data => {
        const mallorcaHotels = data.filter(hotel => hotel.kategorie?.name === "Mallorca");
        const formattedData = mallorcaHotels.map(hotel => ({
          id: hotel.id,
          location: hotel.location,
          hotelName: hotel.title,
          duration: `${hotel.days} Tage`,
          person: hotel.person,
          mealPlan: hotel.info,
          rating: hotel.rating,
          scoreText: "Sehr gut",
          originalPrice: parseInt(hotel.price) + 200,
          discount: 20,
          stars: hotel.stars,
          finalPrice: hotel.price,
          image: hotel.image
            ? `${process.env.NEXT_PUBLIC_UPLOADS_URL || "http://localhost:8000/uploads/images/"}${hotel.image}`
            : "/placeholder.svg"
        }));
        setDeals(formattedData);
        setLoading(false);
      })
      .catch(err => {
        setError(err.message);
        setLoading(false);
      });
  }, []);

  return { deals, loading, error };
}