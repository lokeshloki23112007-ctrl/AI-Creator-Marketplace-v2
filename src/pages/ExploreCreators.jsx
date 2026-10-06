import { Link, NavLink, useLocation, useNavigate } from 'react-router-dom'
import { useMemo, useState } from 'react'
import { creators } from '../data/creators'

const filters = ['All', 'AI Video', 'Runway', 'Midjourney', 'Animation', 'Graphic Design', 'Product Ads']

function ExploreCreators() {
  const navigate = useNavigate()
  const location = useLocation()
  const [selectedFilter, setSelectedFilter] = useState('All')
  const [searchTerm, setSearchTerm] = useState(location.state?.searchTerm ?? '')

  const filteredCreators = useMemo(() => {
    return creators.filter((creator) => {
      const matchesText =
        !searchTerm ||
        creator.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        creator.role.toLowerCase().includes(searchTerm.toLowerCase()) ||
        creator.skills.join(' ').toLowerCase().includes(searchTerm.toLowerCase())

      const matchesFilter =
        selectedFilter === 'All' ||
        creator.skills.some((skill) => skill.toLowerCase().includes(selectedFilter.toLowerCase())) ||
        creator.role.toLowerCase().includes(selectedFilter.toLowerCase())

      return matchesText && matchesFilter
    })
  }, [searchTerm, selectedFilter])

  const sidebarItems = [
    { label: 'Dashboard', to: '/brand/dashboard' },
    { label: 'Create Brief', to: '/brand/brief' },
    { label: 'My Briefs', to: '/brand/dashboard' },
    { label: 'Search Creators', to: '/brand/creators' },
    { label: 'Messages', to: '/brand/dashboard' },
    { label: 'Settings', to: '/brand/dashboard' },
    { label: 'Logout', to: '/' },
  ]

  const handleViewProfile = (id) => {
    navigate(`/creator/profile/${id}`)
  }

  return (
    <div className="min-h-screen bg-[#050d1d] px-4 py-6 text-white sm:px-6 lg:px-8">
      <div className="mx-auto max-w-7xl overflow-hidden rounded-[30px] border border-white/10 bg-[#071426]/80 shadow-[0_20px_65px_rgba(59,130,246,0.15)]">
        <header className="flex items-center justify-between border-b border-white/10 px-6 py-4">
          <Link to="/" className="flex items-center gap-3">
            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#3b82f6] via-[#7c3aed] to-[#ec4899] shadow-[0_0_20px_rgba(124,58,237,0.7)]">
              <span className="text-lg font-black text-white">▶</span>
            </div>
            <div>
              <div className="text-2xl font-black tracking-[-0.06em] text-white">AICreators</div>
              <div className="text-[0.58rem] uppercase tracking-[0.2em] text-slate-400">Create • Connect • Grow</div>
            </div>
          </Link>

          <div className="flex items-center gap-3">
            <div className="h-10 w-10 rounded-full bg-gradient-to-br from-[#60a5fa] via-[#8b5cf6] to-[#ec4899]" />
            <div>
              <div className="text-sm font-semibold text-white">XYZ Brand</div>
              <div className="text-xs text-slate-400">Brand</div>
            </div>
          </div>
        </header>

        <div className="grid min-h-[760px] lg:grid-cols-[260px_1fr]">
          <aside className="border-r border-white/10 bg-[#0b1322] p-5">
            <nav className="space-y-2">
              {sidebarItems.map((item) => (
                <NavLink
                  key={item.label}
                  to={item.to}
                  className={({ isActive }) =>
                    `flex w-full items-center justify-between rounded-2xl px-4 py-3 text-left text-sm font-medium transition ${
                      isActive
                        ? 'bg-gradient-to-r from-[#1d4ed8]/20 via-[#7c3aed]/20 to-[#ec4899]/20 text-white shadow-[0_0_18px_rgba(168,85,247,0.15)]'
                        : 'text-slate-300 hover:bg-white/5 hover:text-white'
                    }`
                  }
                  onClick={(event) => {
                    if (item.label === 'Logout') {
                      event.preventDefault()
                      navigate('/')
                    }
                  }}
                >
                  <span>{item.label}</span>
                </NavLink>
              ))}
            </nav>
          </aside>

          <main className="p-6 lg:p-8">
            <div className="rounded-[28px] border border-white/10 bg-[#0b1322] p-6">
              <div className="mb-6 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <div className="flex-1">
                  <div className="flex items-center gap-3 rounded-full border border-white/10 bg-[#0f1a2b] px-4 py-3">
                    <span className="text-lg text-slate-300">⌕</span>
                    <input
                      type="text"
                      placeholder="Search creators, skills, tools, or content type..."
                      value={searchTerm}
                      onChange={(event) => setSearchTerm(event.target.value)}
                      className="w-full bg-transparent text-sm text-white placeholder:text-slate-400 focus:outline-none"
                    />
                  </div>
                </div>

                <button
                  type="button"
                  className="rounded-full bg-gradient-to-r from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] px-5 py-3 text-sm font-semibold text-white shadow-[0_0_18px_rgba(168,85,247,0.4)] transition hover:brightness-110"
                >
                  Apply Filters
                </button>
              </div>

              <div className="mb-6 flex flex-wrap gap-2">
                {filters.map((filter) => (
                  <button
                    key={filter}
                    type="button"
                    onClick={() => setSelectedFilter(filter)}
                    className={`rounded-full px-3 py-2 text-sm transition ${
                      selectedFilter === filter
                        ? 'bg-gradient-to-r from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] text-white shadow-[0_0_18px_rgba(168,85,247,0.35)]'
                        : 'border border-white/10 bg-white/5 text-slate-300 hover:bg-white/10'
                    }`}
                  >
                    {filter}
                  </button>
                ))}
              </div>

              {location.state?.successMessage && (
                <div className="mb-5 rounded-2xl border border-[#60a5fa]/30 bg-[#0f1d31] px-4 py-3 text-sm text-sky-200">
                  {location.state.successMessage}
                </div>
              )}

              <div className="grid gap-5 xl:grid-cols-[260px_1fr]">
                <aside className="rounded-[24px] border border-white/10 bg-[#0d1b2a] p-5">
                  <h3 className="text-lg font-bold text-white">Filters</h3>
                  <div className="mt-4 space-y-4 text-sm text-slate-300">
                    <div>
                      <p className="mb-2 font-medium text-white">Skills</p>
                      <div className="space-y-2">
                        <label className="flex items-center gap-2"><input type="checkbox" /> AI Video</label>
                        <label className="flex items-center gap-2"><input type="checkbox" /> Animation</label>
                        <label className="flex items-center gap-2"><input type="checkbox" /> Motion Graphics</label>
                      </div>
                    </div>

                    <div>
                      <p className="mb-2 font-medium text-white">Specialization</p>
                      <div className="space-y-2">
                        <label className="flex items-center gap-2"><input type="checkbox" /> Product Ads</label>
                        <label className="flex items-center gap-2"><input type="checkbox" /> Branding</label>
                        <label className="flex items-center gap-2"><input type="checkbox" /> Social Media</label>
                      </div>
                    </div>

                    <div>
                      <p className="mb-2 font-medium text-white">Tools</p>
                      <div className="space-y-2">
                        <label className="flex items-center gap-2"><input type="checkbox" /> Runway</label>
                        <label className="flex items-center gap-2"><input type="checkbox" /> Midjourney</label>
                        <label className="flex items-center gap-2"><input type="checkbox" /> Kling</label>
                      </div>
                    </div>

                    <div>
                      <p className="mb-2 font-medium text-white">Content Type</p>
                      <div className="space-y-2">
                        <label className="flex items-center gap-2"><input type="checkbox" /> Video</label>
                        <label className="flex items-center gap-2"><input type="checkbox" /> Image</label>
                        <label className="flex items-center gap-2"><input type="checkbox" /> Animation</label>
                      </div>
                    </div>
                  </div>

                  <div className="mt-6 flex gap-3">
                    <button type="button" className="flex-1 rounded-full border border-white/10 bg-white/5 px-3 py-2 text-sm text-white">Clear All</button>
                    <button type="button" className="flex-1 rounded-full bg-gradient-to-r from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] px-3 py-2 text-sm text-white">Apply</button>
                  </div>
                </aside>

                <div className="grid gap-5 md:grid-cols-2">
                  {filteredCreators.map((creator) => (
                    <article
                      key={creator.id}
                      className="rounded-[24px] border border-white/10 bg-[#0f1d31] p-4 shadow-[0_0_20px_rgba(96,165,250,0.08)]"
                    >
                      <div className="flex items-start justify-between gap-3">
                        <div className="flex items-center gap-3">
                          <img src={creator.image} alt={creator.name} className="h-14 w-14 rounded-full object-cover" />
                          <div>
                            <div className="flex items-center gap-2">
                              <h3 className="text-lg font-bold text-white">{creator.name}</h3>
                              {creator.verified && <span className="text-sm text-[#60a5fa]">✓</span>}
                            </div>
                            <p className="text-sm text-slate-300">{creator.role}</p>
                          </div>
                        </div>
                        <button type="button" className="text-lg text-slate-300">♡</button>
                      </div>

                      <div className="mt-4 flex items-center gap-2 text-sm text-slate-300">
                        <span className="text-[#f59e0b]">★</span>
                        <span className="font-semibold text-white">{creator.rating}</span>
                        <span>({creator.reviews} reviews)</span>
                      </div>

                      <div className="mt-3 text-sm text-slate-300">{creator.projects} projects</div>

                      <div className="mt-4 flex flex-wrap gap-2">
                        {creator.skills.map((skill) => (
                          <span key={`${creator.id}-${skill}`} className="rounded-full border border-white/10 bg-white/5 px-2.5 py-1 text-[0.7rem] text-slate-200">
                            {skill}
                          </span>
                        ))}
                      </div>

                      <div className="mt-4 flex flex-wrap gap-2">
                        {creator.skills.slice(0, 2).map((tool) => (
                          <span key={`${creator.id}-tool-${tool}`} className="rounded-full border border-[#60a5fa]/30 bg-[#162845] px-2.5 py-1 text-[0.7rem] text-sky-200">
                            {tool}
                          </span>
                        ))}
                      </div>

                      <button
                        type="button"
                        onClick={() => handleViewProfile(creator.id)}
                        className="mt-5 w-full rounded-full bg-gradient-to-r from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] px-4 py-3 text-sm font-semibold text-white shadow-[0_0_18px_rgba(168,85,247,0.35)] transition hover:brightness-110"
                      >
                        View Profile
                      </button>
                    </article>
                  ))}
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
    </div>
  )
}

export default ExploreCreators
