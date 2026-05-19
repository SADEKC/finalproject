<?php $pageTitle = 'Sign In'; $skipContainer = true; include __DIR__ . '/../layout/header.php'; ?>

<div class="auth-split">
  <!-- LEFT: form -->
  <div class="auth-form-side">
    <div class="auth-form-wrap">
      <h1 class="auth-heading">Welcome Back!</h1>
      <p class="auth-subtitle">Sign in to access your dashboard and continue writing.</p>

      <?php if (!empty($errors['general'])): ?>
        <div class="flash error"><?= e($errors['general']) ?></div>
      <?php endif; ?>

      <form method="POST" action="<?= base_url('login') ?>">
        <div class="field">
          <label>Email</label>
          <div class="input-wrap has-icon">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
              <polyline points="22,6 12,13 2,6"/>
            </svg>
            <input type="email" name="email" value="<?= e($old['email']) ?>" placeholder="Enter your email" required autofocus>
          </div>
        </div>

        <div class="field">
          <label>Password</label>
          <div class="input-wrap has-icon has-toggle">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <input type="password" name="password" id="pw" placeholder="Enter your password" required>
            <button type="button" class="pw-toggle-icon" data-target="pw" aria-label="Toggle password">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
        </div>

        <label class="check-row">
          <input type="checkbox" name="remember" value="1"> Keep me signed in for 30 days
        </label>

        <button type="submit" class="btn btn-block">Sign In</button>
      </form>

      <div class="divider-or">OR</div>

      <p style="text-align:center;color:#6b7280;font-size:14px;">
        Don't have an account? <a href="<?= base_url('register') ?>" style="color:#134e4a;font-weight:600;">Sign Up</a>
      </p>
    </div>
  </div>

  <!-- RIGHT: dark teal panel -->
  <div class="auth-panel-side">
    <div>
      <h2>Stories worth reading.<br>Voices worth following.</h2>
      <div class="quote-block">
        <div class="quote-mark">"</div>
        <p class="quote-text">BlogPlatform gave my writing a home. It's clean, fast, and the community is incredible.</p>
        <div class="quote-author">
          <div class="quote-author-avatar">M</div>
          <div class="quote-author-info">
            <strong>Maya Chen</strong>
            <span>Senior Writer · TechWeekly</span>
          </div>
        </div>
      </div>
    </div>
    <div class="panel-footer">
      <div class="panel-footer-label">TRUSTED BY WRITERS WORLDWIDE</div>
      <div class="panel-footer-tags">
        <span>· Journalism</span>
        <span>· Tech</span>
        <span>· Essays</span>
        <span>· Tutorials</span>
        <span>· Reviews</span>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
