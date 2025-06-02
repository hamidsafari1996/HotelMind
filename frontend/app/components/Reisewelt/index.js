"use client"

import Image from "next/image"
import Link from "next/link"
import { Swiper, SwiperSlide } from "swiper/react"
import { Navigation } from "swiper/modules"
import { ChevronRight, ChevronLeft } from "lucide-react"
import ReiseweltLoading from "../UI/ReiseweltPlaceholder.js";

import "swiper/css"
import "swiper/css/navigation"

const inspirations = [
  {
    id: 1,
    title: "Die 15 schönsten\nReiseziele in Europa\n2025",
    image: "/Reisewelt/reuswelt1.jpg",
    link: "#",
  },
  {
    id: 2,
    title: "Günstige Reiseziele\n2025",
    image: "/Reisewelt/reuswelt2.jpg",
    link: "#",
  },
  {
    id: 3,
    title: "Günstige Alternativen\nzu beliebten\nReisezielen in Europa",
    image: "/Reisewelt/reuswelte.jpg",
    link: "#",
  },
  {
    id: 4,
    title: "Beliebteste\nUrlaubsländer für\nPauschalreisen 2025",
    image: "/Reisewelt/reuswelt4.jpg",
    link: "#",
  },
  {
    id: 5,
    title: "Beliebteste\nUrlaubsländer für\nPauschalreisen 2025",
    image: "/Reisewelt/reuswelt5.jpg",
    link: "#",
  },
  {
    id: 6,
    title: "Beliebteste\nUrlaubsländer für\nPauschalreisen 2025",
    image: "/Reisewelt/reuswelt6.jpg",
    link: "#",
  },
  {
    id: 7,
    title: "Beliebteste\nUrlaubsländer für\nPauschalreisen 2025",
    image: "/Reisewelt/reuswelt7.jpg",
    link: "#",
  },
  {
    id: 8,
    title: "Beliebteste\nUrlaubsländer für\nPauschalreisen 2025",
    image: "/Reisewelt/reuswelt8.jpg",
    link: "#",
  },
  {
    id: 9,
    title: "Beliebteste\nUrlaubsländer für\nPauschalreisen 2025",
    image: "/Reisewelt/reuswelt9.jpg",
    link: "#",
  },
]

export default function TravelInspiration({ isLoading, data }) {
  if (isLoading) return <ReiseweltLoading />;
  return (
    <section className="py-12 px-4">
      <div className="max-w-7xl mx-auto">
        <div className="flex items-center justify-center mb-8">
          <h2 className="text-2xl font-bold text-gray-900">CHECK24 Reisewelt - lassen Sie sich inspirieren!</h2>
          <button className="text-blue-600 hover:text-blue-700 cursor-not-allowed">mehr</button>
        </div>

        <div className="relative">
          <Swiper
            modules={[Navigation]}
            navigation={{
              nextEl: ".swiper-button-next",
              prevEl: ".swiper-button-prev",
            }}
            spaceBetween={16}
            slidesPerView={1}
            breakpoints={{
              640: { slidesPerView: 2 },
              1024: { slidesPerView: 4 },
            }}
            className="!px-4"
          >
            {inspirations.map((item) => (
              <SwiperSlide key={item.id}>
                <Link href={item.link} className="block group h-[386px] cursor-not-allowed">
                  <div className="relative h-full overflow-hidden">
                    <Image
                      src={item.image || "/placeholder.svg"}
                      alt={item.title}
                      fill
                      className="object-cover transition-transform duration-300 group-hover:scale-105"
                    />
                    <div className="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors" />
                    <div className="absolute inset-0 flex items-center justify-center p-4">
                      <h3 className="text-white text-center font-bold text-xl md:text-2xl whitespace-pre-line">
                        {item.title}
                      </h3>
                    </div>
                  </div>
                </Link>
              </SwiperSlide>
            ))}
          </Swiper>

          <button className="swiper-button-prev !w-10 !h-10 !bg-white rounded-full shadow-lg after:!text-gray-600 hover:!bg-gray-50">
          <ChevronLeft className='text-slate-400' />
            </button>
          <button className="swiper-button-next !w-10 !h-10 !bg-white rounded-full shadow-lg after:!text-gray-600 hover:!bg-gray-50">
          <ChevronRight className='text-slate-400' />
            </button>
        </div>
      </div>
    </section>
  )
}