<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_header.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header("Location: menu.php");
    exit;
}

/* Fetch item */
$stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    die("Menu item not found.");
}

/* Update */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        UPDATE menu_items 
        SET category = ?, name = ?, price = ?, is_active = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $_POST['category'],
        $_POST['name'],
        $_POST['price'],
        isset($_POST['is_active']) ? 1 : 0,
        $id
    ]);

    header("Location: menu.php?updated=1");
    exit;
}
?>

<h1>Edit Menu Item</h1>

<form method="post" class="admin-form">
    <label>Category</label>
    <input name="category" value="<?= htmlspecialchars($item['category']) ?>" required>

    <label>Name</label>
    <input name="name" value="<?= htmlspecialchars($item['name']) ?>" required>

    <label>Price (£)</label>
    <input name="price" type="number" step="0.01" value="<?= $item['price'] ?>" required>

    <label>
        <input type="checkbox" name="is_active" <?= $item['is_active'] ? 'checked' : '' ?>>
        Active
    </label>

    <?php if ($item['image']): ?>
    <img src="<?= SITE_URL ?>/<?= $item['image'] ?>" width="120">
<?php endif; ?>

<input type="file" name="image">

    <button class="btn btn-primary">Update</button>
    <a href="menu.php" class="btn btn-outline">Cancel</a>
</form>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>