<?php
// creator_tools.php - Manage AI Tools & Generative Models
require_once __DIR__ . '/config/db.php';
$user = require_login('creator');

$db = get_db();
$stmt = $db->prepare("SELECT * FROM creator_profiles WHERE user_id = :uid LIMIT 1");
$stmt->execute(['uid' => $user['id']]);
$profile = $stmt->fetch();

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tools_str = trim($_POST['tools'] ?? '');
    $upd = $db->prepare("UPDATE creator_profiles SET tools = :tools WHERE user_id = :uid");
    $upd->execute(['tools' => $tools_str, 'uid' => $user['id']]);
    
    $success = 'AI Tools & Models updated successfully!';
    $stmt->execute(['uid' => $user['id']]);
    $profile = $stmt->fetch();
}

$current_tools = array_filter(array_map('trim', explode(',', $profile['tools'] ?? 'Runway, Kling, Midjourney, Adobe Firefly, ElevenLabs')));
$avatar = !empty($user['avatar_url']) ? $user['avatar_url'] : ($profile['avatar_url'] ?? null);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AI Tools & Models - AICreators</title>
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
          <div class="mb-6 flex items-center justify-between">
            <div>
              <p class="text-xs uppercase tracking-[0.18em] text-[#00F5FF] font-bold">Creator Profile</p>
              <h1 class="mt-2 text-3xl font-black tracking-[-0.04em] text-[#F1EEFA]">AI Tools &amp; Models</h1>
            </div>
            <div class="rounded-full border border-[#26243E] bg-[#16172B] px-3 py-1 text-xs font-semibold text-[#C4BCE3]">
              Setup
            </div>
          </div>

          <?php if (!empty($success)): ?>
            <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-950/40 p-4 text-sm text-emerald-300 flex items-center gap-2">
              <span class="font-bold text-emerald-400">✓</span>
              <span><?= htmlspecialchars($success) ?></span>
            </div>
          <?php endif; ?>

          <form method="POST" action="creator_tools.php" class="space-y-6">
            <div class="grid gap-6 lg:grid-cols-2">
              <!-- AI Tools Box -->
              <div class="rounded-[24px] border border-[#26243E] bg-[#16172B] p-5">
                <h2 class="text-lg font-bold text-[#F1EEFA]">Currently Selected Tools</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                  <?php foreach ($current_tools as $t): ?>
                    <span class="rounded-full border border-cyan-500/30 bg-cyan-950/40 px-3 py-1.5 text-xs font-semibold text-cyan-300">
                      <?= htmlspecialchars($t) ?>
                    </span>
                  <?php endforeach; ?>
                </div>

                <div class="mt-6">
                  <label class="mb-2 block text-sm font-bold text-[#C4BCE3]">Edit Tools (Comma-separated)</label>
                  <input
                    type="text"
                    name="tools"
                    value="<?= htmlspecialchars(implode(', ', $current_tools)) ?>"
                    placeholder="Runway, Kling, Midjourney, Adobe Firefly, ElevenLabs, Pika"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-3 text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  />
                  <p class="mt-2 text-xs text-[#A79DCB]">These tools appear as badges on your creator card and portfolio.</p>
                </div>
              </div>

              <!-- Popular Quick-Add Tools -->
              <div class="rounded-[24px] border border-[#26243E] bg-[#16172B] p-5">
                <h2 class="text-lg font-bold text-[#F1EEFA]">Popular AI Video &amp; Ad Models</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                  <?php 
                    $suggestions = ['Runway Gen-3', 'Kling AI', 'Luma Dream Machine', 'Sora', 'Midjourney v6', 'Stable Diffusion XL', 'Flux.1', 'ElevenLabs Voice', 'CapCut Pro'];
                    foreach ($suggestions as $s): 
                  ?>
                    <button
                      type="button"
                      onclick="addTool('<?= htmlspecialchars($s) ?>')"
                      class="rounded-full border border-purple-500/30 bg-purple-950/40 px-3 py-1.5 text-xs font-semibold text-purple-300 hover:bg-purple-900/60 transition cursor-pointer"
                    >
                      + <?= htmlspecialchars($s) ?>
                    </button>
                  <?php endforeach; ?>
                </div>
                <p class="mt-4 text-xs text-[#A79DCB]">Click any tool above to add it to your toolset.</p>
              </div>
            </div>

            <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:justify-end">
              <a
                href="creator_profile.php"
                class="rounded-full border border-[#26243E] bg-[#16172B] px-6 py-3 text-sm font-bold text-[#C4BCE3] transition hover:text-[#F1EEFA] text-center"
              >
                Back to Profile
              </a>
              <button
                type="submit"
                class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-7 py-3 text-sm font-bold text-white shadow-md transition hover:brightness-110 cursor-pointer text-center"
              >
                Save Tools &amp; Models
              </button>
            </div>
          </form>

        </div>
      </main>
    </div>
  </div>

  <script>
    function addTool(name) {
      const input = document.querySelector('input[name="tools"]');
      let val = input.value.trim();
      if (val.length > 0 && !val.includes(name)) {
        input.value = val + ', ' + name;
      } else if (val.length === 0) {
        input.value = name;
      }
    }
  </script>

  <!-- Floating AI Assistant Chatbot -->
  <?php require_once __DIR__ . '/includes/chatbot.php'; ?>

</body>
</html>
