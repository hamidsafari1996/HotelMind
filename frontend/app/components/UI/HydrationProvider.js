'use client'

import { createContext, useContext, useEffect, useState } from 'react'

const HydrationContext = createContext({
  isHydrated: false
})

export function HydrationProvider({ children }) {
  const [isHydrated, setIsHydrated] = useState(false)

  useEffect(() => {
    setIsHydrated(true)
  }, [])

  return (
    <HydrationContext.Provider value={{ isHydrated }}>
      <div suppressHydrationWarning={true}>
        {children}
      </div>
    </HydrationContext.Provider>
  )
}

export function useHydration() {
  return useContext(HydrationContext)
}

export function HydratedOnly({ children, fallback = null }) {
  const { isHydrated } = useHydration()
  
  if (!isHydrated) {
    return fallback
  }
  
  return children
} 