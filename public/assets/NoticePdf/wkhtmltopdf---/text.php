<?php
$file_path = 'C:\\xampp\\htdocs\\apks\\public\\assets\\NoticePdf\\html\\';
$app_no = 'notice';

$wkhtmltopdf_path = '"C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe"';
$batch_script_path = 'C:\\xampp\\htdocs\\apks\\public\\assets\\NoticePdf\\wkhtmltopdf\\bin\\test.bat';

$command = "{$wkhtmltopdf_path} {$batch_script_path} {$app_no} {$file_path}";
exec("{$command} 2>&1", $output, $return_var);

// Check if the command executed successfully
if ($return_var === 0) {
    echo "Conversion was successful.";
} else {
    echo "Conversion failed. Error output: " . implode("\n", $output);
}
?>
