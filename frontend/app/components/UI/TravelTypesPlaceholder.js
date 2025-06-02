// components/OfferPlaceholder.js
const OfferPlaceholder = () => {
    return (
      <div className="max-w-7xl mx-auto z-10 relative -mt-32">
        {/* Card Grid (Horizontal Scroll) */}
        <div className="flex gap-5 overflow-x-auto pb-3">
          {[...Array(4)].map((_, index) => (
            <div
              key={index}
              className="flex-shrink-0 w-72 rounded-lg overflow-hidden bg-white shadow-md"
            >
              {/* Image Placeholder */}
              <div className="w-full h-48 bg-gray-200 animate-shimmer"></div>
  
              {/* Content Placeholder */}
              <div className="p-3">
                {/* Title Placeholder */}
                <div className="w-3/4 h-6 bg-gray-200 rounded mb-2 animate-shimmer"></div>
                {/* Description Placeholder */}
                <div className="w-5/6 h-4 bg-gray-200 rounded mb-4 animate-shimmer"></div>
                {/* Button Placeholder */}
                <div className="w-32 h-10 bg-gray-200 rounded animate-shimmer"></div>
              </div>
            </div>
          ))}
        </div>
      </div>
    );
  };
  
  export default OfferPlaceholder;