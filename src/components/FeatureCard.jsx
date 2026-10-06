function FeatureCard({ icon, title, description, accentClass }) {
  return (
    <div className="flex flex-col items-start justify-start rounded-3xl border border-white/10 bg-[#071426]/60 p-6 shadow-[0_0_18px_rgba(59,130,246,0.08)] backdrop-blur-sm">
      <div className={`mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br ${accentClass} text-xl text-white shadow-[0_0_18px_rgba(168,85,247,0.25)]`}>
        {icon}
      </div>
      <h3 className="text-[1.1rem] font-bold text-white">{title}</h3>
      <p className="mt-2 text-sm leading-6 text-slate-300">{description}</p>
    </div>
  )
}

export default FeatureCard