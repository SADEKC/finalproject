// public/js/app.js — UI helpers

// Radio cards highlight selected
document.querySelectorAll('.radio-card').forEach(function (card) {
  var radio = card.querySelector('input[type=radio]');
  if (!radio) return;
  if (radio.checked) card.classList.add('selected');
  card.addEventListener('click', function () {
    document.querySelectorAll('.radio-card').forEach(function (c) {
      if (c.querySelector('input[name="' + radio.name + '"]')) c.classList.remove('selected');
    });
    radio.checked = true;
    card.classList.add('selected');
  });
});

// Avatar preview
var avatarInput = document.getElementById('avatar-input');
var avatarPreview = document.getElementById('avatar-preview');
if (avatarInput && avatarPreview) {
  avatarInput.addEventListener('change', function () {
    var file = this.files && this.files[0];
    if (!file) return;
    if (file.size > 1024 * 1024) { alert('Max 1 MB.'); this.value = ''; return; }
    var r = new FileReader();
    r.onload = function (e) {
      avatarPreview.innerHTML = '';
      avatarPreview.style.background = 'url(' + e.target.result + ') center/cover no-repeat';
    };
    r.readAsDataURL(file);
  });
}

// Password show/hide
document.querySelectorAll('.pw-toggle-icon').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var input = document.getElementById(btn.dataset.target);
    if (!input) return;
    if (input.type === 'password') {
      input.type = 'text';
      btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
    } else {
      input.type = 'password';
      btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
    }
  });
});
