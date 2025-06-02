"use client"

import Image from "next/image"
import Link from "next/link"
import { Facebook, Youtube, Instagram, ChevronDown, ChevronUp } from "lucide-react"
import { useState } from "react"

export default function Footer() {
    const [expandedSections, setExpandedSections] = useState({});

    const toggleSection = (section) => {
        setExpandedSections(prev => ({
            ...prev,
            [section]: !prev[section]
        }));
    };

    const footerLinks = {
        about: {
            title: "Über CHECK24",
            links: [
                { text: "Karriere", href: "#" },
                { text: "Presse", href: "#" },
                { text: "Unternehmen", href: "#" },
                { text: "CHECK24 Österreich", href: "#" },
                { text: "CHECK24 Spanien", href: "#" },
            ],
        },
        partners: {
            title: "Unsere Partner",
            links: [
                { text: "Partnerprogramm", href: "#" },
                { text: "Profi werden", href: "#" },
                { text: "Affiliate werden", href: "#" },
                { text: "Unterkunft anmelden", href: "#" },
            ],
        },
        engagement: {
            title: "Unser Engagement",
            links: [
                { text: "Nachhaltigkeit", href: "#" },
                { text: "CHECK24 hilft Kindern", href: "#" },
                { text: "CHECK24 hilft der Natur", href: "#" },
            ],
        },
        service: {
            title: "Unser Service für Sie",
            links: [
                { text: "Hilfe und Kontakt", href: "#" },
                { text: "CHECK24 App", href: "#" },
                { text: "CHECK24 Gutscheine", href: "#" },
                { text: "CHECK24 Punkte", href: "#" },
            ],
        },
    }

    return (
        <footer className="bg-[#003B95] text-white">
            {/* Back to top */}
            <div className="bg-[#0066CC] py-2 text-center">
                <Link href="#top" className="text-white hover:underline">
                    zurück zum Seitenanfang
                </Link>
            </div>

            <div className="max-w-7xl mx-auto px-4 pt-8 md:pt-12 pb-5">
                {/* Main footer links */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-y-4 gap-x-8 mb-8">
                    <div className="lg:col-span-2 mb-6 lg:mb-0">
                        <Image src="/logo/download.svg" alt="CHECK24" width={150} height={50} className="mb-2" />
                        <p className="text-white/90 text-sm mb-3">Deutschlands größtes Vergleichsportal</p>
                        <div className="flex flex-wrap items-center gap-2 mb-4">
                            <Link href="#" className="text-white/90 hover:text-white hover:underline text-sm">
                                AGB
                            </Link>
                            <span className="text-white/40">|</span>
                            <Link href="#" className="text-white/90 hover:text-white hover:underline text-sm">
                                Datenschutz
                            </Link>
                            <span className="text-white/40">|</span>
                            <Link href="#" className="text-white/90 hover:text-white hover:underline text-sm">
                                Impressum
                            </Link>
                        </div>
                        <div className="flex items-center gap-4">
                            <Link href="#" className="text-white/90 hover:text-white">
                                <Facebook className="w-5 h-5" />
                            </Link>
                            <Link href="#" className="text-white/90 hover:text-white">
                                <Youtube className="w-5 h-5" />
                            </Link>
                            <Link href="#" className="text-white/90 hover:text-white">
                                <Instagram className="w-5 h-5" />
                            </Link>
                            <Link href="#" className="text-white/90 hover:text-white">
                                <svg viewBox="0 0 24 24" className="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z" />
                                </svg>
                            </Link>
                        </div>
                    </div>

                    {/* Desktop Links - Only visible on lg screens and up */}
                    {Object.entries(footerLinks).map(([key, section]) => (
                        <div key={key} className="hidden lg:block">
                            <h3 className="font-bold mb-3 text-sm">{section.title}</h3>
                            <ul className="space-y-2">
                                {section.links.map((link) => (
                                    <li key={link.text}>
                                        <Link href={link.href} className="text-white/90 hover:text-white hover:underline text-sm">
                                            {link.text}
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    ))}

                    {/* Mobile Accordion - Only visible on screens smaller than lg */}
                    <div className="lg:hidden col-span-full">
                        {Object.entries(footerLinks).map(([key, section]) => (
                            <div key={key} className="border-b border-white/20 py-3">
                                <button 
                                    onClick={() => toggleSection(key)}
                                    className="w-full flex justify-between items-center"
                                >
                                    <h3 className="font-bold text-sm">{section.title}</h3>
                                    {expandedSections[key] ? 
                                        <ChevronUp className="w-5 h-5" /> : 
                                        <ChevronDown className="w-5 h-5" />
                                    }
                                </button>
                                {expandedSections[key] && (
                                    <ul className="mt-2 space-y-2 pl-2">
                                        {section.links.map((link) => (
                                            <li key={link.text}>
                                                <Link href={link.href} className="text-white/90 hover:text-white hover:underline text-sm">
                                                    {link.text}
                                                </Link>
                                            </li>
                                        ))}
                                    </ul>
                                )}
                            </div>
                        ))}
                    </div>
                </div>
                
                {/* Copyright */}
                <div className="text-center text-white/90 text-xs md:text-sm mt-6 md:mt-8 pt-3 border-t border-white/20">
                    <p className="mt-3">
                    © 2025 CHECK24 Vergleichsportal GmbH München. Alle Inhalte unterliegen unserem Copyright.
                    </p>   
                </div>
            </div>
        </footer>
    )
}