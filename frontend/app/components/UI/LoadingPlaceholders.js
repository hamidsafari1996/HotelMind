// components/DestinationPlaceholder.js
const DestinationPlaceholder = () => {
    return (
      <div className="max-w-7xl mx-auto">
        {/* Header Placeholder */}
        <div className="flex items-center justify-center">
            <div className="w-96 h-4 bg-gray-200 rounded mb-5 animate-shimmer"></div>
            <div className="w-16 h-4 bg-gray-200 rounded mb-5 animate-shimmer ml-2"></div>
        </div>
  
        {/* Card Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 sm:gap-4">
          {/* Large Left Card (Mallorca) */}
          <div className="relative group overflow-hidden rounded-lg cursor-not-allowed w-full h-[200px] sm:h-[250px] lg:h-[216px] lg:col-span-3">
            <div className="w-full h-64 bg-gray-200 animate-shimmer relative">
              <div className="absolute top-3 left-3 w-24 h-5 bg-gray-300 rounded animate-shimmer"></div>
              <div className="absolute top-10 left-3 w-32 h-4 bg-gray-300 rounded animate-shimmer"></div>
            </div>
          </div>
  
          {/* Top Right Card (Allgäu) */}
          <div className="relative group overflow-hidden rounded-lg cursor-not-allowed w-full h-[200px] sm:h-[250px] lg:h-[216px] lg:col-span-3">
            <div className="w-full h-64 bg-gray-200 animate-shimmer relative">
              <div className="absolute top-3 left-3 w-24 h-5 bg-gray-300 rounded animate-shimmer"></div>
              <div className="absolute top-10 left-3 w-32 h-4 bg-gray-300 rounded animate-shimmer"></div>
            </div>
          </div>
  
          {/* Middle Left Card (Gardasee) */}
          <div className="relative group overflow-hidden rounded-lg cursor-not-allowed w-full h-[180px] sm:h-[200px] lg:h-[216px] lg:col-span-2">
            <div className="w-full h-64 bg-gray-200 animate-shimmer relative">
              <div className="absolute top-3 left-3 w-24 h-5 bg-gray-300 rounded animate-shimmer"></div>
              <div className="absolute top-10 left-3 w-32 h-4 bg-gray-300 rounded animate-shimmer"></div>
            </div>
          </div>
  
          {/* Middle Center Card (Istrien) */}
          <div className="relative group rounded-lg cursor-not-allowed w-full h-[180px] sm:h-[200px] lg:h-[216px] lg:col-span-2 overflow-hidden">
            <div className="w-full h-64 bg-gray-200 animate-shimmer relative">
              <div className="absolute top-3 left-3 w-24 h-5 bg-gray-300 rounded animate-shimmer"></div>
              <div className="absolute top-10 left-3 w-32 h-4 bg-gray-300 rounded animate-shimmer"></div>
            </div>
          </div>
  
          {/* Large Right Card (Rügen) */}
          <div className="relative group overflow-hidden rounded-lg cursor-not-allowed w-full h-[180px] sm:h-[200px] lg:h-[216px] lg:col-span-2">
            <div className="w-full h-64 bg-gray-200 animate-shimmer relative">
              <div className="absolute top-3 left-3 w-24 h-5 bg-gray-300 rounded animate-shimmer"></div>
              <div className="absolute top-10 left-3 w-32 h-4 bg-gray-300 rounded animate-shimmer"></div>
            </div>
          </div>
        </div>
      </div>
    );
  };
  
  export default DestinationPlaceholder;