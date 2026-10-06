function CreatorCard({ creator }) {
  const { name, role, rating, reviews, projects, skills, image, verified } = creator

  return (
    <article className="group relative overflow-hidden rounded-[26px] border border-slate-200 bg-white p-4 shadow-[0_16px_30px_rgba(15,23,42,0.07)] transition hover:-translate-y-1 hover:shadow-[0_24px_40px_rgba(15,23,42,0.12)]">
      <button
        type="button"
        aria-label="Save creator"
        className="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-lg text-slate-500 shadow-sm transition hover:text-[#7c3aed]"
      >
        ♡
      </button>

      <div className="flex items-center gap-3">
        <img
          src={image}
          alt={name}
          className="h-14 w-14 rounded-full object-cover ring-2 ring-slate-100"
        />
        <div className="min-w-0 flex-1">
          <div className="flex items-center gap-2">
            <h3 className="truncate text-lg font-bold text-slate-900">{name}</h3>
            {verified && <span className="text-sm text-[#3b82f6]">✓</span>}
          </div>
          <p className="mt-1 text-sm text-slate-500">{role}</p>
        </div>
      </div>

      <div className="mt-4 flex items-center justify-between gap-2 text-sm text-slate-600">
        <div className="flex items-center gap-1.5">
          <span className="text-[#f59e0b]">★</span>
          <span className="font-semibold text-slate-800">{rating}</span>
        </div>
        <span>({reviews} reviews)</span>
      </div>

      <div className="mt-3 flex items-center justify-between text-sm text-slate-500">
        <span>{projects} projects</span>
      </div>

      <div className="mt-4 flex flex-wrap items-center gap-2">
        {skills.map((skill) => (
          <span
            key={skill}
            className="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[0.7rem] font-medium text-slate-600"
          >
            {skill}
          </span>
        ))}
      </div>
    </article>
  )
}

export default CreatorCard