// admin.js — small helpers for admin pages
document.addEventListener('DOMContentLoaded', function(){
  // simple table search enhancement or other future admin interactions
  document.querySelectorAll('.admin-table .btn-danger').forEach(b => {
    b.addEventListener('click', function(e){
      if (!confirm('Are you sure? This action cannot be undone.')) e.preventDefault();
    });
  });
});
