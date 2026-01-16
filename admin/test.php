<?php
// admin/calendar.php

require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../includes/admin_header.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . SITE_URL . '/admin/login.php');
    exit;
}

/* ===============================
   Month handling
================================ */
$month = isset($_GET['m']) ? (int)$_GET['m'] : date('n');
$year  = isset($_GET['y']) ? (int)$_GET['y'] : date('Y');

$start = new DateTime("$year-$month-01");
$end   = (clone $start)->modify('first day of next month');

/* ===============================
   Fetch bookings
================================ */
$stmt = $pdo->prepare("
    SELECT 
        b.id,
        b.event_date,
        b.services_json,
        b.event_address,
        c.full_name
    FROM bookings b
    LEFT JOIN clients c ON b.client_id = c.id
    WHERE b.event_date >= ? AND b.event_date < ?
    ORDER BY b.event_date ASC
");
$stmt->execute([
    $start->format('Y-m-d'),
    $end->format('Y-m-d')
]);

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===============================
   Resolve menu items (SAME AS booking_view)
================================ */
$calendarBookings = [];

foreach ($bookings as $b) {

    $menuNames = [];
    $menu_raw = json_decode($b['services_json'], true);

    if (is_array($menu_raw)) {
        $item_ids = [];
        foreach ($menu_raw as $catItems) {
            if (is_array($catItems)) {
                foreach ($catItems as $id) {
                    $item_ids[] = (int)$id;
                }
            }
        }

        if ($item_ids) {
            $placeholders = implode(',', array_fill(0, count($item_ids), '?'));
            $stmtItems = $pdo->prepare("
                SELECT item_name 
                FROM food_items 
                WHERE id IN ($placeholders)
            ");
            $stmtItems->execute($item_ids);
            foreach ($stmtItems->fetchAll(PDO::FETCH_ASSOC) as $i) {
                $menuNames[] = $i['item_name'];
            }
        }
    }

    $calendarBookings[$b['event_date']][] = [
        'id'     => $b['id'],
        'name'   => $b['full_name'],
        'venue'  => $b['event_address'],
        'menu'   => $menuNames
    ];
}

$prev = (clone $start)->modify('-1 month');
$next = (clone $start)->modify('+1 month');
?>

<main class="admin-main container">
<section class="admin-content">

<h1>Bookings Calendar — <?= $start->format('F Y') ?></h1>

<div class="calendar-header">
    <a class="btn" href="?m=<?= $prev->format('n') ?>&y=<?= $prev->format('Y') ?>">← Prev</a>
    <a class="btn" href="?m=<?= $next->format('n') ?>&y=<?= $next->format('Y') ?>">Next →</a>
</div>

<div class="calendar-weekdays">
    <div class="weekday">Mon</div><div class="weekday">Tue</div>
    <div class="weekday">Wed</div><div class="weekday">Thu</div>
    <div class="weekday">Fri</div><div class="weekday">Sat</div>
    <div class="weekday">Sun</div>
</div>

<div class="calendar-grid">
<?php
$d = clone $start;
$pad = (int)$d->format('N') - 1;
for ($i=0; $i<$pad; $i++) echo '<div class="cal-cell empty"></div>';

while ($d < $end):
    $date = $d->format('Y-m-d');
    $day  = $d->format('j');

    if (!empty($calendarBookings[$date])):
        foreach ($calendarBookings[$date] as $b):
            $menu = htmlspecialchars(implode('<br>', $b['menu'] ?: ['No menu selected']));
?>
<div class="cal-cell booked booking-cell"
     data-booking-id="<?= $b['id'] ?>">
    <div class="day"><?= $day ?></div>
    <div class="client"><?= htmlspecialchars($b['name']) ?></div>

    <!-- POPUP -->
    <div class="booking-popup">
        <strong>Menu Items</strong><br>
        <?= $menu ?>
        <hr>
        <button onclick="exportBooking(<?= $b['id'] ?>)">Download</button>
    </div>
</div>
<?php endforeach; else: ?>
<div class="cal-cell free"><div class="day"><?= $day ?></div></div>
<?php endif; $d->modify('+1 day'); endwhile; ?>
</div>

</section>
</main>

<script>
function exportBooking(id) {
    window.location.href = "<?= SITE_URL ?>/admin/export_booking.php?id=" + id;
}
</script>

<style>
/* ADDITIVE ONLY — NO EXISTING STYLES TOUCHED */

.booking-cell {
    position: relative;
}

.booking-popup {
    display:none;
    position:absolute;
    z-index:10;
    background:#fff;
    color:#000;
    border:1px solid #ccc;
    padding:10px;
    width:220px;
    top:100%;
    left:50%;
    transform:translateX(-50%);
    box-shadow:0 4px 10px rgba(0,0,0,.2);
    font-size:0.85em;
}

.booking-cell:hover .booking-popup {
    display:block;
}

.booking-popup button {
    margin-top:8px;
    padding:5px 10px;
    background:#333;
    color:#fff;
    border:none;
    cursor:pointer;
}
</style>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>