"use client"

import Image from "next/image"
import Link from "next/link"
import FerienwohnungenLoading from "../UI/LoadingPlaceholders";

const cities = [
  {
    name: "Mallorca",
    accommodations: "6.604",
    image: "/Ferienwohnungen/picture.avif",
    size: "large",
  },
  {
    name: "Allgäu",
    accommodations: "2.150",
    image: "/Ferienwohnungen/picture1.avif",
    size: "large",
  },
  {
    name: "Gardasee",
    accommodations: "5.477",
    image: "/Ferienwohnungen/picture2.avif",
    size: "small",
  },
  {
    name: "Istrien",
    accommodations: "8.003",
    image: "/Ferienwohnungen/picture3.avif",
    size: "small",
  },
  {
    name: "Rügen",
    accommodations: "5.079",
    image: "/Ferienwohnungen/picture4.avif",
    size: "small",
  },
]

export default function Ferienwohnungen({ isLoading, data }) {
  if (isLoading) return <FerienwohnungenLoading />;
  return (
    <section className="py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-7xl mx-auto">
        <div className="flex items-end justify-between sm:justify-center mb-6 sm:mb-8">
          <h2 className="text-xl sm:text-2xl font-bold text-gray-900">Beliebte Reiseziele für Ferienwohnungen</h2>
          <button className="text-blue-600 hover:text-blue-700 ml-1 cursor-not-allowed text-sm sm:text-base">mehr</button>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 sm:gap-4">
          {cities.map((city, index) => (
            <Link
              key={city.name}
              href="#"
              className={`relative group overflow-hidden rounded-lg cursor-not-allowed w-full ${
                city.size === "large"
                  ? "h-[200px] sm:h-[250px] lg:h-[216px] lg:col-span-3"
                  : "h-[180px] sm:h-[200px] lg:h-[216px] lg:col-span-2"
              }`}
            >
              <Image
                src={city.image || "/placeholder.svg"}
                alt={city.name}
                fill
                className="object-cover transition-transform duration-300 group-hover:scale-105"
                sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
              <div className="absolute bottom-0 left-0 p-3 sm:p-4 text-white">
                <h3 className="text-xl sm:text-2xl font-bold mb-0.5 sm:mb-1">{city.name}</h3>
                <p className="text-xs sm:text-sm text-white/90">{city.accommodations} Unterkünfte</p>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </section>
  )
}