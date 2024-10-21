<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submission and return dates from the form
    $submission_date = $_POST['submission_date'];
    $return_date = $_POST['return_date'];

    // Convert the dates to DateTime objects
    $submission_date = new DateTime($submission_date);
    $return_date = new DateTime($return_date);

    // Calculate the difference in days
    $interval = $submission_date->diff($return_date);
    $days_late = $interval->days;

    // Calculate the fine based on the number of days late
    $fine = 0;

    if ($days_late > 0 && $days_late <= 7) {
        $fine = $days_late * 1;  // Rs. 1 per day for 0-7 days
    } elseif ($days_late > 7 && $days_late <= 14) {
        $fine = (7 * 1) + (($days_late - 7) * 2);  // Rs. 2 per day for 8-14 days
    } elseif ($days_late > 14) {
        $fine = (7 * 1) + (7 * 2) + (($days_late - 14) * 3);  // Rs. 3 per day for more than 14 days
    }

    // Display the result
    echo "Days Late: " . $days_late . "<br>";
    echo "Fine Amount: Rs. " . $fine;
}
?>
