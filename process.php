<?php
// Function for Input Sanitization (Key Question 3)
/**
 * Sanitizes input data to prevent XSS attacks and cleans up whitespace.
 * @param string $data The raw input string.
 * @return string The sanitized string.
 */
function sanitize_input($data) {
    // 1. Remove unnecessary characters (extra space, tabs, newline)
    $data = trim($data);
    // 2. Remove backslashes
    $data = stripslashes($data);
    // 3. Convert special characters to HTML entities (critical for preventing XSS)
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Start of the HTML for the response page (Supplementary Problem)
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: "Inter", sans-serif; background-color: #f7f9fc; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">';

// Key Question 1: Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check if required fields are present
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
        
        // Sanitize the inputs
        $name = sanitize_input($_POST['name']);
        $email = sanitize_input($_POST['email']);
        $password = sanitize_input($_POST['password']);

        // --- Data Storage Simulation (Key Question 2) ---
        
        // Prepare data record
        $timestamp = date('Y-m-d H:i:s');
        // Data is stored as a simple pipe-separated record in a text file
        $record = "[$timestamp] | Name: $name | Email: $email | Password: $password\n";
        
        // Define the path for the storage file
        $file_path = 'registrations.txt';

        // Append the record to the file (FILE_APPEND appends, LOCK_EX prevents concurrent write issues)
        if (file_put_contents($file_path, $record, FILE_APPEND | LOCK_EX) !== false) {
            
            // Success response
            echo '<div class="w-full max-w-lg p-8 bg-white rounded-xl shadow-2xl border-t-4 border-green-600 text-center">';
            echo '<svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            echo '<h1 class="text-3xl font-bold text-gray-800 mt-4">Registration Successful!</h1>';
            echo '<p class="text-lg text-gray-600 mt-2">Your data has been processed and stored (in registrations.txt).</p>';
            
            // Display stored data for confirmation
            echo '<div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-left">';
            echo '<h2 class="font-semibold text-gray-700 mb-2 border-b pb-1">Submitted Details:</h2>';
            echo '<p><strong>Name:</strong> ' . $name . '</p>';
            echo '<p><strong>Email:</strong> ' . $email . '</p>';
            echo '<p><strong>Password:</strong> ' . $password . ' (Remember to hash this in a real app!)</p>';
            echo '</div>';
            
            echo '<a href="index.html" class="mt-8 inline-block py-2 px-4 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition">Go Back to Form</a>';
            echo '</div>';

        } else {
            // File writing error
            echo '<div class="w-full max-w-lg p-8 bg-white rounded-xl shadow-2xl border-t-4 border-red-600 text-center">';
            echo '<h1 class="text-3xl font-bold text-gray-800 mt-4">Submission Error!</h1>';
            echo '<p class="text-lg text-gray-600 mt-2">Could not write data to the storage file. Check file permissions on the server.</p>';
            echo '<a href="index.html" class="mt-8 inline-block py-2 px-4 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition">Try Again</a>';
            echo '</div>';
        }
        
    } else {
        // Missing fields error
        echo '<div class="w-full max-w-lg p-8 bg-white rounded-xl shadow-2xl border-t-4 border-red-600 text-center">';
        echo '<h1 class="text-3xl font-bold text-gray-800 mt-4">Invalid Submission</h1>';
        echo '<p class="text-lg text-gray-600 mt-2">Required fields are missing. Please complete the form.</p>';
        echo '<a href="index.html" class="mt-8 inline-block py-2 px-4 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition">Go Back to Form</a>';
        echo '</div>';
    }
} else {
    // If accessed directly via GET
    echo '<div class="w-full max-w-lg p-8 bg-white rounded-xl shadow-2xl border-t-4 border-yellow-600 text-center">';
    echo '<h1 class="text-3xl font-bold text-gray-800 mt-4">Direct Access Detected</h1>';
    echo '<p class="text-lg text-gray-600 mt-2">This page should only be accessed via a form submission (POST method).</p>';
    echo '<a href="index.html" class="mt-8 inline-block py-2 px-4 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition">Go to Registration Form</a>';
    echo '</div>';
}

echo '</body></html>';
?>
