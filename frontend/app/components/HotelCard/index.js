// components/HotelCard.js
import { Star } from "lucide-react"
import Link from "next/link"
import ResponsiveImage from "../UI/ResponsiveImage"
import getRatingText from "../../utils/getRatingText"

export default function HotelCard({ deal }) {
  return (
    <Link href={`/hotel/${deal.id}`} className="block h-full">
      <div className="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden h-full flex flex-col">
        <div className="relative w-full h-[200px] sm:h-[220px]">
          <ResponsiveImage 
            src={deal.image} 
            alt={deal.hotelName}
            className="object-cover"
            sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
          />
        </div>

        <div className="p-3 sm:p-4 flex flex-col flex-grow">
          <div className="text-xs sm:text-sm text-gray-600 mb-1 font-light">{deal.location}</div>
          <div className="text-[#0066CC] hover:underline font-semibold text-sm sm:text-base mb-2 line-clamp-2">
            {deal.hotelName}
          </div>

          <div className="flex items-center mb-2">
            {[...Array(deal.stars)].map((_, i) => (
              <Star key={i} className="w-3 h-3 sm:w-4 sm:h-4 fill-current text-yellow-400" />
            ))}
          </div>

          <div className="text-xs text-gray-400 mb-3 font-thin">
            {deal.duration}, {deal.mealPlan}
          </div>

          <div className="mt-auto">
            <div className="flex items-end justify-between gap-2 flex-wrap sm:flex-nowrap">
              <div className="flex items-left gap-1 sm:gap-2 flex-col">
                <span className="bg-[#003B95] text-white px-2 py-1 rounded text-xs sm:text-sm font-bold w-fit">
                  {deal.rating}
                </span>
                <span className="text-[10px] sm:text-xs font-medium text-left text-black">{getRatingText(deal.rating)}</span>
              </div>
              <div className="flex items-end justify-end gap-1 sm:gap-2 flex-col">
                <div className="flex items-end flex-wrap sm:flex-nowrap">
                  <div className="text-xs sm:text-sm text-gray-600 mr-2 sm:mr-3 font-light whitespace-nowrap">
                    {deal.person} Pers. ab
                  </div>
                  <div className="text-xl sm:text-2xl text-gray-900 font-light whitespace-nowrap">
                    {deal.finalPrice} €
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Link>
  );
}