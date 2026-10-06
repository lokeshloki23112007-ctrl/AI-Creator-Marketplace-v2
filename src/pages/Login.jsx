import { Link, useNavigate } from 'react-router-dom'
import { useState } from 'react'

function Login() {
  const navigate = useNavigate()
  const [form, setForm] = useState({ email: '', password: '' })

  const handleChange = (event) => {
    const { name, value } = event.target
    setForm((prev) => ({ ...prev, [name]: value }))
  }

  const handleSubmit = (event) => {
    event.preventDefault()

    if (!form.email.trim() || !form.password.trim()) {
      alert('Please enter your email and password.')
      return
    }

    navigate('/creator/dashboard')
  }

  return (
    <div className="min-h-screen bg-[#050d1d] px-4 py-10 text-white sm:px-6 lg:px-8">
      <div className="mx-auto max-w-5xl rounded-[32px] border border-white/10 bg-[#071426]/80 p-6 shadow-[0_20px_60px_rgba(59,130,246,0.15)] backdrop-blur-sm">
        <div className="mb-8 flex items-center justify-between">
          <Link to="/" className="flex items-center gap-3">
            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#3b82f6] via-[#7c3aed] to-[#ec4899] shadow-[0_0_20px_rgba(124,58,237,0.7)]">
              <span className="text-lg font-black text-white">▶</span>
            </div>
            <div>
              <div className="text-2xl font-black tracking-[-0.06em] text-white">AICreators</div>
              <div className="text-[0.58rem] uppercase tracking-[0.2em] text-slate-400">Create • Connect • Grow</div>
            </div>
          </Link>

          <Link to="/" className="text-sm font-medium text-slate-300 transition hover:text-white">
            Back to Home
          </Link>
        </div>

        <div className="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
          <div className="rounded-[28px] border border-[#60a5fa]/20 bg-[radial-gradient(circle_at_top_left,rgba(96,165,250,0.16),transparent_28%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,0.18),transparent_35%)] p-8">
            <div className="mb-6 inline-flex rounded-full border border-[#7c3aed]/40 bg-[#0f1a2a] px-3 py-2 text-[0.62rem] font-bold uppercase tracking-[0.18em] text-slate-200">
              Creator Login
            </div>
            <h1 className="text-4xl font-black tracking-[-0.06em] text-white">Welcome back, creator.</h1>
            <p className="mt-4 max-w-md text-base leading-7 text-slate-300">
              Continue building your portfolio and managing your AI content opportunities.
            </p>
          </div>

          <div className="rounded-[28px] border border-white/10 bg-[#0a1220]/80 p-6 shadow-[0_0_30px_rgba(96,165,250,0.12)]">
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="mb-2 block text-sm font-medium text-slate-200">Email Address</label>
                <input
                  type="email"
                  name="email"
                  value={form.email}
                  onChange={handleChange}
                  placeholder="name@example.com"
                  className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white placeholder:text-slate-400 focus:border-[#8b5cf6] focus:outline-none"
                />
              </div>

              <div>
                <label className="mb-2 block text-sm font-medium text-slate-200">Password</label>
                <input
                  type="password"
                  name="password"
                  value={form.password}
                  onChange={handleChange}
                  placeholder="Enter your password"
                  className="w-full rounded-2xl border border-white/10 bg-[#0f1a2b] px-4 py-3 text-white placeholder:text-slate-400 focus:border-[#8b5cf6] focus:outline-none"
                />
              </div>

              <button
                type="submit"
                className="mt-4 w-full rounded-full bg-gradient-to-r from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] px-6 py-3 text-base font-semibold text-white shadow-[0_0_18px_rgba(168,85,247,0.45)] transition hover:brightness-110"
              >
                Login
              </button>

              <div className="pt-2 text-center text-sm text-slate-300">
                Don&apos;t have an account?{' '}
                <Link to="/signup" className="font-semibold text-[#8b5cf6] hover:text-[#a78bfa]">
                  Sign Up
                </Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  )
}

export default Login