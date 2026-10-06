import { Link, NavLink, useNavigate } from 'react-router-dom'

const tools = ['Runway', 'Kling', 'Midjourney', 'Adobe Firefly', 'ElevenLabs']
const models = ['Stable Diffusion', 'SDXL', 'DALL-E']

function CreatorTools() {
  const navigate = useNavigate()

  const sidebarItems = [
    { label: 'Dashboard', to: '/creator/dashboard' },
    { label: 'Edit Profile', to: '/creator/profile' },
    { label: 'My Portfolio', to: '/creator/portfolio' },
    { label: 'Messages', to: '/creator/dashboard' },
    { label: 'Settings', to: '/creator/dashboard' },
    { label: 'Logout', to: '/' },
  ]

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
              <div className="text-sm font-semibold text-white">Arun Kumar</div>
              <div className="text-xs text-slate-400">Creator</div>
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
              <div className="mb-6 flex items-center justify-between">
                <div>
                  <p className="text-sm uppercase tracking-[0.18em] text-slate-400">Creator profile</p>
                  <h1 className="mt-2 text-4xl font-black tracking-[-0.06em] text-white">AI Tools &amp; Models</h1>
                </div>
                <div className="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-medium text-slate-300">
                  Setup
                </div>
              </div>

              <div className="grid gap-6 lg:grid-cols-2">
                <div className="rounded-[24px] border border-white/10 bg-[#0f1d31] p-5">
                  <h2 className="text-xl font-bold text-white">AI Tools Used</h2>
                  <div className="mt-4 flex flex-wrap gap-2">
                    {tools.map((tool) => (
                      <span
                        key={tool}
                        className="rounded-full border border-[#60a5fa]/40 bg-[#162845] px-3 py-2 text-sm text-slate-200"
                      >
                        {tool}
                      </span>
                    ))}
                  </div>
                  <button
                    type="button"
                    className="mt-5 rounded-full border border-[#60a5fa]/40 bg-transparent px-4 py-2 text-sm font-medium text-white transition hover:bg-[#162845]"
                  >
                    Add Tool
                  </button>
                </div>

                <div className="rounded-[24px] border border-white/10 bg-[#0f1d31] p-5">
                  <h2 className="text-xl font-bold text-white">Models Used</h2>
                  <div className="mt-4 flex flex-wrap gap-2">
                    {models.map((model) => (
                      <span
                        key={model}
                        className="rounded-full border border-[#8b5cf6]/40 bg-[#1a1835] px-3 py-2 text-sm text-slate-200"
                      >
                        {model}
                      </span>
                    ))}
                  </div>
                  <button
                    type="button"
                    className="mt-5 rounded-full border border-[#8b5cf6]/40 bg-transparent px-4 py-2 text-sm font-medium text-white transition hover:bg-[#1a1835]"
                  >
                    Add Model
                  </button>
                </div>
              </div>

              <div className="mt-7 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button
                  type="button"
                  onClick={() => navigate('/creator/profile')}
                  className="rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                >
                  Back
                </button>
                <button
                  type="button"
                  onClick={() => navigate('/creator/portfolio')}
                  className="rounded-full bg-gradient-to-r from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] px-5 py-3 text-sm font-semibold text-white shadow-[0_0_18px_rgba(168,85,247,0.4)] transition hover:brightness-110"
                >
                  Save &amp; Continue
                </button>
              </div>
            </div>
          </main>
        </div>
      </div>
    </div>
  )
}

export default CreatorTools
