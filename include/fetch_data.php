<?php
include "../include/connect.php";

if($_POST) {
    $results_per_page = 25;

    // Checking if there is some search criteria for filter
    $query = array();
    if (!empty($_POST['coverArea'])) {
        $coverArea = $_POST['coverArea'];
        $query[] = "cover_area='$coverArea'";
    }
    if (!empty($_POST['travelClass'])) {
        $travelClass = $_POST['travelClass'];
        $query[] = "travel_class='$travelClass'";
    }
    if (!empty($_POST['startDate'])) {
        $startDate = $_POST['startDate'];
        $query[] = "start_date='$startDate'";
    }

    // If there is any search criteria add WHERE clause to sql query
    if (!empty($query)){
        $where = " WHERE ";
    }else{
        $where = "";
    }

    $searchQuery = implode(' AND ', $query);

    // Pages parameters
    $sql = "SELECT * FROM wwiis_technical_challenge_quotes $where $searchQuery";

    $pages_result = $conn->query($sql);
    $number_of_results = $pages_result->num_rows;

    $number_of_pages = ceil($number_of_results/$results_per_page);
    $page = $_POST['page'];
    // if there is not passed page number
    if (!$page) {
        $page = 1;
    }
    $page_first_result = ($page - 1) * $results_per_page;

    $paginationLinks = getAllPageLinks($number_of_pages, $page);

    // Creating sql query with JOIN for xml medical condition
    $sql = 'SELECT '
        . 'a.quote_number, '
        . 'm.medical_xml as "condition" ';

    $sql .= ' FROM wwiis_technical_challenge_quotes a '
        . ' INNER JOIN wwiis_technical_challenge_medical m ON m.quote_number = a.quote_number';

    $sql .= $where;
    $sql .= $searchQuery;
    $sql .= " LIMIT $page_first_result , $results_per_page";

    $resultSearch = $conn->query($sql);

    // if any data
    if ($resultSearch->num_rows > 0) {
        $records = $resultSearch->fetch_all();
        foreach ($records as $key=>$value) {
            $records[$key][1]=simplexml_load_string($records[$key][1]);
            $records[$key][2] = $key;
        }
        $result['page'] = $page;
        $result['totalPages'] = $number_of_pages;
        $result['records'] = $records;
        $result['links'] = $paginationLinks;

        // Creating json with our data, page parameters and links for pagination
        $json_data = json_encode($result, JSON_UNESCAPED_UNICODE);

        echo $json_data;
    }
}


    // Creatin our page links with passed total pages and current page
    function getAllPageLinks($pages, $page) {
        $output = '';
        if($pages>1) {
            if($page == 1)
                $output = $output . '<li class="page-item disabled"><button class="first page-link"><<</button></li><li class="page-item disabled"><button class="previous page-link"><</button></li>';
            else
                $output = $output . '<li class="page-item"><button class="first page-link"><<</button></li><li class="page-item"><button class="previous page-link"><</button></li>';


            if(($page-3)>0) {
                if($page == 1)
                    $output = $output . '<li class="page-item active"><button id="1" class="page-num page-link">1</button></li>';
                else
                    $output = $output . '<li class="page-item"><button id="1" class="page-num page-link">1</button></li>';
            }
            if(($page-3)>1) {
                $output = $output . '<li class="page-item"><span class="dot">...</span></li>';
            }

            for($i=($page-2); $i<=($page+2); $i++)	{
                if($i<1) continue;
                if($i>$pages) break;
                if($page == $i)
                    $output = $output . '<li class="page-item active"><button id="'. $i .'" class="page-num page-link">'. $i .'</button></li>';
                else
                    $output = $output . '<li class="page-item"><button id="'. $i .'" class="page-num page-link">'. $i .'</button></li>';
            }

            if(($pages-($page+2))>1) {
                $output = $output . '<li class="page-item"><span class="dot">...</span></li>';
            }
            if(($pages-($page+2))>0) {
                if($page == $pages)
                    $output = $output . '<li class="page-item active"><button id="'. $pages .'" class="page-num page-link">'. $pages .'</button></li>';
                else
                    $output = $output . '<li class="page-item"><button id="'. $pages .'" class="page-num page-link">'. $pages .'</button></li>';
            }

            if($page < $pages)
                $output = $output . '<li class="page-item"><button class="next page-link">></button></li><li class="page-item"><button class="last page-link">>></button></li>';
            else
                $output = $output . '<li class="page-item disabled"><button class="next page-link">></button></li><li class="page-item disabled"><button class="last page-link">>></button></li>';
        }
        return $output;
    }
?>