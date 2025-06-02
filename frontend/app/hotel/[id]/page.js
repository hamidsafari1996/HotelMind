'use client'

import { useEffect, useState } from 'react'
import { useParams } from 'next/navigation'
import { MapPin, Star} from 'lucide-react'
import Header from '../../components/Header'
import Footer from '../../components/Footer'
import Link from 'next/link'
import getRatingText from "../../utils/getRatingText"
import DetailedHotelPlaceholder from '../../components/UI/DetailedHotelPlaceholder'


export default function HotelPage() {
  const params = useParams()
  const [hotel, setHotel] = useState(null)
  const [loading, setLoading] = useState(true)
  const [activeTab, setActiveTab] = useState('overview')
  
  useEffect(() => {
    const fetchHotel = async () => {
      try {
        // First try to get the hotel from sessionStorage (only on client-side)
        let storedResults = null;
        if (typeof window !== 'undefined') {
          storedResults = sessionStorage.getItem('hotelResults');
        }
        
        if (storedResults) {
          const results = JSON.parse(storedResults)
          // Find hotel by clientId
          const foundHotel = Array.isArray(results) ? 
            results.find(h => h.clientId === params.id) : null
          
          if (foundHotel) {
            // If the hotel has a symfony_id, try to fetch more detailed data from API
            if (foundHotel.symfony_id && foundHotel._source === 'symfony') {
              try {
                const res = await fetch(`http://localhost:8000/api/hotels/${foundHotel.symfony_id}`);
                if (res.ok) {
                  const detailedData = await res.json();
                  setHotel({
                    ...foundHotel,
                    ...detailedData
                  });
                } else {
                  // If API call fails, use the stored hotel data
                  setHotel(foundHotel);
                }
              } catch (error) {
                console.error("Error fetching detailed hotel data:", error);
                setHotel(foundHotel);
              }
            } 
            // If it has a real ID but not marked as symfony source, try by real ID
            else if (foundHotel.id && !isNaN(Number(foundHotel.id))) {
              try {
                const res = await fetch(`http://localhost:8000/api/hotels/${foundHotel.id}`);
                if (res.ok) {
                  const detailedData = await res.json();
                  setHotel({
                    ...foundHotel,
                    ...detailedData,
                    symfony_id: detailedData.id,
                    _source: 'symfony'
                  });
                } else {
                  // If API call fails, use the stored hotel data
                  setHotel(foundHotel);
                }
              } catch (error) {
                console.error("Error fetching hotel by ID:", error);
                setHotel(foundHotel);
              }
            } else {
              // Use the hotel data we already have
              setHotel(foundHotel);
            }
          } else {
            // If not found by clientId, try to fetch directly by ID if it seems like a Symfony ID
            if (params.id && !isNaN(Number(params.id))) {
              try {
                const res = await fetch(`http://localhost:8000/api/hotels/${params.id}`);
                if (res.ok) {
                  const hotelData = await res.json();
                  setHotel({
                    ...hotelData,
                    symfony_id: hotelData.id,
                    clientId: params.id,
                    _source: 'symfony'
                  });
                } else {
                  // Fallback to a default hotel if API fails
                  setHotel(createDefaultHotel(params.id));
                }
              } catch (error) {
                console.error("Error fetching hotel by ID param:", error);
                setHotel(createDefaultHotel(params.id));
              }
            } else {
              // Fallback to a default hotel if not found and not a numeric ID
              setHotel(createDefaultHotel(params.id));
            }
          }
        } else {
          // Try direct API call if it looks like a Symfony ID
          if (params.id && !isNaN(Number(params.id))) {
            try {
              const res = await fetch(`http://localhost:8000/api/hotels/${params.id}`);
              if (res.ok) {
                const hotelData = await res.json();
                setHotel({
                  ...hotelData,
                  symfony_id: hotelData.id,
                  clientId: params.id,
                  _source: 'symfony'
                });
              } else {
                // Fallback to a default hotel if API fails
                setHotel(createDefaultHotel(params.id));
              }
            } catch (error) {
              console.error("Error fetching hotel when no stored results:", error);
              setHotel(createDefaultHotel(params.id));
            }
          } else {
            // No stored hotels and not a numeric ID, set a default
            setHotel(createDefaultHotel(params.id));
          }
        }
      } catch (error) {
        console.error("Error fetching hotel data:", error);
        setHotel(createDefaultHotel(params.id));
      } finally {
        setLoading(false);
      }
    }
    
    // Helper function to create a default hotel
    const createDefaultHotel = (id) => ({
      clientId: id,
      title: "Example Hotel",
      Stars: 4,
      image: null,
      location: "Example Location",
      Price: 299,
      Rating: "8.7",
      "Trip Info": "7 Tage, All Inclusive"
    });
    
    fetchHotel();
  }, [params.id])
  
  if (loading) {
    return (
      <>
        <Header />
        <DetailedHotelPlaceholder />
        <Footer />
      </>
    )
  }
  
  // Helper function to render star rating
  const renderStars = (count) => {
    const stars = [];
    const starCount = parseInt(count) || 0;
    
    for (let i = 0; i < 5; i++) {
      stars.push(
        <Star 
          key={i} 
          className={`w-5 h-5 ${i < starCount ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'}`} 
        />
      );
    }
    
    return stars;
  };
  
  return (
    <>
      <Header />
      <div className="flex flex-col min-h-screen bg-gray-50">
      <main className="flex-grow">
        {/* Breadcrumb navigation */}
        <div className="bg-white my-5">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div className="flex items-center text-sm text-gray-500">
              <Link href="/" className="hover:text-blue-600">Startseite</Link>
              <span className="mx-2">/</span>
              <span className="text-gray-900 font-medium truncate">{hotel.title || hotel.Hotel || "Hotel Details"}</span>
            </div>
          </div>
        </div>
          
        {/* Main content */}
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 -mt-10 relative z-10">
          {/* Left column - Hotel details */}
          <div className="lg:col-span-2 mb-3">
            <div className="flex items-start justify-between">
              <div>
                <h2 className="text-xl font-bold text-blue-600 flex items-start">{hotel.title}
                    {hotel.stars && (
                        <span className="ml-2 inline-flex">
                            {[...Array(parseInt(hotel.stars))].map((_, i) => (
                                <svg key={i} xmlns="http://www.w3.org/2000/svg" className="h-4 w-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            ))}
                        </span>
                    )}
                </h2>
                <p className="text-lg text-gray-600 flex items-center gap-1">
                  <MapPin className="w-4 h-4" /> {hotel.location || hotel.Location || ""}
                </p>
              </div>
              <div className="flex flex-col items-end">
                <div className="bg-blue-900 text-white rounded px-3 py-2 flex flex-col items-center mb-1">
                  <span className="text-xl font-bold">{hotel.Rating || hotel.rating || "8.5"}</span>
                  <span className="text-xs uppercase">{getRatingText(hotel.Rating || hotel.rating)}</span>
                </div>
              </div>
            </div>
          </div>
          <div className="h-[30vh] sm:h-[40vh] md:h-[50vh] w-full bg-gray-200 relative overflow-hidden">
              {hotel.image ? (
                <img 
                  src={hotel.image.startsWith('http') ? hotel.image : `http://localhost:8000/uploads/images/${hotel.image}`} 
                  alt={hotel.title || hotel.Hotel} 
                  className="object-cover w-full h-96 rounded-lg"
                />
              ) : (
                <div className="w-full h-full bg-gray-200 flex items-center justify-center">
                  <span className="text-gray-500">No Image Available</span>
                </div>
              )}
            </div>
          {/* Hotel info card */}
          <div className="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div className="p-6">
              <div className="grid grid-cols-1 lg:grid-cols-3 gap-6"> 
                {/* Left column - Hotel description */}
                <div className="lg:col-span-2">
                  <h3 className="text-lg text-gray-900 font-semibold mb-3">Beschreibung</h3>
                  <div className="prose max-w-none">
                    {hotel.description ? (
                      <p className="text-gray-700">{hotel.description}</p>
                    ) : (
                      <p className="text-gray-500 italic">Keine Beschreibung verfügbar</p>
                    )}
                  </div>
                </div>
                 {/* Right column - Price and booking */}
                <div className="bg-blue-50 p-4 rounded-lg border border-blue-100 flex flex-col justify-between">
                  <div className="flex justify-between items-center mb-2">
                    <div>
                      <div className="text-sm text-gray-600 mb-5">
                      {hotel.days}<span className="ml-1 mr-2">Tage</span>|{hotel.person}<span className="mx-1">Pers.</span>| <span className="ml-1">{hotel.info}</span>
                      </div>
                      <div className="flex items-baseline">
                        <span className="text-3xl font-bold text-blue-600">{hotel.Price || hotel.price || "kostenlos"}€</span>
                      </div>
                    </div>
                  </div>
                  <div className="space-y-3">
                    <button className="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-md font-semibold transition-colors cursor-not-allowed">
                      Jetzt buchen
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
      
    </div>
    <Footer />
    </>
  )
} 