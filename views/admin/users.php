<?php $wideContainer = true; $pageTitle = 'Admin · Users'; include __DIR__ . '/../layout/header.php'; ?>

<h1>User management</h1>
<p class="subtitle">
  Promote pending registrations to author.
  <strong style="color:#0f3a3a;"><?= count(array_filter($users, fn($u) => $u['pending_author'])) ?></strong> awaiting approval.
</p>

<table>
  <thead>
    <tr>
      <th>#</th><th>User</th><th>Email</th><th>Role</th><th>Status</th><th></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($users as $u): ?>
    <tr data-user-id="<?= (int)$u['id'] ?>">
      <td><?= (int)$u['id'] ?></td>
      <td><strong><?= e($u['name']) ?></strong></td>
      <td style="color:#6b7280;"><?= e($u['email']) ?></td>
      <td class="role-cell"><span class="badge badge-<?= e($u['role']) ?>"><?= e($u['role']) ?></span></td>
      <td class="pending-cell">
        <?php if ($u['pending_author']): ?>
          <span class="badge badge-pending">Pending</span>
        <?php else: ?>
          <span style="color:#9ca3af;">—</span>
        <?php endif; ?>
      </td>
      <td class="action-cell">
        <?php if ($u['role'] === 'reader'): ?>
          <button class="btn btn-sm promote-btn" type="button">Promote</button>
        <?php else: ?>
          <span style="color:#9ca3af;">—</span>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<script>
document.querySelectorAll('.promote-btn').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var row = btn.closest('tr');
    var userId = row.dataset.userId;
    var originalText = btn.textContent;

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Promoting';

    var fd = new FormData();
    fd.append('user_id', userId);

    fetch('<?= base_url('api/users/promote') ?>', { method: 'POST', body: fd })
      .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
      .then(function (res) {
        if (!res.ok) {
          alert('Error: ' + (res.j.error || 'unknown'));
          btn.disabled = false;
          btn.textContent = originalText;
          return;
        }
        row.querySelector('.role-cell').innerHTML =
          '<span class="badge badge-author">author</span>';
        row.querySelector('.pending-cell').innerHTML =
          '<span style="color:#9ca3af;">—</span>';
        row.querySelector('.action-cell').innerHTML =
          '<span style="color:#14b8a6;font-weight:600;">✓ Promoted</span>';
        row.style.background = '#f0fdfa';
        setTimeout(function () { row.style.background = ''; }, 1500);
      })
      .catch(function (err) {
        alert('Request failed: ' + err);
        btn.disabled = false;
        btn.textContent = originalText;
      });
  });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
