<?php $pageTitle = 'Sign Up'; $skipContainer = true; include __DIR__ . '/../layout/header.php'; ?>

<div class="auth-split">
  <!-- LEFT: form -->
  <div class="auth-form-side">
    <div class="auth-form-wrap">
      <h1 class="auth-heading">Create your account</h1>
      <p class="auth-subtitle">Join thousands of writers and readers on BlogPlatform.</p>

      <form method="POST" action="<?= base_url('register') ?>">
        <div class="field">
          <label>Full name</label>
          <div class="input-wrap has-icon">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
            <input type="text" name="name" value="<?= e($old['name']) ?>" placeholder="Jane Doe" required>
          </div>
          <?php if (!empty($errors['name'])): ?><div class="err"><?= e($errors['name']) ?></div><?php endif; ?>
        </div>

        <div class="field">
          <label>Email</label>
          <div class="input-wrap has-icon">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
              <polyline points="22,6 12,13 2,6"/>
            </svg>
            <input type="email" name="email" value="<?= e($old['email']) ?>" placeholder="jane@example.com" required>
          </div>
          <?php if (!empty($errors['email'])): ?><div class="err"><?= e($errors['email']) ?></div><?php endif; ?>
        </div>

        <div class="field">
          <label>Password <span style="color:#9ca3af;font-weight:400;font-size:12px;">(min. 8 characters)</span></label>
          <div class="input-wrap has-icon has-toggle">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <input type="password" name="password" id="pw" placeholder="Enter a strong password" required>
            <button type="button" class="pw-toggle-icon" data-target="pw" aria-label="Toggle password">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
          <?php if (!empty($errors['password'])): ?><div class="err"><?= e($errors['password']) ?></div><?php endif; ?>
        </div>

        <div class="field">
          <label>Account type</label>
          <div class="radio-row">
            <label class="radio-card">
              <input type="radio" name="role" value="reader" <?= $old['role']==='reader' ? 'checked' : '' ?>>
              <strong>Reader</strong>
              <small>Read, like, comment</small>
            </label>
            <label class="radio-card">
              <input type="radio" name="role" value="author" <?= $old['role']==='author' ? 'checked' : '' ?>>
              <strong>Author</strong>
              <small>Needs admin approval</small>
            </label>
          </div>
          <?php if (!empty($errors['role'])): ?><div class="err"><?= e($errors['role']) ?></div><?php endif; ?>
        </div>

        <button type="submit" class="btn btn-block" style="margin-top:8px;">Create Account</button>
      </form>

      <div class="divider-or">OR</div>

      <p style="text-align:center;color:#6b7280;font-size:14px;">
        Already have an account? <a href="<?= base_url('login') ?>" style="color:#134e4a;font-weight:600;">Sign In</a>
      </p>
    </div>
  </div>

  <!-- RIGHT: dark teal panel -->
  <div class="auth-panel-side">
    <div>
      <h2>Start writing today.<br>Reach a global audience.</h2>
      <div class="quote-block">
        <div class="quote-mark">"</div>
        <p class="quote-text">The publishing process is so smooth. I went from draft to thousands of readers in one afternoon.</p>
        <div class="quote-author">
          <div class="quote-author-avatar">R</div>
          <div class="quote-author-info">
            <strong>Rahim Ahmed</strong>
            <span>Independent Writer · Dhaka</span>
          </div>
        </div>
      </div>
    </div>
    <div class="panel-footer">
      <div class="panel-footer-label">JOIN 10,000+ WRITERS</div>
      <div class="panel-footer-tags">
        <span>· Free forever</span>
        <span>· No ads</span>
        <span>· Your content, your rules</span>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
