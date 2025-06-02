// components/ResponsiveImage.js
import Image from 'next/image'

const ResponsiveImage = ({
  src,
  alt,
  className = 'object-cover',
  sizes = '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw',
}) => {
  return (
    <div className={`relative w-full h-full ${className}`}>
      <img
        src={src}
        alt={alt}
        className={`w-full h-full ${className}`}
        width={400}
        sizes={sizes}
      />
    </div>
  )
}

export default ResponsiveImage