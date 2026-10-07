<?php
// creator_profile.php - Update Creator Profile & All Brand Filter Attributes
require_once __DIR__ . '/config/db.php';
$user = require_login('creator');

$db = get_db();
$stmt = $db->prepare("SELECT * FROM creator_profiles WHERE user_id = :uid LIMIT 1");
$stmt->execute(['uid' => $user['id']]);
$profile = $stmt->fetch();

$error = '';
$success = '';

$specializations_list = [
    'AI Filmmaker',
    'AI Video Creator',
    'AI Animator',
    'AI Artist',
    'Generative Designer',
    'AI Motion Designer',
    'Product Visualization Artist',
    'Advertising Creative'
];

$budget_tiers_list = [
    'Under ₹10,000',
    '₹10,000–₹25,000',
    '₹25,000–₹50,000',
    '₹50,000–₹1,00,000',
    '₹1,00,000+'
];

$experience_levels_list = [
    'Beginner',
    'Intermediate',
    'Advanced',
    'Expert'
];

$availability_list = [
    'Available Now',
    'Available This Week',
    'Currently Booked'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['fullName'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $specialization = trim($_POST['specialization'] ?? 'AI Video Creator');
    $skills = trim($_POST['skills'] ?? '');
    $tools = trim($_POST['tools'] ?? '');
    $content_types = trim($_POST['content_types'] ?? 'Video, Advertisement');
    $aspect_ratios = trim($_POST['aspect_ratios'] ?? '16:9, 9:16');
    $budget_tier = trim($_POST['budget_tier'] ?? '₹25,000–₹50,000');
    $price_num = (int)($_POST['price_num'] ?? 25000);
    $experience_level = trim($_POST['experience_level'] ?? 'Advanced');
    $availability = trim($_POST['availability'] ?? 'Available Now');
    $commercial_rights = trim($_POST['commercial_rights'] ?? 'Commercial Use, Paid Advertising');
    $role_title = trim($_POST['role_title'] ?? $specialization);
    $hourly_rate = trim($_POST['hourly_rate'] ?? '₹25,000 / ad');
    $location = trim($_POST['location'] ?? 'Remote');

    if (empty($full_name)) {
        $error = 'Full name is required.';
    } else {
        $avatar_path = $user['avatar_url'];

        // Handle Profile Picture File Upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_res = handle_file_upload(
                $_FILES['avatar'],
                'uploads/avatars',
                ['jpg', 'jpeg', 'png', 'webp', 'gif'],
                5 * 1024 * 1024 // 5MB limit
            );

            if ($upload_res['success']) {
                $avatar_path = $upload_res['path'];
            } else {
                $error = 'Avatar Upload Error: ' . $upload_res['error'];
            }
        }

        if (empty($error)) {
            // Update users table
            $u_stmt = $db->prepare("UPDATE users SET full_name = :fname, avatar_url = :avatar WHERE id = :id");
            $u_stmt->execute([
                'fname' => $full_name,
                'avatar' => $avatar_path,
                'id' => $user['id']
            ]);

            // Update creator_profiles table
            $cp_stmt = $db->prepare("
                UPDATE creator_profiles 
                SET role_title = :role_title, bio = :bio, specialization = :spec, 
                    skills = :skills, tools = :tools, content_types = :ct, aspect_ratios = :ar,
                    budget_tier = :bt, price_num = :pnum, experience_level = :exp, availability = :av,
                    commercial_rights = :cr, hourly_rate = :rate, location = :loc, avatar_url = :avatar
                WHERE user_id = :uid
            ");
            $cp_stmt->execute([
                'role_title' => $role_title,
                'bio' => $bio,
                'spec' => $specialization,
                'skills' => $skills,
                'tools' => $tools,
                'ct' => $content_types,
                'ar' => $aspect_ratios,
                'bt' => $budget_tier,
                'pnum' => $price_num,
                'exp' => $experience_level,
                'av' => $availability,
                'cr' => $commercial_rights,
                'rate' => $hourly_rate,
                'loc' => $location,
                'avatar' => $avatar_path,
                'uid' => $user['id']
            ]);

            $success = 'Profile and brand filter tags updated successfully!';
            // Refresh user and profile
            $user = get_logged_in_user();
            $stmt->execute(['uid' => $user['id']]);
            $profile = $stmt->fetch();
        }
    }
}

