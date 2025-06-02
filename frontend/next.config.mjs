/** @type {import('next').NextConfig} */
const nextConfig = {
    reactStrictMode: false,
    experimental: {
        // Remove invalid suppressHydrationWarning option
    },
    compiler: {
        removeConsole: process.env.NODE_ENV === 'production',
    },
    images: {
        domains: ['localhost'],
        unoptimized: true,
    },
    async rewrites() {
        return [
          {
            source: "/api/:path*",
            destination: "http://localhost:8000/api/:path*",
          },
        ];
    },
};

export default nextConfig;
