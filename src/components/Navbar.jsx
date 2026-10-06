import { Link, NavLink } from 'react-router-dom'

const navItems = [
  { label: 'Home', to: '/' },
  { label: 'Explore Creators', to: '/brand/creators' },
  { label: 'For Brands', to: '/brand/dashboard' },
  { label: 'For Creators', to: '/creator/dashboard' },
  { label: 'About', to: '/about' },
]

function Navbar() {
  return (
    <header className="sticky top-0 z-50 border-b border-white/10 bg-[#040d1d]/95 backdrop-blur-xl">
      <nav className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <div className="flex items-center gap-3">
          <div className="relative flex h-10 w-10 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-[#3b82f6] via-[#7c3aed] to-[#ec4899] shadow-[0_0_20px_rgba(124,58,237,0.7)]">
            <div className="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.45),transparent_60%)]" />
            <span className="relative text-lg font-black text-white">▶</span>
          </div>
          <div>
            <div className="text-[1.75rem] font-black leading-none tracking-[-0.06em] text-white">
              AICreators
            </div>
            <div className="mt-1 text-[0.58rem] font-medium uppercase tracking-[0.24em] text-slate-400">
              Create • Connect • Grow
            </div>
          </div>
        </div>

        <div className="hidden items-center gap-8 lg:flex">
          {navItems.map((item) => (
            item.label === 'Home' ? (
              <NavLink
                key={item.label}
                to={item.to}
                className={({ isActive }) =>
                  `relative text-sm transition duration-200 ${
                    isActive ? 'font-semibold text-white' : 'text-slate-300 hover:text-white'
                  }`
                }
              >
                {item.label}
                <span className="absolute -bottom-3 left-0 h-0.5 w-full rounded-full bg-gradient-to-r from-[#60a5fa] via-[#8b5cf6] to-[#f472b6]" />
              </NavLink>
            ) : (
              <Link
                key={item.label}
                to={item.to}
                className="relative text-sm text-slate-300 transition duration-200 hover:text-white"
              >
                {item.label}
              </Link>
            )
          ))}
        </div>

        <div className="flex items-center gap-3 sm:gap-4">
          <Link
            to="/brand/creators"
            aria-label="Search"
            className="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-lg text-white shadow-[0_0_16px_rgba(96,165,250,0.15)] transition hover:border-cyan-400/50 hover:bg-white/10"
          >
            ⌕
          </Link>

          <Link
            to="/login"
            className="rounded-full border border-[#7c3aed]/60 bg-transparent px-5 py-2 text-sm font-semibold text-white transition hover:bg-purple-500/10"
          >
            Login
          </Link>

          <Link
            to="/signup"
            className="rounded-full bg-gradient-to-r from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] px-5 py-2 text-sm font-semibold text-white shadow-[0_0_16px_rgba(168,85,247,0.6)] transition hover:brightness-110"
          >
            Sign Up
          </Link>
        </div>
      </nav>
    </header>
  )
}

export default Navbar