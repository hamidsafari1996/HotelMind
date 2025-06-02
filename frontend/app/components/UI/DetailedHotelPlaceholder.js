// components/DetailedHotelPlaceholder.js
const DetailedHotelPlaceholder = () => {
    return (
      <div className="max-w-7xl mx-auto my-7">
        <div className="rounded-lg overflow-hidden bg-white shadow-md">
          {/* Header Section */}
          <div className="flex items-center justify-between p-3">
            <div>
              {/* Breadcrumb Placeholder */}
              <div className="w-32 h-4 bg-gray-200 rounded mb-2 animate-shimmer"></div>
              {/* Hotel Name Placeholder */}
              <div className="w-40 h-4 bg-gray-200 rounded mb-2 animate-shimmer"></div>
              {/* Location Placeholder */}
              <div className="w-48 h-4 bg-gray-200 rounded animate-shimmer"></div>
            </div>
            {/* Rating Placeholder */}
            <div className="w-12 h-12 bg-gray-200 rounded animate-shimmer"></div>
          </div>
          <div className="w-full h-72 bg-gray-200 animate-shimmer"></div>
          {/* Main Content Section */}
          <div className="flex">
            {/* Description and Details */}
            <div className="p-3 flex-1 flex justify-between">
              <div>
                {/* Description Title Placeholder */}
                <div className="w-[300px] h-6 bg-gray-200 rounded mb-2 animate-shimmer"></div>
                {/* Description Text Placeholder */}
                <div className="w-[500px] h-4 bg-gray-200 rounded mb-1 animate-shimmer"></div>
                <div className="w-[500px] h-4 bg-gray-200 rounded mb-1 animate-shimmer"></div>
                <div className="w-[500px] h-4 bg-gray-200 rounded mb-1 animate-shimmer"></div>
                <div className="w-[500px] h-4 bg-gray-200 rounded mb-1 animate-shimmer"></div>
                <div className="w-[350px] h-4 bg-gray-200 rounded mb-1 animate-shimmer"></div>
                <div className="w-[310px] h-4 bg-gray-200 rounded mb-1 animate-shimmer"></div>
              </div>
              {/* Price and Button Section */}
              <div className="w-[300px] h-[300px] flex flex-col items-end bg-gray-200 rounded-lg p-3"></div>
            </div>
          </div>
        </div>
      </div>
    );
  };
  export default DetailedHotelPlaceholder;