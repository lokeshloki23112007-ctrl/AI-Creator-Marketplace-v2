import { Link } from 'react-router-dom'
import Navbar from '../components/Navbar'
import Hero from '../components/Hero'
import SearchBar from '../components/SearchBar'
import FeatureCard from '../components/FeatureCard'
import CreatorCard from '../components/CreatorCard'
import Footer from '../components/Footer'
import { creators } from '../data/creators'

const featureItems = [
  {
    icon: '✓',
    title: 'Verified Creators',
    description: 'Work with trusted & verified AI professionals',
    accentClass: 'from-[#60a5fa] via-[#7c3aed] to-[#ec4899]',
  },
  {
    icon: '▣',
    title: 'Detailed Portfolios',
    description: 'Explore past work, skills, tools and more',
    accentClass: 'from-[#38bdf8] via-[#3b82f6] to-[#8b5cf6]',
  },
  {
    icon: '⌕',
    title: 'Smart Search & Filters',
    description: 'Find the perfect creator for your needs',
    accentClass: 'from-[#22d3ee] via-[#60a5fa] to-[#8b5cf6]',
  },
  {
    icon: '⚡',
    title: 'Easy Collaboration',
    description: 'Connect, discuss and bring your ideas to life',
    accentClass: 'from-[#f59e0b] via-[#8b5cf6] to-[#ec4899]',
  },
]

function Home() {
  return (
    <div className="min-h-screen bg-[#050d1d] text-white">
      <Navbar />

      <main>
        <Hero />
        <SearchBar />

        <section className="bg-[#050d1d] px-4 py-8 sm:px-6 lg:px-8">
          <div className="mx-auto grid max-w-7xl gap-5 md:grid-cols-2 xl:grid-cols-4">
            {featureItems.map((item, index) => (
              <div key={item.title} className={index < featureItems.length - 1 ? 'xl:border-r xl:border-white/10 xl:pr-5' : ''}>
                <FeatureCard {...item} />
              </div>
            ))}
          </div>
        </section>

        <section className="bg-[#050d1d] px-4 pb-12 pt-6 sm:px-6 lg:px-8">
          <div className="mx-auto grid max-w-7xl gap-6 lg:grid-cols-2">
            <div className="relative overflow-hidden rounded-[28px] border border-[#8b5cf6]/30 bg-gradient-to-br from-[#4c1d95] via-[#312e81] to-[#1d4ed8] p-8 shadow-[0_0_28px_rgba(168,85,247,0.25)]">
              <div className="mb-2 text-sm font-bold uppercase tracking-[0.2em] text-purple-200">For creators</div>
              <h3 className="text-4xl font-black tracking-[-0.06em] text-white">Showcase Your AI Skills</h3>
              <p className="mt-4 max-w-md text-base text-slate-200">
                Build your profile, upload your portfolio, and get discovered by brands looking for unique talent.
              </p>
              <Link to="/signup" className="mt-8 inline-flex rounded-full border border-white/20 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20">
                Join as Creator →
              </Link>
            </div>

            <div className="relative overflow-hidden rounded-[28px] border border-[#60a5fa]/30 bg-gradient-to-br from-[#0b1329] via-[#0f172a] to-[#1d4ed8] p-8 shadow-[0_0_28px_rgba(59,130,246,0.25)]">
              <div className="mb-2 text-sm font-bold uppercase tracking-[0.2em] text-blue-200">For brands</div>
              <h3 className="text-4xl font-black tracking-[-0.06em] text-white">Find the Perfect Creator</h3>
              <p className="mt-4 max-w-md text-base text-slate-200">
                Post your content requirements, search and filter creators, and bring your ideas to life.
              </p>
              <Link to="/brand/dashboard" className="mt-8 inline-flex rounded-full border border-white/20 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20">
                Join as Brand →
              </Link>
            </div>
          </div>
        </section>

        <section className="bg-[#edf2f7] px-4 pb-14 pt-12 sm:px-6 lg:px-8">
          <div className="mx-auto max-w-7xl">
            <div className="mb-8 flex items-end justify-between gap-4">
              <div>
                <h2 className="text-4xl font-black tracking-[-0.06em] text-slate-900">Featured Creators</h2>
                <p className="mt-2 text-base text-slate-500">Discover top AI creators with diverse skills and unique styles.</p>
              </div>
              <Link to="/brand/creators" className="text-base font-semibold text-slate-700 transition hover:text-slate-900">
                View All →
              </Link>
            </div>

            <div className="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
              {creators.map((creator) => (
                <CreatorCard key={creator.id} creator={creator} />
              ))}
            </div>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  )
}

export default Home