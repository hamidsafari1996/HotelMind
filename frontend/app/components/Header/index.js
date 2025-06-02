'use client'

import { Bell, Heart, User, HelpCircle, Menu, X } from 'lucide-react'
import Link from 'next/link'
import Image from 'next/image'
import { useState, useEffect } from 'react'

export default function Header() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false)
  const [mounted, setMounted] = useState(false)

  useEffect(() => {
    setMounted(true)
  }, [])

  return (
    <header className="bg-[#003366]" suppressHydrationWarning={true}>
      {/* Top Bar */}
      <div className="max-w-[1400px] mx-auto px-4 py-3">
        <div className="flex items-center justify-between gap-4">
          {/* Logo */}
          <Link href="/" className="shrink-0">
            <Image
              src="/logo/download.svg"
              alt="Logo"
              width={120}
              height={40}
              className="w-auto h-8"
            />
          </Link>

          {/* Search Bar */}
          <div className="flex-1 max-w-2xl relative hidden sm:block">
            {/* Search content goes here */}
          </div>

          {/* Right Navigation - Desktop */}
          <nav className="hidden md:flex items-center gap-6 text-white">
            <Link href="/hilfe" className="flex items-center gap-1 text-sm hover:text-blue-200">
              <HelpCircle className="w-5 h-5" />
              <span>Hilfe und Kontakt</span>
            </Link>
            <Link href="/notifications" className="hover:text-blue-200">
              <Bell className="w-5 h-5" />
            </Link>
            <Link href="/merkzettel" className="hover:text-blue-200">
              <Heart className="w-5 h-5" />
            </Link>
            <Link href="/login" className="flex items-center gap-1 text-sm hover:text-blue-200">
              <User className="w-5 h-5" />
              <span>Anmelden</span>
            </Link>
          </nav>

          {/* Mobile Menu Button */}
          <button 
            className="text-white md:hidden"
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            suppressHydrationWarning={true}
          >
            {mounted ? (
              mobileMenuOpen ? 
                <X className="w-6 h-6" /> : 
                <Menu className="w-6 h-6" />
            ) : (
              <Menu className="w-6 h-6" />
            )}
          </button>
        </div>
      </div>

      {/* Bottom Navigation - Desktop */}
      <nav className="border-t border-blue-800 hidden md:block">
        <div className="max-w-[1400px] mx-auto px-4 overflow-x-auto">
          <ul className="flex items-center gap-6 text-sm text-white whitespace-nowrap">
            <li>
              <Link href="/versicherungen" className="block py-3 hover:text-blue-200">
                Versicherungen
              </Link>
            </li>
            <li>
              <Link href="/konto" className="block py-3 hover:text-blue-200">
                Konto & Kredit
              </Link>
            </li>
            <li>
              <Link href="/energie" className="block py-3 hover:text-blue-200">
                Strom & Gas
              </Link>
            </li>
            <li>
              <Link href="/internet" className="block py-3 hover:text-blue-200">
                Internet
              </Link>
            </li>
            <li>
              <Link href="/handy" className="block py-3 hover:text-blue-200">
                Handy
              </Link>
            </li>
            <li>
              <Link href="/reise" className="block py-3 hover:text-blue-200">
                Reise
              </Link>
            </li>
            <li>
              <Link href="/fluege" className="block py-3 hover:text-blue-200">
                Flüge
              </Link>
            </li>
            <li>
              <Link href="/hotel" className="block py-3 hover:text-blue-200">
                Hotel & Ferienwohnung
              </Link>
            </li>
            <li>
              <Link href="/mietwagen" className="block py-3 hover:text-blue-200">
                Mietwagen
              </Link>
            </li>
            <li>
              <Link href="/profis" className="block py-3 hover:text-blue-200">
                Profis
              </Link>
            </li>
            <li>
              <Link href="/shopping" className="block py-3 hover:text-blue-200">
                Shopping
              </Link>
            </li>
            <li>
              <Link href="/steuer" className="block py-3 hover:text-blue-200">
                Steuer
              </Link>
            </li>
            <li>
              <Link href="/fussball" className="block py-3 hover:text-blue-200">
                Fußball
              </Link>
            </li>
          </ul>
        </div>
      </nav>

      {/* Mobile Menu */}
      {mounted && mobileMenuOpen && (
        <div className="md:hidden bg-[#003366] border-t border-blue-800" suppressHydrationWarning={true}>
          {/* Mobile Icons */}
          <div className="flex justify-around py-3 border-b border-blue-800">
            <Link href="/hilfe" className="text-white flex flex-col items-center">
              <HelpCircle className="w-5 h-5" />
              <span className="text-xs mt-1">Hilfe</span>
            </Link>
            <Link href="/notifications" className="text-white flex flex-col items-center">
              <Bell className="w-5 h-5" />
              <span className="text-xs mt-1">Benachrichtigungen</span>
            </Link>
            <Link href="/merkzettel" className="text-white flex flex-col items-center">
              <Heart className="w-5 h-5" />
              <span className="text-xs mt-1">Merkzettel</span>
            </Link>
            <Link href="/login" className="text-white flex flex-col items-center">
              <User className="w-5 h-5" />
              <span className="text-xs mt-1">Anmelden</span>
            </Link>
          </div>
          
          {/* Mobile Navigation Links */}
          <nav className="py-2 px-4">
            <ul className="text-white divide-y divide-blue-800">
              {[
                { href: "/versicherungen", label: "Versicherungen" },
                { href: "/konto", label: "Konto & Kredit" },
                { href: "/energie", label: "Strom & Gas" },
                { href: "/internet", label: "Internet" },
                { href: "/handy", label: "Handy" },
                { href: "/reise", label: "Reise" },
                { href: "/fluege", label: "Flüge" },
                { href: "/hotel", label: "Hotel & Ferienwohnung" },
                { href: "/mietwagen", label: "Mietwagen" },
                { href: "/profis", label: "Profis" },
                { href: "/shopping", label: "Shopping" },
                { href: "/steuer", label: "Steuer" },
                { href: "/fussball", label: "Fußball" },
              ].map((item, index) => (
                <li key={index}>
                  <Link href={item.href} className="block py-3 hover:text-blue-200">
                    {item.label}
                  </Link>
                </li>
              ))}
            </ul>
          </nav>
        </div>
      )}
    </header>
  )
}