<?php $pageTitle = e($author['name']) . " · Profile"; include __DIR__ . '/../layout/header.php'; ?>

<div class="profile-header">
  <?php if ($author['profile_pic_path']): ?>
    <img class="avatar" src="<?= base_url(e($author['profile_pic_path'])) ?>" alt="avatar">
  <?php else: ?>
    <div class="avatar avatar-placeholder"><?= strtoupper(substr($author['name'], 0, 1)) ?></div>
  <?php endif; ?>
  <div>
    <h1 style="margin-bottom:6px;"><?= e($author['name']) ?></h1>
    <span class="badge badge-<?= e($author['role']) ?>"><?= e($author['role']) ?></span>
    <?php if (!empty($author['bio'])): ?>
      <p style="margin-top:12px;color:#4b5563;font-size:14px;"><?= nl2br(e($author['bio'])) ?></p>
    <?php endif; ?>
  </div>
</div>

<?php if (!empty($social['twitter']) || !empty($social['github'])): ?>
  <h2>Connect</h2>
  <div class="social-links">
    <?php if (!empty($social['twitter'])): ?>
      <a class="social-link" href="<?= e($social['twitter']) ?>" target="_blank" rel="noopener">𝕏 Twitter</a>
    <?php endif; ?>
    <?php if (!empty($social['github'])): ?>
      <a class="social-link" href="<?= e($social['github']) ?>" target="_blank" rel="noopener">⚡ GitHub</a>
    <?php endif; ?>
  </div>
<?php endif; ?>

<h2>Published articles</h2>
<?php if (empty($articles)): ?>
  <div class="empty">No published articles yet.</div>
<?php else: ?>
  <?php foreach ($articles as $a): ?>
    <div class="article-card">
      <strong><?= e($a['title']) ?></strong>
      <div class="meta"><?= date('M j, Y', strtotime($a['created_at'])) ?></div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>
