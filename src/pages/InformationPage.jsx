import { Link, useLocation } from 'react-router-dom'

const pages = {
  '/about': {
    title: 'About AICreators',
    paragraphs: [
      'AICreators connects brands with independent creators who use AI tools to make videos, images, animations, and other digital content.',
      'This marketplace preview uses sample profiles and frontend-only workflows. Creator and brand information is not submitted to a backend.',
    ],
  },
  '/privacy': {
    title: 'Privacy Policy',
    paragraphs: [
      'This frontend preview does not send form entries, search terms, or profile details to a server. Information entered in forms is used only for the current browser interaction.',
      'The sample creator profiles and portfolio content shown in this preview are mock data. No account or analytics service is connected.',
    ],
  },
  '/terms': {
    title: 'Terms & Conditions',
    paragraphs: [
      'AICreators is currently a frontend demonstration. The marketplace, creator profiles, briefs, and dashboard figures are illustrative and do not represent live services or transactions.',
      'Do not use this preview to submit confidential information or rely on its sample content as a binding offer, agreement, or service commitment.',
    ],
  },
  '/support': {
    title: 'Support',
    paragraphs: [
      'Support services are not connected in this frontend preview. Account, messaging, and marketplace actions use mock navigation and data only.',
      'For help testing this project, return to the marketplace home or explore the available creator and brand screens.',
    ],
  },
}

function InformationPage() {
  const { pathname } = useLocation()
  const page = pages[pathname] ?? pages['/about']

  return (
    <main className="min-h-screen bg-[#050d1d] px-4 py-10 text-white sm:px-6 lg:px-8">
      <section className="mx-auto max-w-4xl rounded-[32px] border border-white/10 bg-[#071426]/80 p-6 shadow-[0_20px_60px_rgba(59,130,246,0.15)] sm:p-8">
        <Link to="/" className="mb-8 inline-flex items-center gap-3">
          <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#3b82f6] via-[#7c3aed] to-[#ec4899] shadow-[0_0_20px_rgba(124,58,237,0.7)]">
            <span className="text-lg font-black text-white">▶</span>
          </div>
          <div>
            <div className="text-2xl font-black tracking-[-0.06em] text-white">AICreators</div>
            <div className="text-[0.58rem] uppercase tracking-[0.2em] text-slate-400">Create • Connect • Grow</div>
          </div>
        </Link>

        <h1 className="text-4xl font-black tracking-[-0.06em] text-white">{page.title}</h1>
        <div className="mt-6 space-y-4 text-base leading-7 text-slate-300">
          {page.paragraphs.map((paragraph) => <p key={paragraph}>{paragraph}</p>)}
        </div>
        <Link to="/" className="mt-8 inline-flex rounded-full border border-white/20 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/20">
          Back to Home
        </Link>
      </section>
    </main>
  )
}

export default InformationPage