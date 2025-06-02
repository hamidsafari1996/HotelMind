// components/HotelSlider.js
import { useRef, useEffect, useState } from "react"
import { Swiper, SwiperSlide } from "swiper/react"
import { Navigation } from "swiper/modules"
import { ChevronLeft, ChevronRight } from "lucide-react"
import HotelCard from "../HotelCard"

import "swiper/css"
import "swiper/css/navigation"

export default function HotelSliderS({ deals }) {
  const prevRef = useRef(null)
  const nextRef = useRef(null)
  const [swiperInstance, setSwiperInstance] = useState(null)

  useEffect(() => {
    if (swiperInstance && prevRef.current && nextRef.current) {
      swiperInstance.params.navigation.prevEl = prevRef.current
      swiperInstance.params.navigation.nextEl = nextRef.current

      swiperInstance.navigation.destroy()
      swiperInstance.navigation.init()
      swiperInstance.navigation.update()
    }
  }, [swiperInstance])

  return (
    <div className="relative">
      <Swiper
        modules={[Navigation]}
        onSwiper={setSwiperInstance}
        loop
        spaceBetween={16}
        slidesPerView={1}
        breakpoints={{
          640: { slidesPerView: 2 },
          1024: { slidesPerView: 4 },
        }}
        className="!px-4"
      >
        {deals.map((deal) => (
          <SwiperSlide key={deal.id}>
            <HotelCard deal={deal} />
          </SwiperSlide>
        ))}
      </Swiper>
      <button ref={prevRef} className="absolute top-1/2 -translate-y-1/2 -left-2 z-10 w-10 h-10 rounded-full bg-white shadow-md border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition">
        <ChevronLeft className='text-slate-400' />
      </button>
      <button ref={nextRef} className="absolute top-1/2 -translate-y-1/2 -right-2 z-10 w-10 h-10 rounded-full bg-white shadow-md border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition">
        <ChevronRight className='text-slate-400' />
      </button>
    </div>
  );
}