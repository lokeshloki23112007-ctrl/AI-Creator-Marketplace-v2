<?php
// brand_profile.php - Brand Profile Update & Brand Logo / Profile Picture Upload
require_once __DIR__ . '/config/db.php';
$brand = require_login('brand');

$db = get_db();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['fullName'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($full_name) || empty($email)) {
        $error = 'Brand name and contact email are required.';
    } else {
        $avatar_path = $brand['avatar_url'];

        // Handle Brand Logo / Profile Picture File Upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_res = handle_file_upload(
                $_FILES['avatar'],
                'uploads/avatars',
                ['jpg', 'jpeg', 'png', 'webp', 'svg'],
                5 * 1024 * 1024
            );

            if ($upload_res['success']) {
                $avatar_path = $upload_res['path'];
            } else {
                $error = 'Logo Upload Error: ' . $upload_res['error'];
            }
        }

        if (empty($error)) {
            // Update users table
            $stmt = $db->prepare("
                UPDATE users 
                SET full_name = :fname, email = :email, bio = :bio, website = :web, avatar_url = :avatar
                WHERE id = :id
            ");
            $stmt->execute([
                'fname' => $full_name,
                'email' => $email,
                'bio' => $bio,
                'web' => $website,
                'avatar' => $avatar_path,
                'id' => $brand['id']
            ]);

            $success = 'Brand profile and logo updated successfully!';
            // Refresh brand record
            $brand = get_logged_in_user();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Brand Profile - AICreators</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body, * { font-family: 'Times New Roman', Times, serif !important; }
    body { background-color: #0A0B12; color: #F1EEFA; }
  </style>
</head>
<body class="min-h-screen bg-[#0A0B12] px-4 py-6 text-[#F1EEFA] sm:px-6 lg:px-8">

  <div class="mx-auto max-w-7xl overflow-hidden rounded-[30px] border border-[#26243E] bg-[#0E0F1B] shadow-[0_20px_65px_rgba(0,0,0,0.8)]">
    <!-- Top Header -->
    <header class="flex items-center justify-between border-b border-[#26243E] bg-[#0E0F1B]/95 px-6 py-4">
      <a href="index.php" class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] shadow-[0_0_20px_rgba(6,182,212,0.4)]">
          <span class="text-lg font-black text-white">▶</span>
        </div>
        <div>
          <div class="text-2xl font-black tracking-[-0.06em] text-[#F1EEFA]">Brand/Agency</div>
          <div class="text-[0.58rem] uppercase tracking-[0.2em] text-[#C4BCE3]">Hire • Campaigns • Scale</div>
        </div>
      </a>

      <div class="flex items-center gap-3">
        <?php if (!empty($brand['avatar_url'])): ?>
          <img src="<?= htmlspecialchars($brand['avatar_url']) ?>" alt="Logo" class="h-10 w-10 rounded-full object-cover ring-2 ring-cyan-400 shadow-sm">
        <?php else: ?>
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-sm font-bold text-white shadow-md">
            <?= strtoupper(substr($brand['full_name'], 0, 2)) ?>
          </div>
        <?php endif; ?>
        <div>
          <div class="text-sm font-semibold text-[#F1EEFA]"><?= htmlspecialchars($brand['full_name']) ?></div>
          <div class="text-xs text-[#A79DCB]">Brand Settings</div>
        </div>
      </div>
    </header>

    <div class="grid min-h-[760px] lg:grid-cols-[260px_1fr]">
      <!-- Left Sidebar -->
      <?php require_once __DIR__ . '/includes/brand_sidebar.php'; ?>

      <!-- Main Profile Form -->
      <main class="p-6 lg:p-8 bg-[#0A0B12]/60">
        <div class="rounded-[28px] border border-[#26243E] bg-[#121324] p-6 lg:p-8 shadow-sm">
          
          <div class="mb-6 flex items-center justify-between">
            <div>
              <p class="text-sm uppercase tracking-[0.18em] text-[#00F5FF] font-bold">Brand Settings</p>
              <h1 class="mt-2 text-4xl font-black tracking-[-0.06em] text-[#F1EEFA]">Edit Brand Profile &amp; Logo</h1>
            </div>
            <button
              type="button"
              onclick="document.getElementById('brand-logo-input').click()"
              class="rounded-full border border-[#26243E] bg-[#16172B] px-4 py-2 text-sm font-medium text-[#C4BCE3] transition hover:text-[#00F5FF] hover:bg-[#1e1f37] cursor-pointer shadow-sm"
            >
              🏢 Change Logo
            </button>
          </div>

          <?php if (!empty($success)): ?>
            <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-950/40 p-4 text-sm text-emerald-300 flex items-center gap-2">
              <span>✓</span>
              <span><?= htmlspecialchars($success) ?></span>
            </div>
          <?php endif; ?>

          <?php if (!empty($error)): ?>
            <div class="mb-6 rounded-2xl border border-rose-500/30 bg-rose-950/40 p-4 text-sm text-rose-300 flex items-center gap-2">
              <span>⚠</span>
              <span><?= htmlspecialchars($error) ?></span>
            </div>
          <?php endif; ?>

          <form method="POST" action="brand_profile.php" enctype="multipart/form-data" class="space-y-6">
            <!-- Hidden file input for logo -->
            <input type="file" id="brand-logo-input" name="avatar" accept="image/*" class="hidden" onchange="previewBrandLogo(event)">

            <div class="grid gap-6 lg:grid-cols-[200px_1fr]">
              <!-- Brand Logo Box -->
              <div class="flex flex-col items-center justify-center rounded-[24px] border border-[#26243E] bg-[#16172B] p-5 text-center shadow-sm">
                <div id="brand-logo-box" class="relative flex h-28 w-28 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-2xl font-black text-white shadow-md">
                  <?php if (!empty($brand['avatar_url'])): ?>
                    <img id="brand-logo-img" src="<?= htmlspecialchars($brand['avatar_url']) ?>" alt="Brand Logo" class="h-full w-full object-cover">
                  <?php else: ?>
                    <span id="brand-logo-initials"><?= strtoupper(substr($brand['full_name'], 0, 2)) ?></span>
                  <?php endif; ?>
                </div>

                <button
                  type="button"
                  onclick="document.getElementById('brand-logo-input').click()"
                  class="mt-4 rounded-full border border-[#26243E] bg-[#0E0F1B] px-3 py-1.5 text-xs font-semibold text-[#C4BCE3] transition hover:text-[#00F5FF] cursor-pointer shadow-sm"
                >
                  Upload Brand Logo
                </button>
                <p class="mt-2 text-[0.68rem] text-[#A79DCB]">PNG, JPG, SVG up to 5MB</p>
              </div>

              <!-- Brand Name & Email Fields -->
              <div class="space-y-5">
                <div>
                  <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Brand / Company Name</label>
                  <input
                    type="text"
                    name="fullName"
                    required
                    value="<?= htmlspecialchars($brand['full_name'] ?? '') ?>"
                    placeholder="e.g. Acme Corporation, Apex Athletics, XYZ Brand"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  />
                </div>

                <div>
                  <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Contact Email</label>
                  <input
                    type="email"
                    name="email"
                    required
                    value="<?= htmlspecialchars($brand['email'] ?? '') ?>"
                    placeholder="brand@company.com"
                    class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                  />
                </div>
              </div>
            </div>

            <!-- Website & Description -->
            <div class="space-y-5">
              <div>
                <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Official Website URL</label>
                <input
                  type="url"
                  name="website"
                  value="<?= htmlspecialchars($brand['website'] ?? '') ?>"
                  placeholder="https://yourbrand.com"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] focus:border-cyan-400 focus:outline-none"
                />
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Brand Bio &amp; Creative Vision</label>
                <textarea
                  name="bio"
                  rows="4"
                  placeholder="Tell creators about your brand, target audience, and types of 30-second video advertisements you frequently look for..."
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] placeholder:text-[#7A7593] focus:border-cyan-400 focus:outline-none"
                ><?= htmlspecialchars($brand['bio'] ?? '') ?></textarea>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-3 pt-4 sm:flex-row sm:justify-end">
              <a
                href="brand_dashboard.php"
                class="rounded-full border border-[#26243E] bg-[#16172B] px-6 py-3 text-sm font-semibold text-[#C4BCE3] transition hover:text-[#F1EEFA] text-center shadow-sm"
              >
                Cancel
              </a>

              <button
                type="submit"
                class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-8 py-3 text-sm font-semibold text-white shadow-[0_0_18px_rgba(6,182,212,0.35)] transition hover:brightness-110 cursor-pointer text-center"
              >
                Save Brand Profile
              </button>
            </div>
          </form>

        </div>
      </main>
    </div>
  </div>

  <script>
    function previewBrandLogo(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const box = document.getElementById('brand-logo-box');
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
