<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_header.php';

/* Toggle active status */
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];

    $pdo->prepare("
        UPDATE menu_items 
        SET is_active = IF(is_active = 1, 0, 1)
        WHERE id = ?
    ")->execute([$id]);

    header("Location: menu.php");
    exit;
}

/* Delete menu item */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $pdo->prepare("DELETE FROM menu_items WHERE id = ?")->execute([$id]);

    header("Location: menu.php");
    exit;
}

/* Fetch menu items */
$stmt = $pdo->query("
    SELECT * FROM menu_items
    ORDER BY category ASC, created_at DESC
");
$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-content">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <h1>Menu Items</h1>
        <a href="menu_add.php" class="btn btn-primary">+ Add Menu Item</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Name</th>
                <th>Price (£)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

        <?php if (!$menuItems): ?>
            <tr>
                <td colspan="6" style="text-align:center;">No menu items found.</td>
            </tr>
        <?php endif; ?>

        <?php foreach ($menuItems as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['category']) ?></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= number_format($item['price'], 2) ?></td>
                <td>
                    <?= $item['is_active'] ? '<span style="color:lime;">Active</span>' : '<span style="color:red;">Hidden</span>' ?>
                </td>
                <td>
                    <a href="?toggle=<?= $item['id'] ?>" class="btn btn-outline">
                        <?= $item['is_active'] ? 'Deactivate' : 'Activate' ?>
                    </a>

                    <a href="?delete=<?= $item['id'] ?>"
                       onclick="return confirm('Delete this item permanently?')"
                       class="btn btn-danger">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>