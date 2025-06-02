"use client"
import { useState, useEffect } from "react"
import useMallorcaDeals from "../../hooks/useMallorcaDeals"
import HotelSliderM from "../HotelSliderM"
import BesteurlaubMLoading from "../UI/HotelPlaceholder";

export default function BesteurlaubM() {
  const [mounted, setMounted] = useState(false)
  const { deals, loading, error } = useMallorcaDeals()

  useEffect(() => {
    setMounted(true)
  }, [])

  // Don't render anything until mounted on client
  if (!mounted) {
    return <BesteurlaubMLoading />
  }

  if (loading) return <BesteurlaubMLoading />;
  if (error) return <div className="py-12 text-center text-red-500">Error: {error}</div>
  if (deals.length === 0) return <div className="py-12 text-center">No deals available</div>

  return (
    <section className="py-12 px-4">
      <div className="max-w-7xl mx-auto">
        <div className="flex items-end justify-center mb-8">
          <h2 className="text-2xl font-bold text-gray-900">Unsere besten Urlaubsreisen - Mallorca</h2>
          <button className="text-blue-600 hover:text-blue-700 ml-1 cursor-not-allowed">mehr</button>
        </div>
        <HotelSliderM deals={deals} />
      </div>
    </section>
  )
}