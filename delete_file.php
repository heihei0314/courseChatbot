<?php
// delete_file.php
if (isset($_POST['filename'])) {
    $filename = $_POST['filename'];
    // Sanitize the filename to prevent directory traversal
    $filename = basename($filename);
    $file_path = __DIR__ . '/voice/' . $filename; // Adjust path as needed

    // Check if file exists and is writable
    if (file_exists($file_path) && is_writable($file_path)) {
        if (unlink($file_path)) {
            echo "File $filename deleted successfully.";
        } else {
            echo "Error: Unable to delete $filename.";
        }
    } else {
        echo "Error: File $filename does not exist or is not writable.";
    }
} else {
    echo "Error: No filename provided.";
}
?>