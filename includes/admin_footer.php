    </main>
</div>
<script>
function toggleAdminDropdown() {
    const dropdown = document.getElementById('adminDropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener('click', function(e) {
    const user = document.querySelector('.admin-user');
    if (!user.contains(e.target)) {
        document.getElementById('adminDropdown').style.display = 'none';
    }
});
</script>
</body>
</html>
