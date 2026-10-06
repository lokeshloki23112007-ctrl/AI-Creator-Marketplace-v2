import { Link, NavLink, useNavigate } from 'react-router-dom'
import { useState } from 'react'

function CreatorProfile() {
  const navigate = useNavigate()
  const [form, setForm] = useState({
    fullName: 'Arun Kumar',
    bio: 'AI content creator specializing in product advertisements and short-form videos.',
    specialization: 'Product Advertisement',
    skills: 'AI Video, Animation, Motion Graphics',
  })

  const sidebarItems = [
    { label: 'Dashboard', to: '/creator/dashboard' },
    { label: 'Edit Profile', to: '/creator/profile' },
    { label: 'My Portfolio', to: '/creator/portfolio' },
    { label: 'Messages', to: '/creator/dashboard' },
    { label: 'Settings', to: '/creator/dashboard' },
    { label: 'Logout', to: '/' },
  ]

  const handleChange = (event) => {
    const { name, value } = event.target
    setForm((prev) => ({ ...prev, [name]: value }))
  }

  const handleSave = (event) => {
    event.preventDefault()

    if (!form.fullName.trim() || !form.bio.trim() || !form.specialization.trim() || !form.skills.trim()) {
      alert('Please complete all profile fields.')
      return
    }

    navigate('/creator/tools')
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
                  <h1 className="mt-2 text-4xl font-black tracking-[-0.06em] text-white">Edit Profile</h1>
                </div>
                <button
                  type="button"
                  className="rounded-full border border-[#60a5fa]/40 bg-transparent px-4 py-2 text-sm font-medium text-white transition hover:bg-white/5"
                >
                  Change Photo
                </button>
              </div>

              <form onSubmit={handleSave} className="space-y-5">
                <div className="grid gap-5 lg:grid-cols-[180px_1fr]">
                  <div className="flex items-center justify-center rounded-[24px] border border-white/10 bg-[#0f1d31] p-4">
                    <div className="flex h-28 w-28 items-center justify-center rounded-full bg-gradient-to-br from-[#60a5fa] via-[#8b5cf6] to-[#ec4899] text-2xl font-black text-white">
                      AK
                    </div>
                  </div>

                  <div className="space-y-5">
                    <div>
                      <label className="mb-2 block text-sm font-medium text-slate-200">Full Name</label>
                      <input
                        type="text"
                        name="fullName"
                        value={form.fullName}
                        onChange={handleChange}
                        className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                      />
                    </div>

                    <div>
                      <label className="mb-2 block text-sm font-medium text-slate-200">Bio</label>
                      <textarea
                        name="bio"
                        value={form.bio}
                        onChange={handleChange}
                        rows="4"
                        className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                      />
                    </div>
                  </div>
                </div>

                <div className="grid gap-5 md:grid-cols-2">
                  <div>
                    <label className="mb-2 block text-sm font-medium text-slate-200">Specialization</label>
                    <input
                      type="text"
                      name="specialization"
                      value={form.specialization}
                      onChange={handleChange}
                      className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                    />
                  </div>

                  <div>
                    <label className="mb-2 block text-sm font-medium text-slate-200">Skills</label>
                    <input
                      type="text"
                      name="skills"
                      value={form.skills}
                      onChange={handleChange}
                      className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                    />
                  </div>
                </div>

                <div className="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                  <button
                    type="button"
                    onClick={() => navigate('/creator/dashboard')}
                    className="rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                  >
                    Cancel
                  </button>

                  <button
                    type="button"
                    onClick={() => navigate('/creator/tools')}
                    className="rounded-full border border-[#60a5fa]/30 bg-[#162845] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#1c3058]"
                  >
                    Add Skill
                  </button>

                  <button
                    type="submit"
                    className="rounded-full bg-gradient-to-r from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] px-5 py-3 text-sm font-semibold text-white shadow-[0_0_18px_rgba(168,85,247,0.4)] transition hover:brightness-110"
                  >
                    Save Changes
                  </button>
                </div>
              </form>
            </div>
          </main>
        </div>
      </div>
    </div>
  )
}

export default CreatorProfile
