// components/HotelDestinationPlaceholder.js
const HotelDestinationPlaceholder = () => {
    return (
      <div className="max-w-7xl mx-auto mt-5">
        {/* Header Placeholder */}
        <div className="flex items-center justify-center">
            <div className="w-96 h-4 bg-gray-200 rounded mb-5 animate-shimmer"></div>
            <div className="w-16 h-4 bg-gray-200 rounded mb-5 animate-shimmer ml-2"></div>
        </div>
  
        {/* Card Grid (Horizontal Scroll) */}
        <div className="flex gap-5 overflow-x-auto pb-3">
          {[...Array(4)].map((_, index) => (
            <div
              key={index}
              className="flex-shrink-0 w-72 rounded-lg overflow-hidden relative"
            >
              {/* Image Placeholder */}
              <div className="w-full h-48 bg-gray-200 animate-shimmer"></div>
  
              {/* Text Placeholder */}
              <div className="p-3">
                <div className="w-3/4 h-5 bg-gray-200 rounded mb-3 animate-shimmer"></div>
                <div className="w-1/2 h-4 bg-gray-200 rounded animate-shimmer"></div>
              </div>
            </div>
          ))}
        </div>
      </div>
    );
  };
  
  export default HotelDestinationPlaceholder;