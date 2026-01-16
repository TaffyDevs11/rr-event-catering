<?php
// admin/dashboard.php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../includes/admin_header.php';

// Fetch stats
$totalBookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$totalClients  = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$totalMenu     = $pdo->query("SELECT COUNT(*) FROM menu")->fetchColumn();

// Fetch recent bookings
$stmt = $pdo->query("
    SELECT id, full_name, event_date, guests, status 
    FROM bookings 
    ORDER BY created_at DESC 
    LIMIT 5
");
$recentBookings = $stmt->fetchAll();

/* ===============================
   KPIs & Revenue
================================ */
$totalBookings  = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$paidBookings   = $pdo->query("SELECT COUNT(*) FROM bookings WHERE is_paid = 1")->fetchColumn();
$unpaidBookings = $totalBookings - $paidBookings;

// Calculate total revenue from paid bookings
$totalRevenue = $pdo->query("
    SELECT IFNULL(SUM(total_amount),0)
    FROM bookings
    WHERE is_paid = 1
")->fetchColumn();

/* Monthly bookings */
$monthly = $pdo->query("
    SELECT DATE_FORMAT(event_date,'%Y-%m') m, COUNT(*) total
    FROM bookings
    GROUP BY m
    ORDER BY m ASC
")->fetchAll(PDO::FETCH_ASSOC);

$topItems = $pdo->query("
    SELECT item_name, COUNT(*) total
    FROM booking_items
    GROUP BY item_name
    ORDER BY total DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="dashboard">
    <h1>Dashboard</h1>

    <!-- Stats cards -->
    <div class="stats">
        <div class="stat-card"><h2><?= $totalBookings ?></h2><p>Total Bookings</p></div>
        <div class="stat-card"><h2><?= $totalClients ?></h2><p>Registered Clients</p></div>
        <div class="stat-card"><h2><?= $totalMenu ?></h2><p>Menu Items</p></div>
    </div>

<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin_style.css">

<main class="admin-main container">

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Revenue (£)</h3>
            <p><?= number_format($totalRevenue, 2) ?></p>
        </div>
        <div class="stat-card">
            <h3>Paid</h3>
            <p><?= $paidBookings ?></p>
        </div>
        <div class="stat-card danger">
            <h3>Unpaid</h3>
            <p><?= $unpaidBookings ?></p>
        </div>
    </div>

    <section class="admin-section">
        <h2>Bookings per Month</h2>
        <table class="table">
            <tr><th>Month</th><th>Total</th></tr>
            <?php foreach ($monthly as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['m']) ?></td>
                <td><?= $m['total'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <section class="admin-section">
        <h2>Top Menu Items</h2>
        <table class="table">
            <tr><th>Item</th><th>Times Ordered</th></tr>
            <?php foreach ($topItems as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['item_name']) ?></td>
                <td><?= $item['total'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- Recent bookings -->
    <div class="table-card">
        <h2>Recent Bookings</h2>
        <?php if (empty($recentBookings)): ?>
            <p style="color:#aaa;">No bookings yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Event Date</th>
                        <th>Guests</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentBookings as $b): ?>
                        <tr>
                            <td><?= htmlspecialchars($b['full_name']) ?></td>
                            <td><?= htmlspecialchars($b['event_date']) ?></td>
                            <td><?= (int)$b['guests'] ?></td>
                            <td><span class="status <?= strtolower($b['status']) ?>"><?= ucfirst($b['status']) ?></span></td>
                            <td>
                                <a href="<?= SITE_URL ?>/admin/booking_view.php?id=<?= (int)$b['id'] ?>" class="btn">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
