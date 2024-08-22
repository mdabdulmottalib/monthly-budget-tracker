<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/BillReminder.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';

// Simulate user authentication
$userId = 1; // Replace with actual user ID from session or auth system

$billReminderModel = new BillReminder();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['date'], $_POST['description'])) {
        $date = $_POST['date'];
        $description = $_POST['description'];
        $billReminderModel->addReminder($userId, $date, $description);
    } elseif (isset($_POST['reminder_id'], $_POST['is_paid'])) {
        $reminderId = $_POST['reminder_id'];
        $isPaid = $_POST['is_paid'] ? 1 : 0;
        $billReminderModel->updateReminder($reminderId, $isPaid);
    }
}

// Generate current month's calendar
$currentDate = new DateTime();
$month = isset($_GET['month']) ? $_GET['month'] : $currentDate->format('m');
$year = isset($_GET['year']) ? $_GET['year'] : $currentDate->format('Y');

$startOfMonth = new DateTime("$year-$month-01");
$endOfMonth = clone $startOfMonth;
$endOfMonth->modify('last day of this month');

$startDayOfWeek = $startOfMonth->format('w');
$daysInMonth = $startOfMonth->format('t');

// Get bill reminders for the current month
$reminders = $billReminderModel->getReminders($userId, $startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d'));

$remindersByDate = [];
foreach ($reminders as $reminder) {
    $date = (new DateTime($reminder['date']))->format('j');
    $remindersByDate[$date][] = $reminder;
}
?>

<h2 class="text-xl text-center mb-5"><?php echo DateTime::createFromFormat('!m', $month)->format('F') . ' ' . $year; ?></h2>
<div class="grid grid-cols-7 gap-4 text-center">
    <div class="font-bold">Sunday</div>
    <div class="font-bold">Monday</div>
    <div class="font-bold">Tuesday</div>
    <div class="font-bold">Wednesday</div>
    <div class="font-bold">Thursday</div>
    <div class="font-bold">Friday</div>
    <div class="font-bold">Saturday</div>

    <?php for ($i = 0; $i < $startDayOfWeek; $i++): ?>
        <div></div>
    <?php endfor; ?>

    <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
        <div class="border p-2 relative">
            <div><?php echo $day; ?></div>
            <?php if (isset($remindersByDate[$day])): ?>
                <?php foreach ($remindersByDate[$day] as $reminder): ?>
                    <form method="post" class="mt-2">
                        <input type="hidden" name="reminder_id" value="<?php echo $reminder['id']; ?>">
                        <input type="hidden" name="is_paid" value="<?php echo $reminder['is_paid'] ? 0 : 1; ?>">
                        <input type="checkbox" class="checkbox" <?php echo $reminder['is_paid'] ? 'checked' : ''; ?> onclick="this.form.submit()">
                        <label class="text"><?php echo htmlspecialchars($reminder['description']); ?></label>
                    </form>
                <?php endforeach; ?>
            <?php else: ?>
                <form method="post" class="mt-2">
                    <input type="hidden" name="date" value="<?php echo "$year-$month-$day"; ?>">
                    <input type="text" name="description" placeholder="New Bill" class="border p-1 text-sm w-full">
                    <button type="submit" class="hidden"></button>
                </form>
            <?php endif; ?>
        </div>
    <?php endfor; ?>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
