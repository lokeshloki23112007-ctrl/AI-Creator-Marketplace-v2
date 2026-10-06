import { Link, NavLink, useNavigate } from 'react-router-dom'

function BrandDashboard() {
  const navigate = useNavigate()

  const sidebarItems = [
    { label: 'Dashboard', to: '/brand/dashboard' },
    { label: 'Create Brief', to: '/brand/brief' },
    { label: 'My Briefs', to: '/brand/dashboard' },
    { label: 'Search Creators', to: '/brand/creators' },
    { label: 'Messages', to: '/brand/dashboard' },
    { label: 'Settings', to: '/brand/dashboard' },
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
            <section className="rounded-[28px] border border-white/10 bg-[radial-gradient(circle_at_top_left,rgba(96,165,250,0.14),transparent_20%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,0.20),transparent_25%)] p-6">
              <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                  <p className="text-sm uppercase tracking-[0.18em] text-slate-400">Brand dashboard</p>
                  <h1 className="mt-2 text-4xl font-black tracking-[-0.06em] text-white">Welcome, XYZ Brand! 👋</h1>
                </div>

                <div className="flex gap-3">
                  <button
                    type="button"
                    onClick={() => navigate('/brand/brief')}
                    className="rounded-full border border-[#60a5fa]/40 bg-white/5 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10"
                  >
                    Create New Brief
                  </button>
                  <button
                    type="button"
                    onClick={() => navigate('/brand/creators')}
                    className="rounded-full bg-gradient-to-r from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] px-5 py-2.5 text-sm font-semibold text-white shadow-[0_0_18px_rgba(168,85,247,0.4)] transition hover:brightness-110"
                  >
                    Search Creators
                  </button>
                </div>
              </div>
            </section>

            <section className="mt-8 grid gap-5 md:grid-cols-3">
              <div className="rounded-[26px] border border-white/10 bg-[#0b1322] p-6">
                <div className="text-sm text-slate-400">Active Briefs</div>
                <div className="mt-3 text-3xl font-black text-white">04</div>
              </div>
              <div className="rounded-[26px] border border-white/10 bg-[#0b1322] p-6">
                <div className="text-sm text-slate-400">Creators Contacted</div>
                <div className="mt-3 text-3xl font-black text-white">18</div>
              </div>
              <div className="rounded-[26px] border border-white/10 bg-[#0b1322] p-6">
                <div className="text-sm text-slate-400">Pending Reviews</div>
                <div className="mt-3 text-3xl font-black text-white">06</div>
              </div>
            </section>

            <section className="mt-8 grid gap-5 lg:grid-cols-[1.1fr_0.9fr]">
              <div className="rounded-[26px] border border-white/10 bg-[#0b1322] p-6">
                <h2 className="text-xl font-bold text-white">Quick Actions</h2>
                <div className="mt-5 grid gap-3">
                  <button
                    type="button"
                    onClick={() => navigate('/brand/brief')}
                    className="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-left text-sm font-medium text-slate-200 transition hover:bg-white/10"
                  >
                    Create Brief
                  </button>
                  <button
                    type="button"
                    onClick={() => navigate('/brand/creators')}
                    className="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-left text-sm font-medium text-slate-200 transition hover:bg-white/10"
                  >
                    Search Creators
                  </button>
                </div>
              </div>

              <div className="rounded-[26px] border border-white/10 bg-[#0b1322] p-6">
                <h2 className="text-xl font-bold text-white">Recent Briefs</h2>
                <div className="mt-5 space-y-3 text-sm text-slate-300">
                  <div className="rounded-2xl border border-white/10 bg-white/5 p-3">
                    Launch Campaign • Video • 16:9
                  </div>
                  <div className="rounded-2xl border border-white/10 bg-white/5 p-3">
                    Product Reel • Social Media • 9:16
                  </div>
                  <div className="rounded-2xl border border-white/10 bg-white/5 p-3">
                    Fashion Story • Image • 1:1
                  </div>
                </div>
              </div>
            </section>
          </main>
        </div>
      </div>
    </div>
  )
}

export default BrandDashboard
