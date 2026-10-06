import { Link, NavLink, useNavigate } from 'react-router-dom'
import { useState } from 'react'

const defaultForm = {
  title: 'AI Shoe Advertisement',
  contentType: 'Video',
  description: '15-second product advertisement created for a sports shoe campaign.',
  tools: 'Runway, Kling',
  skills: 'AI Video, Advertisement',
  preview: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80',
}

function AddPortfolioProject() {
  const navigate = useNavigate()
  const [form, setForm] = useState(defaultForm)

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

  const handleSubmit = (event) => {
    event.preventDefault()

    const { title, contentType, description, tools, skills, preview } = form

    if (!title.trim() || !contentType.trim() || !description.trim() || !tools.trim() || !skills.trim() || !preview.trim()) {
      alert('Please complete all required fields before adding the project.')
      return
    }

    const newProject = {
      id: Date.now(),
      title: title.trim(),
      contentType: contentType.trim(),
      description: description.trim(),
      tools: tools.split(',').map((item) => item.trim()).filter(Boolean),
      skills: skills.split(',').map((item) => item.trim()).filter(Boolean),
      preview: preview.trim(),
    }

    navigate('/creator/portfolio', { state: { newProject } })
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
                  <p className="text-sm uppercase tracking-[0.18em] text-slate-400">Portfolio</p>
                  <h1 className="mt-2 text-4xl font-black tracking-[-0.06em] text-white">Add Portfolio Project</h1>
                </div>
              </div>

              <form onSubmit={handleSubmit} className="space-y-5">
                <div className="grid gap-5 md:grid-cols-2">
                  <div>
                    <label className="mb-2 block text-sm font-medium text-slate-200">Project Title</label>
                    <input
                      type="text"
                      name="title"
                      value={form.title}
                      onChange={handleChange}
                      className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                    />
                  </div>

                  <div>
                    <label className="mb-2 block text-sm font-medium text-slate-200">Content Type</label>
                    <input
                      type="text"
                      name="contentType"
                      value={form.contentType}
                      onChange={handleChange}
                      className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                    />
                  </div>
                </div>

                <div>
                  <label className="mb-2 block text-sm font-medium text-slate-200">Description</label>
                  <textarea
                    name="description"
                    value={form.description}
                    onChange={handleChange}
                    rows="4"
                    className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                  />
                </div>

                <div className="grid gap-5 md:grid-cols-2">
                  <div>
                    <label className="mb-2 block text-sm font-medium text-slate-200">Tools Used</label>
                    <input
                      type="text"
                      name="tools"
                      value={form.tools}
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

                <div>
                  <label className="mb-2 block text-sm font-medium text-slate-200">Project Preview / Image</label>
                  <input
                    type="text"
                    name="preview"
                    value={form.preview}
                    onChange={handleChange}
                    className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                  />
                </div>

                <div className="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                  <button
                    type="button"
                    onClick={() => navigate('/creator/portfolio')}
                    className="rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    className="rounded-full bg-gradient-to-r from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] px-5 py-3 text-sm font-semibold text-white shadow-[0_0_18px_rgba(168,85,247,0.4)] transition hover:brightness-110"
                  >
                    Add Project
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

export default AddPortfolioProject
