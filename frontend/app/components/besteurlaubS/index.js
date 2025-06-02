"use client"
import { useState, useEffect } from "react"
import useSildaDeals from "../../hooks/useSilda"
import HotelSliderS from "../HotelSliderS"
import BesteurlaubSLoading from "../UI/HotelPlaceholder";

export default function BesteurlaubS() {
  const [mounted, setMounted] = useState(false)
  const { deals, loading, error } = useSildaDeals()

  useEffect(() => {
    setMounted(true)
  }, [])

  // Don't render anything until mounted on client
  if (!mounted) {
    return <BesteurlaubSLoading />
  }

  if (loading) return <BesteurlaubSLoading />;
  if (error) return <div className="py-12 text-center text-red-500">Error: {error}</div>
  if (deals.length === 0) return <div className="py-12 text-center">No deals available</div>

  return (
    <section className="py-12 px-4">
      <div className="max-w-7xl mx-auto">
        <div className="flex items-end justify-center mb-8">
          <h2 className="text-2xl font-bold text-gray-900">Unsere besten Urlaubsreisen - Side & Alanya</h2>
          <button className="text-blue-600 hover:text-blue-700 ml-1 cursor-not-allowed">mehr</button>
        </div>
        <HotelSliderS deals={deals} />
      </div>
    </section>
  )
}