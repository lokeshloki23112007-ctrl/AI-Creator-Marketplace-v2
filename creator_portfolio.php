<?php
// creator_portfolio.php - Creator's Portfolio Gallery with 30s Ad Video Player
require_once __DIR__ . '/config/db.php';
$user = require_login('creator');

$db = get_db();
$stmt = $db->prepare("SELECT * FROM creator_profiles WHERE user_id = :uid LIMIT 1");
$stmt->execute(['uid' => $user['id']]);
$profile = $stmt->fetch();

// Handle Delete Project action
if (isset($_POST['delete_id'])) {
    $del_id = (int)$_POST['delete_id'];
    $del_stmt = $db->prepare("DELETE FROM portfolio_projects WHERE id = :id AND creator_id = :cid");
    $del_stmt->execute(['id' => $del_id, 'cid' => $profile['id']]);
    
    // Update creator project count
    $db->prepare("UPDATE creator_profiles SET projects_count = GREATEST(0, projects_count - 1) WHERE id = :cid")
       ->execute(['cid' => $profile['id']]);

    set_flash('success', 'Portfolio project deleted successfully.');
    header("Location: creator_portfolio.php");
    exit;
}

// Fetch all creator's portfolios from MySQL
$p_stmt = $db->prepare("SELECT * FROM portfolio_projects WHERE creator_id = :cid ORDER BY created_at DESC");
$p_stmt->execute(['cid' => $profile['id']]);
$projects = $p_stmt->fetchAll();

