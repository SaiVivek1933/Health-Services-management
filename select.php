<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the specialist is selected
    if (isset($_POST['specialist'])) {
        $specialist = $_POST['specialist'];

        $specialist_pages = array(
            'pulmonologist' => 'pulmonologist.php',
            'cardiologist' => 'cardiologist.php',
            'neurologist' => 'neurologist.php',
            'dermatologist' => 'dermatologist.php',
            'dentist' => 'dentist.php',
            'nephrologist' => 'nephrologist.php',
            'general physician' => 'general_physician.php'
            // Add more specialists and their respective pages as needed
        );

        // Check if the selected specialist exists in the array
        if (isset($specialist_pages[$specialist])) {
            // Redirect to the corresponding page
            $page = $specialist_pages[$specialist];
            header("Location: $page");
            exit(); // Exit script to prevent further execution
        } else {
            // If the selected specialist does not exist in the array, redirect to a default page or display an error message
            header("Location: default_page.php");
            exit(); // Exit script to prevent further execution
        }
    } else {
        // If the specialist is not selected, redirect back to the form page
        header("Location: form_page.php");
        exit(); // Exit script to prevent further execution
    }
}
