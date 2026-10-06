import { Link, NavLink, useLocation, useNavigate } from 'react-router-dom'
import { useEffect, useState } from 'react'

const sampleProjects = [
  {
    id: 1,
    title: 'AI Shoe Advertisement',
    contentType: 'Video',
    tools: ['Runway', 'Kling'],
    skills: ['AI Video', 'Advertisement'],
    preview:
      'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80',
  },
  {
    id: 2,
    title: 'AI Fashion Campaign',
    contentType: 'Image',
    tools: ['Midjourney', 'Adobe Firefly'],
    skills: ['AI Image', 'Branding'],
    preview:
      'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80',
  },
  {
    id: 3,
    title: 'Food Product Ad',
    contentType: 'Video',
    tools: ['Runway', 'ElevenLabs'],
    skills: ['AI Video', 'Social Ads'],
    preview:
      'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80',
  },
]

function Portfolio() {
  const navigate = useNavigate()
  const location = useLocation()
  const [projects, setProjects] = useState(sampleProjects)

  useEffect(() => {
    const newProject = location.state?.newProject
    if (newProject) {
      setProjects((prev) => [newProject, ...prev])
    }
  }, [location.state])

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
                  <p className="text-sm uppercase tracking-[0.18em] text-slate-400">Creator portfolio</p>
                  <h1 className="mt-2 text-4xl font-black tracking-[-0.06em] text-white">My Portfolio</h1>
                </div>
                <button
                  type="button"
                  onClick={() => navigate('/creator/portfolio/add')}
                  className="rounded-full bg-gradient-to-r from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] px-5 py-2.5 text-sm font-semibold text-white shadow-[0_0_18px_rgba(168,85,247,0.4)] transition hover:brightness-110"
                >
                  Add Project
                </button>
              </div>

              {location.state?.newProject && (
                <div className="mb-5 rounded-2xl border border-[#60a5fa]/30 bg-[#0f1d31] px-4 py-3 text-sm text-sky-200">
                  Portfolio project added successfully.
                </div>
              )}

              <div className="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                {projects.map((project) => (
                  <article
                    key={project.id}
                    className="overflow-hidden rounded-[24px] border border-white/10 bg-[#0f1d31] shadow-[0_0_18px_rgba(96,165,250,0.08)]"
                  >
                    <img src={project.preview} alt={project.title} className="h-44 w-full object-cover" />
                    <div className="p-4">
                      <div className="mb-3 flex items-center justify-between gap-2">
                        <h3 className="text-lg font-bold text-white">{project.title}</h3>
                        <button
                          type="button"
                          aria-label="Edit portfolio item"
                          className="flex h-8 w-8 items-center justify-center rounded-full border border-white/10 bg-white/5 text-sm text-slate-200"
                        >
                          ✎
                        </button>
                      </div>

                      <div className="mb-3 text-sm text-slate-300">
                        <span className="font-medium text-slate-200">Content Type:</span> {project.contentType}
                      </div>

                      <div className="flex flex-wrap gap-2">
                        {project.tools.map((tool) => (
                          <span
                            key={`${project.id}-${tool}`}
                            className="rounded-full border border-[#60a5fa]/30 bg-[#162845] px-2 py-1 text-[0.65rem] font-medium text-sky-200"
                          >
                            {tool}
                          </span>
                        ))}
                      </div>

                      <div className="mt-3 flex flex-wrap gap-2">
                        {project.skills.map((skill) => (
                          <span
                            key={`${project.id}-${skill}`}
                            className="rounded-full border border-[#8b5cf6]/30 bg-[#1a1835] px-2 py-1 text-[0.65rem] font-medium text-purple-200"
                          >
                            {skill}
                          </span>
                        ))}
                      </div>
                    </div>
                  </article>
                ))}
              </div>
            </div>
          </main>
        </div>
      </div>
    </div>
  )
}

export default Portfolio
