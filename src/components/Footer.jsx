import { Link } from 'react-router-dom'

function Footer() {
  return (
    <footer className="bg-[#040d1d] px-4 pb-8 pt-10 text-slate-300 sm:px-6 lg:px-8">
      <div className="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 border-t border-white/10 pt-6 md:flex-row">
        <div className="text-sm text-slate-400">© 2026 AICreators</div>
        <div className="flex items-center gap-6 text-sm text-slate-300">
          <Link to="/privacy" className="hover:text-white">Privacy</Link>
          <Link to="/terms" className="hover:text-white">Terms</Link>
          <Link to="/support" className="hover:text-white">Support</Link>
        </div>
      </div>
    </footer>
  )
}

export default Footer