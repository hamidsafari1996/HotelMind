'use client'

import { useState } from "react"
import { useRouter } from 'next/navigation'

export default function MainSlider() {
  const [input, setInput] = useState('')
  const [chatHistory, setChatHistory] = useState([
    { role: "bot", content: "Hallo, ich bin Sofia, dein Reiseberater. Wie kann ich dir heute behilflich sein?" }
  ])
  const [query, setQuery] = useState('');
  const router = useRouter();

  const handleKeyDown = (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      handleSubmit(e);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    // Don't submit if query is empty
    if (!query.trim()) return;

    try {
        console.log("Submitting query:", query);
        
        const response = await fetch('http://localhost:5000/hotel-search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ query })
        });

        if (!response.ok) {
            throw new Error(`API responded with status: ${response.status}`);
        }

        const data = await response.json();
        
        // More detailed logging
        console.log("Full API Response:", JSON.stringify(data));
        
        // Direct navigation with the data - simplified approach
        const encodedAnswer = encodeURIComponent(JSON.stringify(data));
        console.log("Encoded answer length:", encodedAnswer.length);
        
        router.push(`/results?question=${encodeURIComponent(query)}&answer=${encodedAnswer}`);
    } catch (error) {
        console.error("Error during hotel search:", error);
        alert("Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.");
    }
  };

  

  return (
    <div className="relative min-h-[470px] flex items-center bg-cover bg-center"
      style={{
        backgroundImage: "url('/slider/background_rsnpau_v2.jpg')",
      }}
    >
      {/* Navigation Arrows */}
      <div className="container mx-auto px-4 py-16">
        <div className="max-w-4xl mx-auto text-center text-white mb-8">
          <h2 className="text-4xl font-bold mb-4">
            Hallo, Ich bin Sophie
          </h2>
          <p className="text-xl">Wie kann ich dir heute helfen?</p>
          {/* Input area */}
          <form onSubmit={handleSubmit}>
          <div className="p-4">
            <div className="relative rounded-2xl bg-[#ffffff] border border-blue-700">
              <div className="flex items-center">
                <textarea
                  value={query}
                  onChange={(e) => setQuery(e.target.value)}
                  onKeyDown={handleKeyDown}
                  placeholder="Wie kann ich Ihnen bei der Hotelsuche helfen?"
                  className="flex-1 border-0 bg-transparent no-ring-input h-32 focus-visible:outline-none text-black pl-6 resize-none pt-6"
                />
                <div className="flex items-center pr-3">
                  <button
                    type="submit"
                    disabled={!query.trim()}
                    className="rounded-full text-gray-400 hover:text-white hover:bg-gray-700 p-2 relative -bottom-5 cursor-pointer delay-150 duration-300 ease-in-out hover:-translate"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" className="size-6">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>
  )
}