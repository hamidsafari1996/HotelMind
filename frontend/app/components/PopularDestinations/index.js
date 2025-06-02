"use client"

import React, { useRef, useState, useEffect } from 'react';
// Import Swiper React components
import { Swiper, SwiperSlide } from 'swiper/react';
import Image from 'next/image'
import { ChevronRight, ChevronLeft } from "lucide-react"
// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import PopularDestinationsLoading from "../UI/HotelDestinationPlaceholder";
// import required modules
import { Navigation } from 'swiper/modules';

const destinations = [
    {
        id: 1,
        name: "Italien",
        accommodations: "501.207",
        image: "/PopularDestinations/picture.webp",
    },
    {
        id: 2,
        name: "Portugal",
        accommodations: "97.036",
        image: "/PopularDestinations/picture1.webp",
    },
    {
        id: 3,
        name: "Kolobrzeg",
        accommodations: "4.660",
        image: "/PopularDestinations/picture2.webp",
    },
    {
        id: 4,
        name: "El Arenal",
        accommodations: "117",
        image: "/PopularDestinations/picture3.webp",
    },
    {
        id: 5,
        name: "Warnemünde",
        accommodations: "1.004",
        image: "/PopularDestinations/picture4.webp",
    },
    {
        id: 6,
        name: "Playa de Palma",
        accommodations: "129",
        image: "/PopularDestinations/picture5.webp",
    },
    {
        id: 7,
        name: "Mallorca",
        accommodations: "19.718",
        image: "/PopularDestinations/picture6.webp",
    },
    {
        id: 8,
        name: "Chalkidiki",
        accommodations: "7.564",
        image: "/PopularDestinations/picture7.webp",
    },
]

export default function PopularDestinations({ isLoading, data }) {
    const [mounted, setMounted] = useState(false)
    const [currentIndex, setCurrentIndex] = useState(0)

    useEffect(() => {
        setMounted(true)
    }, [])

    if (isLoading || !mounted) return <PopularDestinationsLoading />;

    return (
        <section className="py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
            <div className="max-w-7xl mx-auto">
                <div className="flex items-end justify-between sm:justify-center mb-6 sm:mb-8">
                    <h2 className="text-xl sm:text-2xl font-bold text-gray-900">Angesagte Reiseziele für Ihre nächste Hotelbuchung</h2>
                    <button className="text-blue-600 hover:text-blue-700 ml-1 cursor-not-allowed text-sm sm:text-base">mehr</button>
                </div>

                <div className="relative px-4 sm:px-0">
                    <Swiper
                        slidesPerView={1.2}
                        spaceBetween={12}
                        breakpoints={{
                            640: {
                                slidesPerView: 2.2,
                                spaceBetween: 16,
                            },
                            768: {
                                slidesPerView: 3,
                                spaceBetween: 20,
                            },
                            1024: {
                                slidesPerView: 4,
                                spaceBetween: 24,
                            },
                        }}
                        navigation={{
                            prevEl: '.swiper-button-prev',
                            nextEl: '.swiper-button-next',
                        }}
                        modules={[Navigation]}
                        className="mySwiper"
                    >
                        {destinations.slice(currentIndex, currentIndex + 8).map((destination) => (
                            <SwiperSlide key={destination.id}>
                                <div className='group cursor-not-allowed'>
                                    <div className="relative aspect-[4/3] rounded-lg overflow-hidden mb-2 sm:mb-3">
                                        <Image
                                            src={destination.image || "/placeholder.svg"}
                                            alt={destination.name}
                                            fill
                                            className="object-cover transition-transform duration-300 group-hover:scale-105"
                                            sizes="(max-width: 640px) 50vw, (max-width: 768px) 33vw, 25vw"
                                        />
                                    </div>
                                    <h3 className="text-base sm:text-lg font-semibold text-gray-900 mb-0.5 sm:mb-1 line-clamp-1">{destination.name}</h3>
                                    <p className="text-xs sm:text-sm text-gray-600">{destination.accommodations} Unterkünfte</p>
                                </div>
                            </SwiperSlide>
                        ))}
                    </Swiper>
                    
                    <div className="hidden sm:flex justify-between mt-4">
                        <button className="swiper-button-prev !w-8 !h-8 rounded-full shadow-lg hover:shadow-xl transition-shadow bg-white !-left-4 after:hidden">
                            <ChevronLeft className="w-5 h-5 text-gray-600"/>
                        </button>
                        <button className="swiper-button-next !w-8 !h-8 rounded-full shadow-lg hover:shadow-xl transition-shadow bg-white !-right-4 after:hidden">
                            <ChevronRight className="w-5 h-5 text-gray-600"/>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    )
}