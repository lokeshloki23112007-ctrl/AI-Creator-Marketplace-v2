import { Link } from 'react-router-dom'

const floatingCards = [
  {
    title: 'AI Image',
    color: 'from-[#38bdf8] via-[#60a5fa] to-[#2563eb]',
    position: 'left-0 top-20',
    rotate: 'rotate-[-6deg]',
    size: 'w-52 h-64',
    image:
      'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80',
  },
  {
    title: 'AI Video',
    color: 'from-[#f472b6] via-[#a855f7] to-[#7c3aed]',
    position: 'left-32 top-0',
    rotate: 'rotate-[10deg]',
    size: 'w-56 h-64',
    image:
      'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=80',
  },
  {
    title: 'AI Animation',
    color: 'from-[#f9a8d4] via-[#ec4899] to-[#7c3aed]',
    position: 'right-2 top-20',
    rotate: 'rotate-[12deg]',
    size: 'w-52 h-64',
    image:
      'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=900&q=80',
  },
  {
    title: 'AI Graphic',
    color: 'from-[#38bdf8] via-[#a78bfa] to-[#f472b6]',
    position: 'right-20 bottom-0',
    rotate: 'rotate-[-8deg]',
    size: 'w-56 h-64',
    image:
      'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80',
  },
]

function Hero() {
  return (
    <section className="relative overflow-hidden bg-[#050d1d] px-4 pb-8 pt-10 sm:px-6 lg:px-8">
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,0.18),transparent_33%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,0.18),transparent_28%)]" />

      <div className="relative mx-auto max-w-7xl">
        <div className="grid items-center gap-10 lg:grid-cols-[1.06fr_0.94fr]">
          <div className="max-w-[620px]">
            <div className="mb-6 inline-flex items-center rounded-full border border-[#72a8ff]/40 bg-[#0b1c35]/80 px-4 py-2 text-[0.68rem] font-bold uppercase tracking-[0.2em] text-[#d8e5ff] shadow-[0_0_18px_rgba(96,165,250,0.2)]">
              AI content creator marketplace
            </div>

            <h1 className="max-w-[560px] text-5xl font-black leading-[0.95] tracking-[-0.07em] text-white sm:text-6xl lg:text-[5rem]">
              Find the Right AI Creators
              <span className="block bg-gradient-to-r from-[#60a5fa] via-[#8b5cf6] to-[#f472b6] bg-clip-text text-transparent">
                for Your Next Big Idea
              </span>
            </h1>

            <p className="mt-6 max-w-[560px] text-lg leading-8 text-slate-300">
              Connect with talented AI content creators and bring your vision to life with stunning
              videos, images, animations and more.
            </p>

            <div className="mt-8 flex flex-col gap-4 sm:flex-row">
              <Link
                to="/signup"
                className="inline-flex items-center justify-center rounded-2xl border border-transparent bg-gradient-to-r from-[#8b5cf6] via-[#a855f7] to-[#ec4899] px-6 py-4 text-base font-semibold text-white shadow-[0_0_22px_rgba(168,85,247,0.45)] transition hover:brightness-110"
              >
                <span className="mr-3 text-lg">✦</span>
                I&apos;m a Creator
              </Link>

              <Link
                to="/brand/dashboard"
                className="inline-flex items-center justify-center rounded-2xl border border-[#60a5fa]/60 bg-[#071426]/70 px-6 py-4 text-base font-semibold text-white shadow-[0_0_18px_rgba(59,130,246,0.25)] transition hover:border-[#93c5fd] hover:bg-[#0d1c35]"
              >
                <span className="mr-3 text-lg">◫</span>
                I&apos;m a Brand
              </Link>
            </div>
          </div>

          <div className="relative mx-auto h-[420px] w-full max-w-[620px] lg:h-[500px]">
            <div className="absolute inset-x-0 bottom-0 top-10 mx-auto max-w-[540px] rounded-[32px] border border-[#5aa0ff]/30 bg-[radial-gradient(circle_at_center,rgba(59,130,246,0.18),transparent_45%)] blur-3xl" />

            {floatingCards.map((card) => (
              <div
                key={card.title}
                className={`absolute ${card.position} ${card.rotate} ${card.size} overflow-hidden rounded-[28px] border border-white/20 bg-slate-900 shadow-[0_0_35px_rgba(96,165,250,0.25)] backdrop-blur-sm`}
              >
                <div className={`absolute inset-0 bg-gradient-to-br ${card.color} opacity-70`} />
                <img
                  src={card.image}
                  alt={card.title}
                  className="h-full w-full object-cover opacity-90 mix-blend-luminosity"
                />
                <div className="absolute inset-0 bg-[linear-gradient(180deg,rgba(15,23,42,0.1),rgba(15,23,42,0.8))]" />
                <div className="absolute inset-x-0 bottom-0 flex items-center justify-between px-4 pb-4 pt-6">
                  <div className="flex items-center gap-2 rounded-full border border-white/20 bg-black/25 px-2.5 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.12em] text-white backdrop-blur-sm">
                    <span className="text-lg">▶</span>
                    {card.title}
                  </div>
                </div>
              </div>
            ))}

            <div className="absolute bottom-3 right-10 rotate-[18deg] rounded-[28px] border border-white/10 bg-white/5 px-4 py-2 text-[2rem] italic text-[#f1f5f9] shadow-[0_0_30px_rgba(168,85,247,0.2)] backdrop-blur-sm">
              Real Creators.
              <div className="block text-right text-[1.7rem] italic">Real Content.</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}

export default Hero