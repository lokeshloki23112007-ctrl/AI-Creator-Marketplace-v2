import { useState } from 'react'
import { useNavigate } from 'react-router-dom'

const popularSearches = ['AI Video', 'Runway', 'Midjourney', 'Animation', 'Product Ads']

function SearchBar() {
  const navigate = useNavigate()
  const [searchTerm, setSearchTerm] = useState('')

  const handleSearch = (event) => {
    event.preventDefault()
    navigate('/brand/creators', { state: { searchTerm: searchTerm.trim() } })
  }

  return (
    <section className="relative -mt-3 bg-[#040d1d] px-4 pb-10 sm:px-6 lg:px-8">
      <div className="mx-auto max-w-7xl">
        <form onSubmit={handleSearch} className="rounded-[28px] border border-white/10 bg-white p-3 shadow-[0_25px_50px_rgba(59,130,246,0.12)]">
          <div className="flex flex-col gap-3 lg:flex-row lg:items-center">
            <div className="flex flex-1 items-center gap-3 rounded-full bg-[#f4f7fb] px-5 py-4 shadow-inner shadow-slate-200/80">
              <span className="text-2xl text-slate-700">⌕</span>
              <input
                type="text"
                placeholder="Search creators, skills, tools, or content type..."
                value={searchTerm}
                onChange={(event) => setSearchTerm(event.target.value)}
                className="w-full border-0 bg-transparent text-base text-slate-700 placeholder:text-slate-400 focus:outline-none"
              />
            </div>

            <button
              type="submit"
              className="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] px-8 py-4 text-base font-semibold text-white shadow-[0_0_24px_rgba(168,85,247,0.45)] transition hover:brightness-110"
            >
              <span className="mr-2 text-lg">⌕</span>
              Search
            </button>
          </div>
        </form>

        <div className="mt-5 flex flex-wrap items-center gap-3 text-sm text-slate-300 lg:justify-center">
          <span className="mr-1 font-medium text-slate-200">Popular Searches:</span>
          {popularSearches.map((item) => (
            <button
              key={item}
              type="button"
              onClick={() => navigate('/brand/creators', { state: { searchTerm: item } })}
              className="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-sm text-slate-200 transition hover:border-[#8b5cf6]/60 hover:text-white"
            >
              {item}
            </button>
          ))}
        </div>
      </div>
    </section>
  )
}

export default SearchBar