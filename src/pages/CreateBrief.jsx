import { Link, NavLink, useNavigate } from 'react-router-dom'
import { useState } from 'react'

const defaultForm = {
  campaignName: 'Summer Launch Campaign',
  description: 'We need a cinematic promotional video for our new product launch with a premium, modern feel.',
  contentType: 'Video',
  style: 'Cinematic',
  platform: 'Instagram',
  format: '16:9',
  commercialUse: 'Yes',
  reference: 'https://example.com/reference-board',
}

const contentOptions = ['Video', 'Image', 'Animation', 'Graphic Design', 'Social Media']
const styleOptions = ['Cinematic', 'Minimal', 'Luxury', 'Fun', 'Professional']
const platformOptions = ['Instagram', 'YouTube', 'TikTok', 'Website', 'Other']
const formatOptions = ['16:9', '9:16', '1:1', '4:5']
const commercialOptions = ['Yes', 'No']

function CreateBrief() {
  const navigate = useNavigate()
  const [form, setForm] = useState(defaultForm)

  const sidebarItems = [
    { label: 'Dashboard', to: '/brand/dashboard' },
    { label: 'Create Brief', to: '/brand/brief' },
    { label: 'My Briefs', to: '/brand/dashboard' },
    { label: 'Search Creators', to: '/brand/creators' },
    { label: 'Messages', to: '/brand/dashboard' },
    { label: 'Settings', to: '/brand/dashboard' },
    { label: 'Logout', to: '/' },
  ]

  const handleChange = (event) => {
    const { name, value } = event.target
    setForm((prev) => ({ ...prev, [name]: value }))
  }

  const handleSubmit = (event) => {
    event.preventDefault()

    const requiredFields = [
      form.campaignName,
      form.description,
      form.contentType,
      form.style,
      form.platform,
      form.format,
      form.commercialUse,
    ]

    if (requiredFields.some((value) => !value || !String(value).trim())) {
      alert('Please complete all required brief fields.')
      return
    }

    navigate('/brand/creators', {
      state: {
        successMessage: 'Brief created successfully. Discovering creators for your campaign.',
      },
    })
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
              <div className="mb-6 flex items-center justify-between">
                <div>
                  <p className="text-sm uppercase tracking-[0.18em] text-slate-400">Brand brief</p>
                  <h1 className="mt-2 text-4xl font-black tracking-[-0.06em] text-white">Create Brief</h1>
                </div>
              </div>

              <form onSubmit={handleSubmit} className="space-y-5">
                <div className="grid gap-5 md:grid-cols-2">
                  <div>
                    <label className="mb-2 block text-sm font-medium text-slate-200">Campaign Name</label>
                    <input
                      type="text"
                      name="campaignName"
                      value={form.campaignName}
                      onChange={handleChange}
                      className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                    />
                  </div>

                  <div>
                    <label className="mb-2 block text-sm font-medium text-slate-200">Content Type</label>
                    <select
                      name="contentType"
                      value={form.contentType}
                      onChange={handleChange}
                      className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                    >
                      {contentOptions.map((option) => (
                        <option key={option} value={option} className="bg-[#0f1a2b] text-white">
                          {option}
                        </option>
                      ))}
                    </select>
                  </div>
                </div>

                <div>
                  <label className="mb-2 block text-sm font-medium text-slate-200">Requirements / Description</label>
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
                    <label className="mb-2 block text-sm font-medium text-slate-200">Style</label>
                    <select
                      name="style"
                      value={form.style}
                      onChange={handleChange}
                      className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                    >
                      {styleOptions.map((option) => (
                        <option key={option} value={option} className="bg-[#0f1a2b] text-white">
                          {option}
                        </option>
                      ))}
                    </select>
                  </div>

                  <div>
                    <label className="mb-2 block text-sm font-medium text-slate-200">Platform</label>
                    <select
                      name="platform"
                      value={form.platform}
                      onChange={handleChange}
                      className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                    >
                      {platformOptions.map((option) => (
                        <option key={option} value={option} className="bg-[#0f1a2b] text-white">
                          {option}
                        </option>
                      ))}
                    </select>
                  </div>
                </div>

                <div className="grid gap-5 md:grid-cols-3">
                  <div>
                    <label className="mb-2 block text-sm font-medium text-slate-200">Format / Aspect Ratio</label>
                    <select
                      name="format"
                      value={form.format}
                      onChange={handleChange}
                      className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                    >
                      {formatOptions.map((option) => (
                        <option key={option} value={option} className="bg-[#0f1a2b] text-white">
                          {option}
                        </option>
                      ))}
                    </select>
                  </div>

                  <div>
                    <label className="mb-2 block text-sm font-medium text-slate-200">Commercial Use</label>
                    <select
                      name="commercialUse"
                      value={form.commercialUse}
                      onChange={handleChange}
                      className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                    >
                      {commercialOptions.map((option) => (
                        <option key={option} value={option} className="bg-[#0f1a2b] text-white">
                          {option}
                        </option>
                      ))}
                    </select>
                  </div>

                  <div>
                    <label className="mb-2 block text-sm font-medium text-slate-200">Reference / Preview</label>
                    <input
                      type="text"
                      name="reference"
                      value={form.reference}
                      onChange={handleChange}
                      className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white focus:border-[#8b5cf6] focus:outline-none"
                    />
                  </div>
                </div>

                <div className="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                  <button
                    type="button"
                    onClick={() => navigate('/brand/dashboard')}
                    className="rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    className="rounded-full bg-gradient-to-r from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] px-5 py-3 text-sm font-semibold text-white shadow-[0_0_18px_rgba(168,85,247,0.4)] transition hover:brightness-110"
                  >
                    Create Brief
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

export default CreateBrief
