// components/HotelCardPlaceholder.js
const HotelCardPlaceholder = () => {
    return (
      <div className="max-w-7xl mx-auto mt-5">
        {/* Header Placeholder */}
        <div className="flex items-center justify-center">
            <div className="w-96 h-4 bg-gray-200 rounded mb-3 animate-shimmer"></div>
            <div className="w-16 h-4 bg-gray-200 rounded mb-3 animate-shimmer ml-2"></div>
        </div>
  
        {/* Card Grid (2x2) */}
        <div className="grid grid-cols-4 gap-5">
          {[...Array(4)].map((_, index) => (
            <div
              key={index}
              className="rounded-lg overflow-hidden bg-white shadow-md"
            >
              {/* Image Placeholder */}
              <div className="w-full h-40 bg-gray-200 animate-shimmer"></div>
  
              {/* Content Placeholder */}
              <div className="p-3">
                {/* Title Placeholder */}
                <div className="w-3/4 h-5 bg-gray-200 rounded mb-3 animate-shimmer"></div>
                {/* Rating Placeholder */}
                <div className="w-1/2 h-4 bg-gray-200 rounded mb-3 animate-shimmer"></div>
                {/* Description Placeholder */}
                <div className="w-11/12 h-4 bg-gray-200 rounded mb-3 animate-shimmer"></div>
                {/* Price Placeholder */}
                <div className="w-2/3 h-5 bg-gray-200 rounded animate-shimmer"></div>
              </div>
            </div>
          ))}
        </div>
      </div>
    );
  };
  
  export default HotelCardPlaceholder;