$flash = get_flash();
$avatar = !empty($user['avatar_url']) ? $user['avatar_url'] : ($profile['avatar_url'] ?? null);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Portfolio - AICreators</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body, * { font-family: 'Times New Roman', Times, serif !important; }
    body { background-color: #0A0B12; color: #F1EEFA; }
  </style>
</head>
<body class="min-h-screen bg-[#0A0B12] px-4 py-6 text-[#F1EEFA] sm:px-6 lg:px-8">

  <div class="mx-auto max-w-7xl overflow-hidden rounded-[30px] border border-[#26243E] bg-[#0E0F1B] shadow-[0_20px_60px_rgba(0,0,0,0.8)]">
    <!-- Top Header -->
    <header class="flex items-center justify-between border-b border-[#26243E] bg-[#0E0F1B]/95 px-6 py-4">
      <a href="index.php" class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] shadow-md">
          <span class="text-lg font-black text-white">▶</span>
        </div>
        <div>
          <div class="text-2xl font-black tracking-[-0.06em] text-[#F1EEFA]">AICreators</div>
          <div class="text-[0.58rem] uppercase tracking-[0.2em] text-[#C4BCE3] font-semibold">Create • Connect • Grow</div>
        </div>
      </a>

      <div class="flex items-center gap-3">
        <?php if (!empty($avatar)): ?>
          <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="h-10 w-10 rounded-full object-cover ring-2 ring-cyan-400">
        <?php else: ?>
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-sm font-bold text-white shadow-md">
            <?= strtoupper(substr($user['full_name'], 0, 2)) ?>
          </div>
        <?php endif; ?>
        <div>
          <div class="text-sm font-semibold text-[#F1EEFA]"><?= htmlspecialchars($user['full_name']) ?></div>
          <div class="text-xs text-[#A79DCB]">Creator Studio</div>
        </div>
      </div>
    </header>

    <div class="grid min-h-[760px] lg:grid-cols-[260px_1fr]">
      <!-- Left Sidebar -->
      <?php require_once __DIR__ . '/includes/creator_sidebar.php'; ?>

      <!-- Main Content -->
      <main class="p-6 lg:p-8 bg-[#0A0B12]/60">
        <div class="rounded-[28px] border border-[#26243E] bg-[#121324] p-6 lg:p-8 shadow-sm">
          
          <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <p class="text-xs uppercase tracking-[0.18em] text-[#00F5FF] font-bold">Creator Portfolio</p>
              <h1 class="mt-1 text-3xl font-black tracking-[-0.04em] text-[#F1EEFA]">My Portfolio (<?= count($projects) ?>)</h1>
            </div>
            
            <a
              href="creator_portfolio_add.php"
              class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:brightness-110"
            >
              <span>+ Add Project / 30s Ad</span>
            </a>
          </div>

          <?php if ($flash): ?>
            <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-950/40 p-4 text-sm text-emerald-300 flex items-center gap-2">
              <span class="font-bold text-emerald-400">✓</span>
              <span><?= htmlspecialchars($flash['message']) ?></span>
            </div>
          <?php endif; ?>

          <?php if (empty($projects)): ?>
            <div class="rounded-[24px] border border-[#26243E] bg-[#16172B] p-10 text-center text-[#A79DCB]">
              <span class="text-4xl">🎬</span>
              <h3 class="mt-3 text-lg font-bold text-[#F1EEFA]">No projects found in your portfolio</h3>
              <p class="mt-1 text-sm text-[#C4BCE3]">Upload your first 30-second advertisement video or visual project to showcase to brands.</p>
              <a href="creator_portfolio_add.php" class="mt-5 inline-block rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:brightness-110">
                Upload 30s Ad Video Now
              </a>
            </div>
          <?php else: ?>
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
              <?php foreach ($projects as $project): ?>
                <?php
                  $tools_arr = array_filter(array_map('trim', explode(',', $project['tools'] ?? '')));
                  $skills_arr = array_filter(array_map('trim', explode(',', $project['skills'] ?? '')));
                ?>
                <article class="overflow-hidden rounded-[24px] border border-[#26243E] bg-[#16172B] shadow-sm hover:border-[#00F5FF]/50 hover:shadow-[0_0_20px_rgba(0,245,255,0.15)] transition flex flex-col justify-between">
                  <div>
                    <!-- Video Player or Preview Thumbnail -->
                    <?php if (!empty($project['video_url'])): ?>
                      <div class="relative bg-black h-48">
                        <video controls preload="metadata" class="w-full h-full object-cover">
                          <source src="<?= htmlspecialchars($project['video_url']) ?>" type="video/mp4">
                          Your browser does not support video playback.
                        </video>
                        <div class="absolute top-2 right-2 rounded-full bg-rose-600/90 backdrop-blur-sm px-2.5 py-0.5 text-[0.65rem] font-bold text-white shadow">
                          ⚡ 30s Ad Video
                        </div>
                      </div>
                    <?php else: ?>
                      <div class="relative h-48 overflow-hidden bg-black">
                        <img src="<?= htmlspecialchars($project['preview_url']) ?>" alt="<?= htmlspecialchars($project['title']) ?>" class="h-full w-full object-cover" />
                      </div>
                    <?php endif; ?>

                    <div class="p-5">
                      <div class="mb-3 flex items-start justify-between gap-2">
                        <h3 class="text-base font-bold text-[#F1EEFA] leading-snug"><?= htmlspecialchars($project['title']) ?></h3>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                          <a
                            href="creator_portfolio_add.php?edit=<?= $project['id'] ?>"
                            title="Edit this project"
                            class="flex h-8 px-3 items-center justify-center rounded-full border border-[#00F5FF]/40 bg-[#00F5FF]/10 text-xs font-bold text-[#00F5FF] hover:bg-[#00F5FF]/25 transition"
                          >
                            ✏️ Edit
                          </a>
                          <form method="POST" action="creator_portfolio.php" onsubmit="return confirm('Are you sure you want to delete this portfolio project?');">
                            <input type="hidden" name="delete_id" value="<?= $project['id'] ?>">
                            <button
                              type="submit"
                              title="Delete project"
                              class="flex h-8 w-8 items-center justify-center rounded-full border border-rose-500/40 bg-rose-950/40 text-xs text-rose-300 hover:bg-rose-900/60 transition cursor-pointer"
                            >
                              ✕
                            </button>
                          </form>
                        </div>
                      </div>

                      <div class="mb-3 text-sm text-[#C4BCE3]">
                        <span class="font-bold text-[#00F5FF]">Content Type:</span> <?= htmlspecialchars($project['content_type']) ?>
                        <?php if ($project['video_duration'] > 0): ?>
                          <span class="ml-2 text-xs text-amber-400 font-semibold">• <?= $project['video_duration'] ?>s duration</span>
                        <?php endif; ?>
                      </div>

                      <?php if (!empty($project['description'])): ?>
                        <p class="mb-4 text-xs text-[#A79DCB] line-clamp-2"><?= htmlspecialchars($project['description']) ?></p>
                      <?php endif; ?>

                      <!-- Tools badges -->
                      <?php if (!empty($tools_arr)): ?>
                        <div class="flex flex-wrap gap-1.5 mb-2">
                          <?php foreach ($tools_arr as $tool): ?>
                            <span class="rounded-full border border-cyan-500/30 bg-cyan-950/40 px-2 py-0.5 text-[0.65rem] font-semibold text-cyan-300">
                              <?= htmlspecialchars($tool) ?>
                            </span>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>

                      <!-- Skills badges -->
                      <?php if (!empty($skills_arr)): ?>
                        <div class="flex flex-wrap gap-1.5">
                          <?php foreach ($skills_arr as $skill): ?>
                            <span class="rounded-full border border-purple-500/30 bg-purple-950/40 px-2 py-0.5 text-[0.65rem] font-semibold text-purple-300">
                              <?= htmlspecialchars($skill) ?>
                            </span>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>

                  <div class="px-5 pb-5 pt-3 border-t border-[#26243E] flex items-center justify-between text-xs text-[#A79DCB]">
                    <span>Uploaded <?= date('M d, Y', strtotime($project['created_at'])) ?></span>
                    <div class="flex items-center gap-3">
                      <?php if (!empty($project['video_url'])): ?>
                        <span class="text-rose-400 font-bold">⚡ 30s Ad Ready</span>
                      <?php endif; ?>
                      <a href="creator_portfolio_add.php?edit=<?= $project['id'] ?>" class="text-[#00F5FF] font-semibold hover:underline">
                        Edit →
                      </a>
                    </div>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

        </div>
      </main>
    </div>
  </div>

  <!-- Floating AI Assistant Chatbot -->
  <?php require_once __DIR__ . '/includes/chatbot.php'; ?>

</body>
</html>
