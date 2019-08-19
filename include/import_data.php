<?php
include "../include/connect.php";
$output = '';

$dataQuote = array();

if (!empty($_POST['product'])) {
    $dataQuote['product'] = $_POST['product'];
}
if (!empty($_POST['affiliate'])) {
    $dataQuote['affiliate'] = $_POST['affiliate'];
}
if (!empty($_POST['travelType'])) {
    $dataQuote['travel_type'] = $_POST['travelType'];
}
if (!empty($_POST['coverArea'])) {
    $dataQuote['coverArea'] = $_POST['coverArea'];
}
if (!empty($_POST['travelClass'])) {
    $dataQuote['travelClass'] = $_POST['travelClass'];
}
if (!empty($_POST['startDate'])) {
    $dataQuote['startDate'] = $_POST['startDate'];
}
if (!empty($_POST['endDate'])) {
    $dataQuote['endDate'] = $_POST['endDate'];
}

if (count($dataQuote) == 7) {
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
        $valid_extension = 'xml';
        $file_data = explode('.', $_FILES['file']['name']);
        $file_extension = end($file_data);
        $currentDate = date("Y-m-d");

        if ($file_extension == $valid_extension) {
            $data = file_get_contents($_FILES['file']['tmp_name']);
            $sqlQuote = "INSERT INTO wwiis_technical_challenge_quotes (quote_date, product, affiliate, travel_class, travel_type, cover_area, start_date, end_date) VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($sqlQuote);
            $stmt->bind_param(
                'ssssssss',
                $currentDate,
                $dataQuote['product'],
                $dataQuote['affiliate'],
                $dataQuote['travelClass'],
                $dataQuote['travel_type'],
                $dataQuote['coverArea'],
                $dataQuote['startDate'],
                $dataQuote['endDate']
            );

            $stmt->execute();
            if ($stmt) {
                $lastId = $conn->insert_id;
                $user = 1;
                $sql = "INSERT INTO wwiis_technical_challenge_medical (quote_number, traveller_id, medical_xml) VALUES (?, ?, ?)";

                $stmt1 = $conn->prepare($sql);
                $stmt1->bind_param(
                    'iis',
                    $lastId,
                    $user,
                    $data
                );
                $stmt1->execute();

                if ($stmt1) {
                    $output = '<div class="alert alert-success">Success</div>';
                }
            }


        } else {
            $output = '<div class="alert alert-warning">Invalid File</div>';
        }
    } else {
        $output = '<div class="alert alert-warning">Please select a file</div>';
    }
} else {
    $output = '<div class="alert alert-warning">Please fill all fields</div>';
}

echo $output;

