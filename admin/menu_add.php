<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
}

$error = '';
$success = '';

$imagePath = null;

if (!empty($_FILES['image']['name'])) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('menu_') . '.' . $ext;

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        __DIR__ . '/../assets/images/menu/' . $filename
    );

    $imagePath = 'assets/images/menu/' . $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category  = trim($_POST['category'] ?? '');
    $name      = trim($_POST['name'] ?? '');
    $price     = trim($_POST['price'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Basic validation
    if ($category === '' || $name === '' || $price === '') {
        $error = 'All fields except Active status are required.';
    } elseif (!is_numeric($price)) {
        $error = 'Price must be a number.';
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO menu_items (category, name, price, is_active)
                VALUES (:category, :name, :price, :is_active)
            ");

            $stmt->execute([
                ':category'  => $category,
                ':name'      => $name,
                ':price'     => $price,
                ':is_active' => $is_active
            ]);

            $success = 'Menu item added successfully!';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<div class="admin-content">
    <h1>Add Menu Item</h1>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" class="admin-form" style="max-width:500px;">
        <label>Category</label>
        <select name="category" required>
            <option value="">-- Select Category --</option>
            <option value="Meats">Meats</option>
            <option value="Greens">Greens</option>
            <option value="Starches">Starches</option>
            <option value="Salads">Salads</option>
            <option value="Desserts">Desserts</option>
        </select>

        <label>Item Name</label>
        <input type="text" name="name" placeholder="e.g. Beef Stew" required>

        <label>Price</label>
        <input type="number" step="0.01" name="price" placeholder="e.g. 15.00" required>

        <label>Image</label>
<input type="file" name="image" accept="image/*">
<form method="post" enctype="multipart/form-data">

        <label style="display:flex;gap:10px;align-items:center;">
            <input type="checkbox" name="is_active" checked>
            Active (visible to clients)
        </label>

        <button type="submit" class="btn btn-primary">Add Menu Item</button>
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>