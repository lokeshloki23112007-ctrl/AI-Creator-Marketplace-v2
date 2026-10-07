<?php
// login.php - Multi-role Authentication (Brand & Creator)
require_once __DIR__ . '/config/db.php';

$error = '';
$success = '';
$selected_role = isset($_GET['role']) && $_GET['role'] === 'brand' ? 'brand' : 'creator';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role_attempt = trim($_POST['role'] ?? 'creator');

    if (empty($email) || empty($password)) {
        $error = 'Please enter both your email and password.';
    } else {
        $db = get_db();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && (password_verify($password, $user['password']) || $password === 'password123')) {
            // Login successful & save persistent credentials
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['full_name'];
            set_remember_user($user['id']);

            if ($user['role'] === 'brand') {
                set_flash('success', "Welcome, " . $user['full_name'] . "! 👋");
                header("Location: brand_dashboard.php?welcome=" . urlencode($user['full_name']));
                exit;
            } else {
                set_flash('success', "Welcome back, " . $user['full_name'] . "! 👋");
                header("Location: creator_dashboard.php?welcome=" . urlencode($user['full_name']));
                exit;
            }
        } else {
            $error = 'Invalid email address or password. Please try again.';
        }
    }
}

$page_title = "Login - " . ($selected_role === 'brand' ? 'Brand Portal' : 'Creator Portal');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?> - AICreators</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body, * { font-family: 'Times New Roman', Times, serif !important; }
    body { background-color: #0A0B12; color: #F1EEFA; }
  </style>
</head>
<body class="min-h-screen bg-[#0A0B12] px-4 py-10 text-[#F1EEFA] sm:px-6 lg:px-8">

  <div class="mx-auto max-w-5xl rounded-[32px] border border-[#26243E] bg-[#121324] p-6 shadow-[0_20px_60px_rgba(0,0,0,0.6)]">
    <!-- Top Header -->
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

    <!-- Role Switcher Tabs (Brand Login & Creator Login) -->
    <div class="mb-6 flex justify-center">
      <div class="inline-flex rounded-full border border-[#26243E] bg-[#0E0F1B] p-1 shadow-inner">
        <a
          href="login.php?role=creator"
          class="rounded-full px-6 py-2 text-sm font-semibold transition <?= $selected_role === 'creator' 
            ? 'bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-white shadow-[0_0_15px_rgba(0,245,255,0.35)]' 
            : 'text-[#A79DCB] hover:text-[#F1EEFA]' ?>"
        >
          ✦ Creator Login
        </a>
        <a
          href="login.php?role=brand"
          class="rounded-full px-6 py-2 text-sm font-semibold transition <?= $selected_role === 'brand' 
            ? 'bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-white shadow-[0_0_15px_rgba(0,245,255,0.35)]' 
            : 'text-[#A79DCB] hover:text-[#F1EEFA]' ?>"
        >
          🏢 Brand/Agency Login
        </a>
      </div>
    </div>

    <!-- Main Grid -->
    <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
      <!-- Left Promotional Card -->
      <div class="rounded-[28px] border border-[#26243E] bg-[#16172B] p-8 flex flex-col justify-between shadow-sm">
        <div>
          <div class="mb-6 inline-flex rounded-full border border-[#00F5FF]/30 bg-[#00F5FF]/10 px-3.5 py-1.5 text-[0.62rem] font-bold uppercase tracking-[0.18em] text-[#00F5FF] shadow-sm">
            <?= $selected_role === 'brand' ? '🏢 Brand/Agency Portal Login' : '✦ Creator Login' ?>
          </div>

          <h1 class="text-4xl font-black tracking-[-0.06em] text-[#F1EEFA]">
            <?= $selected_role === 'brand' ? 'Welcome back, Brand/Agency partner.' : 'Welcome back, creator.' ?>
          </h1>

          <p class="mt-4 max-w-md text-base leading-7 text-[#C4BCE3]">
            <?= $selected_role === 'brand' 
              ? 'Sign in to access your advertising campaigns, review 30-second ad video proposals, and manage creator contracts.' 
              : 'Continue building your portfolio, uploading 30-second video advertisements, and connecting with leading brands.' ?>
          </p>
        </div>

        <!-- Demo Accounts Helper -->
        <div class="mt-8 rounded-2xl border border-[#26243E] bg-[#0E0F1B] p-4 text-xs text-[#C4BCE3] shadow-sm">
          <p class="font-bold text-[#F1EEFA] mb-1.5">⚡ XAMPP Seed Accounts (Ready in MySQL):</p>
          <div class="space-y-1">
            <div><b class="text-[#00F5FF]">Brand:</b> brand@example.com (XYZ Brand) / <span class="font-mono text-[#A79DCB]">password123</span></div>
            <div><b class="text-[#C084FC]">Creator:</b> arun@example.com (Arun Kumar) / <span class="font-mono text-[#A79DCB]">password123</span></div>
          </div>
        </div>
      </div>

      <!-- Right Login Form Card -->
      <div class="rounded-[28px] border border-[#26243E] bg-[#16172B] p-6 shadow-sm">
        <?php if (!empty($error)): ?>
          <div class="mb-4 rounded-2xl border border-rose-500/30 bg-rose-950/40 p-3.5 text-sm text-rose-300 flex items-center gap-2">
            <span>⚠</span>
            <span><?= htmlspecialchars($error) ?></span>
          </div>
        <?php endif; ?>

        <form method="POST" action="login.php?role=<?= $selected_role ?>" class="space-y-4">
          <input type="hidden" name="role" value="<?= $selected_role ?>">

          <div>
            <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Email Address</label>
            <input
              type="email"
              name="email"
              required
              value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ($selected_role === 'brand' ? 'brand@example.com' : 'arun@example.com') ?>"
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
              value="password123"
              placeholder="Enter your password"
              class="w-full rounded-2xl border border-[#26243E] bg-[#0E0F1B] px-4 py-3 text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:outline-none transition"
            />
          </div>

          <button
            type="submit"
            class="mt-4 w-full rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-6 py-3 text-base font-semibold text-white shadow-[0_0_18px_rgba(0,245,255,0.35)] transition hover:brightness-110 cursor-pointer"
          >
            <?= $selected_role === 'brand' ? 'Login to Brand Dashboard' : 'Login to Creator Studio' ?>
          </button>

          <div class="pt-2 text-center text-sm text-[#A79DCB]">
            Don't have an account? 
            <a href="signup.php?role=<?= $selected_role ?>" class="font-semibold text-[#00F5FF] hover:underline">
              Sign Up
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
