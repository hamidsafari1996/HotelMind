'use client'

import { useSearchParams } from 'next/navigation'
import Image from 'next/image'
import Header from '../components/Header';
import Slider from '../components/Slider';
import Footer from '../components/Footer';
import Link from 'next/link';
import ResponsiveImage from '../components/UI/ResponsiveImage';
import { useEffect, useState } from 'react';
import getRatingText from "../utils/getRatingText"
import SingleHotelPlaceholder from '../components/UI/SingleHotelPlaceholder';

// Helper function to generate deterministic IDs
function generateDeterministicId(hotel, index) {
    if (hotel.id) return hotel.id;
    
    // Use hotel properties to create a consistent ID
    const baseString = `${hotel.Hotel || hotel.title || 'hotel'}-${hotel.Price || 0}-${index}`;
    // Simple deterministic hash function for consistent ID generation
    let hash = 0;
    for (let i = 0; i < baseString.length; i++) {
        const char = baseString.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash; // Convert to 32-bit integer
    }
    // Ensure we always return a positive number as string
    return `hotel-${(hash >>> 0).toString(36)}`;
}

export default function ResultsPage(isLoading, data) {
    
    const searchParams = useSearchParams();
    const question = searchParams.get('question');
    const answerParam = searchParams.get('answer');

    const [parsedAnswer, setParsedAnswer] = useState([]);
    const [enrichedHotels, setEnrichedHotels] = useState([]);
    const [mounted, setMounted] = useState(false);

    // Ensure client-side mounting
    useEffect(() => {
        setMounted(true);
    }, []);

    // 1. Parse the answer from URL query string
    useEffect(() => {
        if (!answerParam || typeof answerParam !== 'string' || !mounted) return;

        try {
            const parsed = JSON.parse(decodeURIComponent(answerParam));
            const hotels = Array.isArray(parsed) ? parsed : parsed.hotels;

            if (hotels && Array.isArray(hotels)) {
                const hotelsWithIds = hotels.map((hotel, index) => ({
                    ...hotel,
                    id: hotel.id || generateDeterministicId(hotel, index)
                }));
                setParsedAnswer(hotelsWithIds);
            } else {
                setParsedAnswer([]);
            }
        } catch (error) {
            console.error("Error parsing answer from query param:", error);
            setParsedAnswer([]);
        }
    }, [answerParam, mounted]);

    // 2. Enrich hotels with additional data from the backend API
    useEffect(() => {
        if (!parsedAnswer.length || !mounted) return;

        const enrichHotels = async () => {
            try {
                const backendAPIUrl = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api/hotels";
                const response = await fetch(backendAPIUrl);
                
                if (!response.ok) {
                    throw new Error(`Failed to fetch hotel data: ${response.status}`);
                }
                
                const backendHotels = await response.json();
                
                const enrichedData = parsedAnswer.map(csvHotel => {
                    const matchingBackendHotel = backendHotels.find(bh => 
                        bh.title?.toLowerCase().includes(csvHotel.Hotel?.toLowerCase()) ||
                        csvHotel.Hotel?.toLowerCase().includes(bh.title?.toLowerCase())
                    );

                    return {
                        ...csvHotel,
                        backendData: matchingBackendHotel || null,
                        image: matchingBackendHotel?.image 
                            ? `${process.env.NEXT_PUBLIC_UPLOADS_URL || "http://localhost:8000/uploads/images/"}${matchingBackendHotel.image}`
                            : "/placeholder.svg",
                        fullTitle: matchingBackendHotel?.title || csvHotel.Hotel || 'Hotel',
                        description: matchingBackendHotel?.info || 'No description available',
                        stars: matchingBackendHotel?.stars || 3,
                        location: matchingBackendHotel?.location || csvHotel.Location || 'Unknown location'
                    };
                });

                setEnrichedHotels(enrichedData);
            } catch (error) {
                console.error("Error enriching hotel data:", error);
                setEnrichedHotels(parsedAnswer); // Fallback to original data
            }
        };

        enrichHotels();
    }, [parsedAnswer, mounted]);

    // Don't render anything until mounted
    if (!mounted) {
        return <SingleHotelPlaceholder />;
    }

    if (!question && !answerParam) {
        return (
            <div suppressHydrationWarning={true}>
                <Header />
                <div className="min-h-screen flex items-center justify-center">
                    <div className="text-center">
                        <h1 className="text-2xl font-bold text-gray-900 mb-4">No search results</h1>
                        <p className="text-gray-600 mb-8">Please perform a search to see results.</p>
                        <Link href="/" className="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                            Back to Home
                        </Link>
                    </div>
                </div>
                <Footer />
            </div>
        );
    }

    return (
        <div suppressHydrationWarning={true}>
            <Header />
            <Slider/>
            <main className="min-h-screen bg-gray-50 py-8">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


                    {/* Results */}
                    <div className="space-y-6">
                        {enrichedHotels.length > 0 ? (
                            enrichedHotels.map((hotel, index) => (
                                <div 
                                    key={hotel.id} 
                                    className="bg-sky-100 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow border-2 border-sky-200"
                                >
                                    <div className="flex flex-col md:flex-row">
                                        {/* Hotel Image */}
                                        <div className="w-full md:w-96 h-full relative">
                                            <ResponsiveImage
                                                src={hotel.image}
                                                alt={hotel.fullTitle}
                                                className="object-cover"
                                                fill
                                                sizes="(max-width: 768px) 100vw, 288px"
                                            />
                                        </div>

                                        {/* Hotel Details */}
                                        <div className="flex-1 p-6">
                                            <div className="flex flex-col md:flex-row md:justify-between h-full">
                                                <div className="flex-1">
                                                    <h3 className="text-xl font-bold text-gray-900 mb-2">
                                                        {hotel.fullTitle}
                                                    </h3>
                                                    <p className="text-gray-600 mb-2 flex items-center">
                                                        <span className="material-icons-outlined mr-1">📍</span>
                                                        {hotel.location}
                                                    </p>
                                                    <p className="text-gray-700 mb-4 line-clamp-2">
                                                        {hotel.description}
                                                    </p>
                                                    
                                                    {/* Star Rating */}
                                                    <div className="flex items-center mb-2">
                                                        {[...Array(5)].map((_, i) => (
                                                            <span 
                                                                key={i}
                                                                className={`text-lg ${
                                                                    i < hotel.stars ? 'text-yellow-400' : 'text-gray-300'
                                                                }`}
                                                            >
                                                                ★
                                                            </span>
                                                        ))}
                                                        <span className="text-sm text-gray-600 ml-2">
                                                            {hotel.stars} Star Hotel
                                                        </span>
                                                    </div>
                                                </div>

                                                {/* Price and Booking */}
                                                <div className="flex flex-col items-start md:items-end mt-4 md:mt-0 md:ml-6">
                                                    {hotel.Rating && (
                                                        <div className="mb-3 text-right">
                                                            <div className="bg-blue-600 text-white px-2 py-1 rounded text-sm font-bold">
                                                                {hotel.Rating}
                                                            </div>
                                                            <div className="text-xs text-gray-600 mt-1">
                                                                {getRatingText(hotel.Rating)}
                                                            </div>
                                                        </div>
                                                    )}
                                                    
                                                    <div className="text-right mb-4">
                                                        <div className="text-2xl font-bold text-gray-900">
                                                            €{hotel.Price}
                                                        </div>
                                                        <div className="text-sm text-gray-600">
                                                            per night
                                                        </div>
                                                    </div>

                                                    <Link 
                                                        href={`/hotel/${hotel.id}?data=${encodeURIComponent(JSON.stringify(hotel))}`}
                                                        className="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium"
                                                    >
                                                        zu den Angeboten
                                                    </Link>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <SingleHotelPlaceholder />
                        )}
                    </div>
                </div>
            </main>
            <Footer />
        </div>
    );
}