<?php
// signup.php - Registration for Creators & Brands
require_once __DIR__ . '/config/db.php';

$error = '';
$selected_role = isset($_GET['role']) && $_GET['role'] === 'brand' ? 'brand' : 'creator';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['fullName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirmPassword'] ?? '');
    $role = in_array($_POST['role'] ?? '', ['creator', 'brand']) ? $_POST['role'] : 'creator';

    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        $db = get_db();
        // Check if email already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $error = 'An account with this email address already exists. Please login instead.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("
                INSERT INTO users (email, password, full_name, role) 
                VALUES (:email, :password, :full_name, :role)
            ");
            $stmt->execute([
                'email' => $email,
                'password' => $hashed_password,
                'full_name' => $full_name,
                'role' => $role,
            ]);
            $user_id = $db->lastInsertId();

            if ($role === 'creator') {
                $p_stmt = $db->prepare("
                    INSERT INTO creator_profiles (user_id, role_title, bio, specialization, skills, tools, rating, reviews_count, projects_count, hourly_rate, location)
                    VALUES (:uid, 'AI Content Creator', 'AI creator specializing in short-form video advertisements and digital content.', 'AI Advertisements', 'AI Video, Runway, Kling', 'Runway, Kling', 5.0, 0, 0, '$75/hr', 'Remote')
                ");
                $p_stmt->execute(['uid' => $user_id]);
            }

            // Auto-login & save persistent credentials (so login credentials are only required once on registration)
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_role'] = $role;
            $_SESSION['user_name'] = $full_name;
            set_remember_user($user_id);

            if ($role === 'brand') {
                set_flash('success', "Welcome, {$full_name}! Your brand account has been created.");
                header("Location: brand_dashboard.php?welcome=" . urlencode($full_name));
            } else {
                set_flash('success', "Welcome, {$full_name}! Your creator account has been created.");
                header("Location: creator_dashboard.php?welcome=" . urlencode($full_name));
            }
            exit;
        }
    }
}

$page_title = "Sign Up - Join AICreators";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body, * { font-family: 'Times New Roman', Times, serif !important; }
    body { background-color: #0A0B12; color: #F1EEFA; }
  </style>
</head>
<body class="min-h-screen bg-[#0A0B12] px-4 py-10 text-[#F1EEFA] sm:px-6 lg:px-8">

  <div class="mx-auto max-w-5xl rounded-[32px] border border-[#26243E] bg-[#121324] p-6 shadow-[0_20px_60px_rgba(0,0,0,0.6)]">
    <div class="mb-8 flex items-center justify-between">
      <a href="index.php" class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] shadow-[0_0_20px_rgba(0,245,255,0.4)]">
          <span class="text-lg font-black text-white">▶</span>
        </div>
        <div>
          <div class="text-2xl font-black tracking-[-0.06em] text-[#F1EEFA]"><?= $selected_role === 'brand' ? 'Brand/Agency' : 'AICreators' ?></div>
          <div class="text-[0.58rem] uppercase tracking-[0.2em] text-[#A79DCB]"><?= $selected_role === 'brand' ? 'Hire • Campaigns • Scale' : 'Create • Connect • Grow' ?></div>
        </div>
      </a>

      <a href="index.php" class="text-sm font-medium text-[#C4BCE3] transition hover:text-[#00F5FF]">
        Back to Home
      </a>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
      <!-- Left side info -->
      <div class="rounded-[28px] border border-[#26243E] bg-[#16172B] p-8 flex flex-col justify-between shadow-sm">
        <div>
          <div class="mb-6 inline-flex rounded-full border border-[#00F5FF]/30 bg-[#00F5FF]/10 px-3.5 py-1.5 text-[0.62rem] font-bold uppercase tracking-[0.18em] text-[#00F5FF] shadow-sm">
            Account Registration
          </div>
          <h1 class="text-4xl font-black tracking-[-0.06em] text-[#F1EEFA]">Join the next wave of AI creators &amp; brands.</h1>
          <p class="mt-4 max-w-md text-base leading-7 text-[#C4BCE3]">
            Build your portfolio with 30-second video advertisements, showcase your AI tools, or hire world-class AI creators for your brand campaigns.
          </p>
        </div>

        <div class="mt-8 rounded-2xl border border-[#26243E] bg-[#0E0F1B] p-4 text-xs text-[#C4BCE3] shadow-sm">
          <p class="font-bold text-[#F1EEFA]">✓ Full MySQL Database Storage</p>
          <p class="mt-1 text-[#A79DCB]">Easily update your profile and avatar anytime in your dashboard.</p>
        </div>
      </div>

      <!-- Right Form -->
      <div class="rounded-[28px] border border-[#26243E] bg-[#16172B] p-6 shadow-sm">
        <?php if (!empty($error)): ?>
          <div class="mb-4 rounded-2xl border border-rose-500/30 bg-rose-950/40 p-3.5 text-sm text-rose-300 flex items-center gap-2">
            <span>⚠</span>
            <span><?= htmlspecialchars($error) ?></span>
          </div>
        <?php endif; ?>

        <form method="POST" action="signup.php" class="space-y-4">
          <div>
            <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">I am joining as a:</label>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center gap-2 rounded-2xl border border-[#26243E] bg-[#0E0F1B] p-3 text-sm cursor-pointer hover:border-[#00F5FF] shadow-sm transition">
                <input type="radio" name="role" value="creator" <?= $selected_role === 'creator' ? 'checked' : '' ?> class="accent-[#00F5FF]">
                <span class="font-semibold text-[#F1EEFA]">AI Creator</span>
              </label>
              <label class="flex items-center gap-2 rounded-2xl border border-[#26243E] bg-[#0E0F1B] p-3 text-sm cursor-pointer hover:border-[#8B5CF6] shadow-sm transition">
                <input type="radio" name="role" value="brand" <?= $selected_role === 'brand' ? 'checked' : '' ?> class="accent-[#8B5CF6]">
                <span class="font-semibold text-[#F1EEFA]">Brand / Agency</span>
              </label>
            </div>
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Full Name / Brand Name</label>
            <input
              type="text"
              name="fullName"
              required
              value="<?= htmlspecialchars($_POST['fullName'] ?? '') ?>"
              placeholder="e.g. Arun Kumar or Acme Studios"
              class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-3 text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:outline-none transition"
            />
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Email Address</label>
            <input
              type="email"
              name="email"
              required
              value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
              placeholder="name@example.com"
              class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-3 text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:outline-none transition"
            />
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Password</label>
            <input
              type="password"
              name="password"
              required
              placeholder="Choose a secure password"
              class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-3 text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:outline-none transition"
            />
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Confirm Password</label>
            <input
              type="password"
              name="confirmPassword"
              required
              placeholder="Confirm your password"
              class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-3 text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:outline-none transition"
            />
          </div>

          <button
            type="submit"
            class="mt-4 w-full rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-6 py-3 text-base font-semibold text-white shadow-[0_0_18px_rgba(0,245,255,0.35)] transition hover:brightness-110 cursor-pointer"
          >
            Create My Account
          </button>

          <div class="pt-2 text-center text-sm text-[#A79DCB]">
            Already have an account? 
            <a href="login.php" class="font-semibold text-[#00F5FF] hover:underline">
              Login
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Floating AI Assistant Chatbot -->
  <?php require_once __DIR__ . '/includes/chatbot.php'; ?>

</body>
</html>
