// components/SingleHotelPlaceholder.js
const SingleHotelPlaceholder = () => {
    return (
      <div className="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        {[...Array(4)].map((_, index) => (
        <div key={index} className="flex flex-col md:flex-row rounded-lg overflow-hidden bg-white shadow-md py-4 my-4">
        
          <div className="w-full md:w-72 h-48 bg-gray-200 animate-shimmer"></div>
          <div className="p-3 flex-1 flex flex-col md:flex-row justify-between">
                <div>
                    {/* Hotel Name Placeholder */}
                    <div className="w-32 h-6 bg-gray-200 rounded mb-2 animate-shimmer"></div>
                    {/* Location Placeholder */}
                    <div className="w-48 h-4 bg-gray-200 rounded mb-2 animate-shimmer"></div>
                    {/* Details Placeholder */}
                    <div className="w-64 h-4 bg-gray-200 rounded mb-2 animate-shimmer"></div>
                </div>
                <div className="flex flex-col items-start md:items-end mt-4 md:mt-0">
                    {/* Rating Placeholder */}
                    <div className="w-12 h-6 bg-gray-200 rounded mb-2 animate-shimmer"></div>
                    {/* Price Placeholder */}
                    <div className="w-16 h-8 bg-gray-200 rounded mb-2 animate-shimmer"></div>
                    {/* Button Placeholder */}
                    <div className="w-32 h-10 bg-gray-200 rounded animate-shimmer"></div>
                </div>
            </div>
        
        </div>
        ))}
      </div>
    );
  };
  
  export default SingleHotelPlaceholder;