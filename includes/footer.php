<?php
// includes/footer.php
?>
  <!-- Footer Component -->
  <footer class="bg-[#0E0F1A] border-t border-[#26243E] px-4 pb-8 pt-10 text-[#C4BCE3] sm:px-6 lg:px-8 mt-12">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 pt-2 md:flex-row">
      <div class="text-sm text-[#A79DCB]">© <?= date('Y') ?> AICreators. All rights reserved.</div>
      <div class="flex items-center gap-6 text-sm text-[#C4BCE3]">
        <a href="about.php" class="transition hover:text-[#00F5FF]">About</a>
        <a href="privacy.php" class="transition hover:text-[#00F5FF]">Privacy</a>
        <a href="terms.php" class="transition hover:text-[#00F5FF]">Terms</a>
        <a href="support.php" class="transition hover:text-[#00F5FF]">Support</a>
      </div>
    </div>
  </footer>

  <!-- Floating AI Assistant Chatbot -->
  <?php require_once __DIR__ . '/chatbot.php'; ?>

</body>
</html>
