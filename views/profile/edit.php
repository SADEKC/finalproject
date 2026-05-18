<?php $pageTitle = 'Edit Profile'; include __DIR__ . '/../layout/header.php'; ?>

<h1>Your profile</h1>
<p class="subtitle">Customize how others see you on the platform.</p>

<?php
  $social = $user['social_links'] ? json_decode($user['social_links'], true) : [];
  $twitter = $social['twitter'] ?? '';
  $github  = $social['github']  ?? '';
?>

<form method="POST" action="<?= base_url('profile/edit') ?>" enctype="multipart/form-data">

  <div class="field">
    <label>Profile picture</label>
    <div class="upload-row">
      <?php if ($user['profile_pic_path']): ?>
        <img id="avatar-preview" class="avatar"
             src="<?= base_url(e($user['profile_pic_path'])) ?>" alt="avatar">
      <?php else: ?>
        <div id="avatar-preview" class="avatar avatar-placeholder">
          <?= strtoupper(substr($user['name'], 0, 1)) ?>
        </div>
      <?php endif; ?>
      <div style="flex:1;">
        <input id="avatar-input" type="file" name="avatar" accept="image/jpeg,image/png">
        <small style="color:#9ca3af;font-size:12px;display:block;margin-top:4px;">JPEG or PNG · max 1 MB</small>
      </div>
    </div>
    <?php if (!empty($errors['avatar'])): ?><div class="err"><?= e($errors['avatar']) ?></div><?php endif; ?>
  </div>

  <div class="field">
    <label>Bio</label>
    <textarea name="bio" placeholder="Tell people a bit about yourself..."><?= e($user['bio']) ?></textarea>
  </div>

  <div class="field">
    <label>Twitter URL</label>
    <input type="url" name="twitter" value="<?= e($twitter) ?>" placeholder="https://twitter.com/yourname">
    <?php if (!empty($errors['twitter'])): ?><div class="err"><?= e($errors['twitter']) ?></div><?php endif; ?>
  </div>

  <div class="field">
    <label>GitHub URL</label>
    <input type="url" name="github" value="<?= e($github) ?>" placeholder="https://github.com/yourname">
    <?php if (!empty($errors['github'])): ?><div class="err"><?= e($errors['github']) ?></div><?php endif; ?>
  </div>

  <button type="submit" class="btn btn-block">Save Changes</button>
</form>

<?php include __DIR__ . '/../layout/footer.php'; ?>