$avatar = !empty($user['avatar_url']) ? $user['avatar_url'] : ($profile['avatar_url'] ?? null);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Creator Profile &amp; Filter Tags - AICreators</title>
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

      <!-- Main Profile Edit Form -->
      <main class="p-6 lg:p-8 bg-[#0A0B12]/60">
        <div class="rounded-[28px] border border-[#26243E] bg-[#121324] p-6 lg:p-8 shadow-sm">
          
          <div class="mb-6 flex items-center justify-between">
            <div>
              <p class="text-xs uppercase tracking-[0.18em] text-[#00F5FF] font-bold">Creator Profile &amp; Filter Settings</p>
              <h1 class="mt-2 text-3xl font-black tracking-[-0.04em] text-[#F1EEFA]">Edit Profile &amp; Brand Filter Attributes</h1>
              <p class="mt-1 text-xs text-[#C4BCE3]">Configure how your profile appears in Brand search and discovery filters.</p>
            </div>
            <button
              type="button"
              onclick="document.getElementById('avatar-file-input').click()"
              class="rounded-full border border-[#26243E] bg-[#16172B] px-4 py-2 text-sm font-semibold text-[#C4BCE3] transition hover:text-[#00F5FF] hover:bg-[#1e1f37] cursor-pointer shadow-sm"
            >
              📷 Change Photo
            </button>
          </div>

          <?php if (!empty($success)): ?>
            <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-950/40 p-4 text-sm text-emerald-300 flex items-center gap-2">
              <span class="font-bold text-emerald-400">✓</span>
              <span><?= htmlspecialchars($success) ?></span>
            </div>
          <?php endif; ?>

          <?php if (!empty($error)): ?>
            <div class="mb-6 rounded-2xl border border-rose-500/30 bg-rose-950/40 p-4 text-sm text-rose-300 flex items-center gap-2">
              <span class="font-bold text-rose-400">⚠</span>
              <span><?= htmlspecialchars($error) ?></span>
            </div>
          <?php endif; ?>

          <form method="POST" action="creator_profile.php" enctype="multipart/form-data" class="space-y-6">
            <!-- Hidden file input for avatar -->
            <input type="file" id="avatar-file-input" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">

            <!-- Basic Info & Photo -->
            <div class="grid gap-6 lg:grid-cols-[200px_1fr]">
              <!-- Avatar Display Box -->
              <div class="flex flex-col items-center justify-center rounded-[24px] border border-[#26243E] bg-[#16172B] p-5 text-center">
                <div id="avatar-preview-box" class="relative flex h-28 w-28 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-2xl font-black text-white shadow-md">
                  <?php if (!empty($avatar)): ?>
                    <img id="avatar-preview-img" src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="h-full w-full object-cover">
                  <?php else: ?>
                    <span id="avatar-preview-initials"><?= strtoupper(substr($user['full_name'], 0, 2)) ?></span>
                  <?php endif; ?>
                </div>

                <button
                  type="button"
                  onclick="document.getElementById('avatar-file-input').click()"
                  class="mt-4 rounded-full border border-[#26243E] bg-[#0E0F1B] px-3 py-1.5 text-xs font-semibold text-[#C4BCE3] transition hover:text-[#00F5FF] cursor-pointer shadow-sm"
                >
                  Upload Photo
                </button>
                <p class="mt-2 text-[0.68rem] text-[#A79DCB]">JPG, PNG, WebP up to 5MB</p>
              </div>

              <!-- Name & Bio Fields -->
              <div class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                  <div>
                    <label class="mb-1.5 block text-xs font-bold text-[#C4BCE3]">Full Name *</label>
                    <input
                      type="text"
                      name="fullName"
                      required
                      value="<?= htmlspecialchars($user['full_name'] ?? '') ?>"
                      class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                    />
                  </div>

                  <div>
                    <label class="mb-1.5 block text-xs font-bold text-[#C4BCE3]">Role Title</label>
                    <input
                      type="text"
                      name="role_title"
                      value="<?= htmlspecialchars($profile['role_title'] ?? 'AI Creator') ?>"
                      placeholder="e.g. AI Video Creator, 30s Ad Specialist"
                      class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                    />
                  </div>
                </div>

                <div>
                  <label class="mb-1.5 block text-xs font-bold text-[#C4BCE3]">Bio</label>
                  <textarea
                    name="bio"
                    rows="2"
                    placeholder="Describe your AI content creation specialties and 30-second ad experience..."
                    class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-2.5 text-sm text-[#F1EEFA] placeholder:text-[#7A7593] focus:border-cyan-400 focus:outline-none"
                  ><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
                </div>
              </div>
            </div>

            <!-- Brand Filter Attributes Section -->
            <div class="rounded-2xl border border-[#26243E] bg-[#16172B]/60 p-5 space-y-5">
              <h3 class="text-sm font-bold uppercase tracking-wider text-[#00F5FF]">Brand Discovery &amp; Filter Settings</h3>

              <!-- Specialization & Experience Level -->
              <div class="grid gap-4 md:grid-cols-2">
                <div>
                  <label class="mb-1.5 block text-xs font-bold text-purple-400">1. Specialization</label>
                  <select
                    name="specialization"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  >
                    <?php foreach ($specializations_list as $spec): ?>
                      <option value="<?= htmlspecialchars($spec) ?>" class="bg-[#121324]" <?= (($profile['specialization'] ?? '') === $spec) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($spec) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div>
                  <label class="mb-1.5 block text-xs font-bold text-cyan-400">7. Experience Level</label>
                  <select
                    name="experience_level"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  >
                    <?php foreach ($experience_levels_list as $exp): ?>
                      <option value="<?= htmlspecialchars($exp) ?>" class="bg-[#121324]" <?= (($profile['experience_level'] ?? 'Advanced') === $exp) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($exp) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <!-- Skills & AI Tools -->
              <div class="grid gap-4 md:grid-cols-2">
                <div>
                  <label class="mb-1.5 block text-xs font-bold text-cyan-400">2. Skills (Comma-separated)</label>
                  <input
                    type="text"
                    name="skills"
                    value="<?= htmlspecialchars($profile['skills'] ?? 'AI Video, Prompt Engineering, Video Editing, Advertising') ?>"
                    placeholder="e.g. AI Video, AI Animation, Prompt Engineering, Motion Graphics"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  />
                </div>

                <div>
                  <label class="mb-1.5 block text-xs font-bold text-emerald-400">3. AI Tools / Models (Comma-separated)</label>
                  <input
                    type="text"
                    name="tools"
                    value="<?= htmlspecialchars($profile['tools'] ?? 'Runway, Kling, Midjourney, ElevenLabs') ?>"
                    placeholder="e.g. Runway, Kling, Midjourney, Sora, Flux, ComfyUI"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  />
                </div>
              </div>

              <!-- Content Types & Aspect Ratios -->
              <div class="grid gap-4 md:grid-cols-2">
                <div>
                  <label class="mb-1.5 block text-xs font-bold text-pink-400">4. Content Types (Comma-separated)</label>
                  <input
                    type="text"
                    name="content_types"
                    value="<?= htmlspecialchars($profile['content_types'] ?? 'Video, Advertisement, Product Video') ?>"
                    placeholder="e.g. Video, Image, Animation, Advertisement, Social Media, 3D"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  />
                </div>

                <div>
                  <label class="mb-1.5 block text-xs font-bold text-amber-400">5. Aspect Ratios / Formats</label>
                  <input
                    type="text"
                    name="aspect_ratios"
                    value="<?= htmlspecialchars($profile['aspect_ratios'] ?? '16:9, 9:16') ?>"
                    placeholder="e.g. 16:9, 9:16, 1:1, 4:5"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  />
                </div>
              </div>

              <!-- Budget Tier & Price Num & Hourly Rate -->
              <div class="grid gap-4 md:grid-cols-3">
                <div>
                  <label class="mb-1.5 block text-xs font-bold text-emerald-400">6. Budget Tier (INR)</label>
                  <select
                    name="budget_tier"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  >
                    <?php foreach ($budget_tiers_list as $bt): ?>
                      <option value="<?= htmlspecialchars($bt) ?>" class="bg-[#121324]" <?= (($profile['budget_tier'] ?? '₹25,000–₹50,000') === $bt) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($bt) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div>
                  <label class="mb-1.5 block text-xs font-bold text-emerald-400">Exact Price (INR ₹)</label>
                  <input
                    type="number"
                    name="price_num"
                    value="<?= (int)($profile['price_num'] ?? 25000) ?>"
                    placeholder="25000"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  />
                </div>

                <div>
                  <label class="mb-1.5 block text-xs font-bold text-emerald-400">Display Rate</label>
                  <input
                    type="text"
                    name="hourly_rate"
                    value="<?= htmlspecialchars($profile['hourly_rate'] ?? '₹25,000 / ad') ?>"
                    placeholder="e.g. ₹25,000 / ad or ₹1,500/hr"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  />
                </div>
              </div>

              <!-- Availability & Commercial Rights -->
              <div class="grid gap-4 md:grid-cols-2">
                <div>
                  <label class="mb-1.5 block text-xs font-bold text-emerald-400">8. Availability</label>
                  <select
                    name="availability"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  >
                    <?php foreach ($availability_list as $av): ?>
                      <option value="<?= htmlspecialchars($av) ?>" class="bg-[#121324]" <?= (($profile['availability'] ?? 'Available Now') === $av) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($av) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div>
                  <label class="mb-1.5 block text-xs font-bold text-violet-400">9. Commercial Rights</label>
                  <input
                    type="text"
                    name="commercial_rights"
                    value="<?= htmlspecialchars($profile['commercial_rights'] ?? 'Commercial Use, Paid Advertising') ?>"
                    placeholder="e.g. Commercial Use, Paid Advertising, Exclusive Rights, Licensing Available"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  />
                </div>
              </div>

              <div>
                <label class="mb-1.5 block text-xs font-bold text-[#C4BCE3]">Location</label>
                <input
                  type="text"
                  name="location"
                  value="<?= htmlspecialchars($profile['location'] ?? 'Remote') ?>"
                  placeholder="e.g. Remote, Mumbai, Bangalore, Delhi"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                />
              </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
              <a
                href="creator_dashboard.php"
                class="rounded-full border border-[#26243E] bg-[#16172B] px-6 py-2.5 text-xs font-bold text-[#C4BCE3] transition hover:text-[#F1EEFA] text-center"
              >
                Cancel
              </a>

              <a
                href="creator_portfolio_add.php"
                class="rounded-full border border-cyan-500/30 bg-cyan-950/40 px-6 py-2.5 text-xs font-bold text-cyan-300 transition hover:bg-cyan-900/60 text-center"
              >
                Upload 30s Ad Video →
              </a>

              <button
                type="submit"
                class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-7 py-2.5 text-xs font-bold text-white shadow-md transition hover:brightness-110 cursor-pointer text-center"
              >
                Save Profile &amp; Filter Settings
              </button>
            </div>
          </form>

        </div>
      </main>
    </div>
  </div>

  <script>
    function previewAvatar(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const box = document.getElementById('avatar-preview-box');
          box.innerHTML = `<img src="${e.target.result}" class="h-full w-full object-cover">`;
        }
        reader.readAsDataURL(file);
      }
    }
  </script>

  <!-- Floating AI Assistant Chatbot -->
  <?php require_once __DIR__ . '/includes/chatbot.php'; ?>

</body>
</html>
