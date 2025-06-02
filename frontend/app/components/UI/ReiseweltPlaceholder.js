const TravelGuidePlaceholder = () => {
  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-0 mb-10">
      {/* Card Grid (Responsive) */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {[...Array(4)].map((_, index) => (
          <div
            key={index}
            className="rounded-lg overflow-hidden relative"
          >
            {/* Image Placeholder */}
            <div className="w-full h-64 md:h-96 bg-gray-200 animate-shimmer"></div>
            {/* Text Placeholder */}
            <div className="p-3 absolute top-[50%] left-0 right-0 flex flex-col gap-2 items-center">
              <div className="w-5/6 h-6 bg-gray-400 rounded mb-2 animate-shimmer"></div>
              <div className="w-3/4 h-5 bg-gray-400 rounded animate-shimmer"></div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};
export default TravelGuidePlaceholder;