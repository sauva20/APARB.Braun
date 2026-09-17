<?php
$file = 'lang/en.json';
$data = json_decode(file_get_contents($file), true);

$newTranslations = [
    "Apar" => "PFE",
    "APAR" => "PFE",
    "Inspeksi" => "Inspection",
    "Jadwal Inspeksi" => "Inspection Schedule",
    "User" => "User",
    "APAR telah di-created" => "PFE has been created",
    "APAR telah di-updated" => "PFE has been updated",
    "APAR telah di-deleted" => "PFE has been deleted",
    "Inspeksi telah di-created" => "Inspection has been created",
    "Inspeksi telah di-updated" => "Inspection has been updated",
    "Inspeksi telah di-deleted" => "Inspection has been deleted",
    "Jadwal Inspeksi telah di-created" => "Inspection Schedule has been created",
    "Jadwal Inspeksi telah di-updated" => "Inspection Schedule has been updated",
    "Jadwal Inspeksi telah di-deleted" => "Inspection Schedule has been deleted",
    "User telah di-created" => "User has been created",
    "User telah di-updated" => "User has been updated",
    "User telah di-deleted" => "User has been deleted"
];

foreach ($newTranslations as $key => $value) {
    if (!isset($data[$key])) {
        $data[$key] = $value;
    }
}

file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Done";
