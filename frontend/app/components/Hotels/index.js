"use client"

import { useState, useEffect } from "react"
import Image from "next/image"
import Link from "next/link"
import { GridPlaceholder, ImageCardPlaceholder } from "../UI/LoadingPlaceholders"

// Sample hotel data
const hotels = [
  {
    id: 1,
    name: "Hotel Bellevue",
    location: "Wien, Österreich",
    rating: 4.7,
    price: "€120",
    image: "/placeholder.svg"
  },
  {
    id: 2,
    name: "Grand Palace Hotel",
    location: "Paris, Frankreich",
    rating: 4.9,
    price: "€185",
    image: "/placeholder.svg"
  },
  {
    id: 3,
    name: "Seaside Resort",
    location: "Barcelona, Spanien",
    rating: 4.5,
    price: "€95",
    image: "/placeholder.svg"
  },
  {
    id: 4,
    name: "Mountain View Lodge",
    location: "Zürich, Schweiz",
    rating: 4.6,
    price: "€145",
    image: "/placeholder.svg"
  }
]

export default function Hotels() {
  const [mounted, setMounted] = useState(false)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    setMounted(true)
    // Simulate loading delay
    const timer = setTimeout(() => {
      setLoading(false)
    }, 2000)
    
    return () => clearTimeout(timer)
  }, [])

  // Don't render anything until mounted on client
  if (!mounted) {
    return (
      <section className="py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div className="max-w-7xl mx-auto">
          <GridPlaceholder />
        </div>
      </section>
    )
  }

  return (
    <section className="py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-7xl mx-auto">
        <div className="flex items-center justify-between mb-6">
          {loading ? (
            <div className="w-40 h-8 bg-gray-200 animate-pulse rounded" />
          ) : (
            <h2 className="text-2xl font-bold text-gray-900">Empfohlene Hotels</h2>
          )}
          
          {loading ? (
            <div className="w-24 h-10 bg-gray-200 animate-pulse rounded-md" />
          ) : (
            <button className="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 border border-blue-600 rounded-md">
              Alle anzeigen
            </button>
          )}
        </div>

        {loading ? (
          // Use the reusable grid placeholder
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <ImageCardPlaceholder height="320px" />
            <ImageCardPlaceholder height="320px" />
            <ImageCardPlaceholder height="320px" />
            <ImageCardPlaceholder height="320px" />
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {hotels.map((hotel) => (
              <Link
                key={hotel.id}
                href="#"
                className="group"
              >
                <div className="relative h-48 mb-3 overflow-hidden rounded-lg">
                  <Image
                    src={hotel.image}
                    alt={hotel.name}
                    fill
                    className="object-cover transition-transform duration-300 group-hover:scale-105"
                  />
                </div>
                <h3 className="text-lg font-medium text-gray-900 group-hover:text-blue-600">
                  {hotel.name}
                </h3>
                <p className="text-sm text-gray-600">{hotel.location}</p>
                <div className="flex items-center mt-2">
                  <span className="flex items-center text-yellow-500">
                    {'★'.repeat(Math.floor(hotel.rating))}
                    {'☆'.repeat(5 - Math.floor(hotel.rating))}
                  </span>
                  <span className="ml-2 text-sm text-gray-600">{hotel.rating}</span>
                </div>
                <p className="mt-2 text-lg font-semibold">ab {hotel.price} / Nacht</p>
              </Link>
            ))}
          </div>
        )}
      </div>
    </section>
  )
} 