'use client'

import Image from 'next/image'
import Link from 'next/link'
import TravelTypesLoading from "../UI/TravelTypesPlaceholder";

export default function TravelTypes({ isLoading, data }) {
  if (isLoading) return <TravelTypesLoading />;
  const cards = [
    {
      title: 'Günstige Mietwagen vergleichen',
      image: '/TravelTypes/Busreise_850px.jpg',
      link: '/mietwagen',
      imagePosition: 'center',
      button: 'Jetzt vergleichen',
    },
    {
      title: 'Weltraumreisen bald bei Check24',
      image: '/TravelTypes/make_life_multiplanetary_desktop_3fa7cff73c.jpg',
      link: '/hotels',
      imagePosition: 'center',
      button: 'Jetzt vergleichen',
    },
    {
      title: 'Risikoleben – absichern und 100 € Cashback',
      image: '/TravelTypes/pkv_aktion2.jpg',
      link: '/versicherung',
      imagePosition: 'center',
      button: 'Jetzt vergleichen',
    },
    {
      title: 'Flug + Hotel entdecken und bis zu 60% sparen',
      image: '/TravelTypes/travelship.jpg',
      link: '/reise',
      imagePosition: 'center',
      button: 'Jetzt vergleichen',
    },
  ]

  return (
    <section className="container max-w-7xl mx-auto px-4 py-8">
      <div className="grid grid-cols-1 -mt-32 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {cards.map((card, index) => (
          <Link
            key={index}
            href={card.link}
            className="group block bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow"
          >
            <div className="relative h-48">
              <Image
                src={card.image || "/placeholder.svg"}
                alt={card.title}
                fill
                style={{ objectFit: 'cover', objectPosition: card.imagePosition }}
                className="group-hover:scale-105 transition-transform duration-300"
              />
            </div>
            <div className="p-4">
              <h3 className="text-lg font-semibold text-gray-800 mb-2">
                {card.title}
              </h3>
              {card.button && (
                <button className="mt-2 w-full bg-[#0066CC] text-white py-2 px-4 rounded hover:bg-blue-700 transition-colors">
                  {card.button}
                </button>
              )}
            </div>
          </Link>
        ))}
      </div>
    </section>
  )
}