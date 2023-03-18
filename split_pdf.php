<?php
// Set the path to the PDFtk binary file on your server
$pdftk_path = "/usr/local/bin/pdftk";

// Get the uploaded file
if ($_FILES["pdf_file"]["error"] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES["pdf_file"]["tmp_name"];
    $name = basename($_FILES["pdf_file"]["name"]);
    $ext = pathinfo($name, PATHINFO_EXTENSION);

    // Check if the uploaded file is a PDF file
    if (strtolower($ext) == "pdf") {
        // Get the range of pages to split
        $start_page = $_POST["start_page"];
        $end_page = $_POST["end_page"];

        // Set the output file name
        $output_file = "output.pdf";

        // Construct the command to split the PDF file
        $command = "$pdftk_path $tmp_name cat $start_page-$end_page output $output_file";

        // Execute the command
        exec($command, $output, $return_value);

        // Check if the command was executed successfully
        if ($return_value == 0) {
            // Download the output file
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $output_file . '"');
            readfile($output_file);
            unlink($output_file);
        } else {
            echo "Error splitting PDF file.";
        }
    } else {
        echo "Invalid file format. Please upload a PDF file.";
    }
} else {
    echo "Error uploading file.";
}
?>
