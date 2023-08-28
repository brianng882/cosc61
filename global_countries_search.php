<?php
// DB connection
$username = "bob";
$password = "insecure_pass";
$database = "global_countries";

// Acquire the form fields from html POST
// Others
$countryKey = $_POST['s_country_key'];
$gdpRange = $_POST['s_GDP_range'];
$minWageMin = $_POST['s_minwage_min'];
$minWageMax = $_POST['s_minwage_max'];
$popRange = $_POST['s_pop_range'];
$taxRateMin = $_POST['s_taxrate_min'];
$taxRateMax = $_POST['s_taxrate_max'];
$tertMin = $_POST['s_tert_min'];
$tertMax = $_POST['s_tert_max'];
$lifeMin = $_POST['s_life_min'];
$lifeMax = $_POST['s_life_max'];
$latMin = $_POST['s_lat_min'];
$latMax = $_POST['s_lat_max'];
$longMin = $_POST['s_long_min'];
$longMax = $_POST['s_long_max'];

// Order
$countryOrder = $_POST['s_country_order'];
$gdpOrder = $_POST['s_GDP_order'];
$minwageOrder = $_POST['s_minwage_order'];
$popOrder = $_POST['s_pop_order'];
$taxRateOrder = $_POST['s_taxrate_order'];
$tertOrder = $_POST['s_tert_order'];
$lifeOrder = $_POST['s_life_order'];
$latOrder = $_POST['s_lat_order'];
$longOrder = $_POST['s_long_order'];

// Establish MySQL connection called $mysqli
$mysqli = new mysqli('localhost', $username, $password, $database);

// Create an array to store conditions
$conditions = [];
$params = [];
$paramTypes = "";
$orderBy = [];

// Others
// For country keyword
if (!empty($countryKey)) {
    $conditions[] = "a.country LIKE ?";
    $params[] = "%" . $countryKey . "%";
    $paramTypes .= "s";
}

// For GDP range
if (isset($gdpRange)) {
    switch ($gdpRange) {
        case '1tplus':
            $conditions[] = 'd.gdp > ?';
            $params[] = 1000000000000; // 1 trillion
            $paramTypes .= 'd'; // assuming GDP is stored as a big integer
            break;
        case '250b1t':
            $conditions[] = 'd.gdp BETWEEN ? AND ?';
            $params[] = 250000000000;  // 250 billion
            $params[] = 999999999999;  // just under 1 trillion
            $paramTypes .= 'dd';
            break;
        case '250bless':
            $conditions[] = 'd.gdp < ?';
            $params[] = 250000000000;  // 250 billion
            $paramTypes .= 'd';
            break;
    }
}

// For Min Wage
if (!empty($minWageMin)) {
    $conditions[] = "d.min_wage >= ?";
    $params[] = $minWageMin;
    $paramTypes .= "d";
}
if (!empty($minWageMax)) {
    $conditions[] = "d.min_wage <= ?";
    $params[] = $minWageMax;
    $paramTypes .= "d";
}

// For Population range
if (isset($popRange)) {
    switch ($popRange) {
        case '250mplus':
            $conditions[] = 'i.population > ?';
            $params[] = 250000000; // 250 million
            $paramTypes .= 'd'; // assuming GDP is stored as a big integer
            break;
        case '10m250m':
            $conditions[] = 'i.population BETWEEN ? AND ?';
            $params[] = 10000000;  // 10 million
            $params[] = 249999999;  // just under 250 million
            $paramTypes .= 'dd';
            break;
        case '10mless':
            $conditions[] = 'i.population < ?';
            $params[] = 10000000;  // 10 million
            $paramTypes .= 'd';
            break;
    }
}

// For Tax Rate
if (!empty($taxRateMin)) {
    $conditions[] = "d.tax_rate >= ?";
    $params[] = $taxRateMin;
    $paramTypes .= "d";
}
if (!empty($taxRateMax)) {
    $conditions[] = "d.tax_rate <= ?";
    $params[] = $taxRateMax;
    $paramTypes .= "d";
}

// For Tertiary Enrollment
if (!empty($tertMin)) {
    $conditions[] = "e.tert_enroll >= ?";
    $params[] = $tertMin;
    $paramTypes .= "d";
}
if (!empty($tertMax)) {
    $conditions[] = "e.tert_enroll <= ?";
    $params[] = $tertMax;
    $paramTypes .= "d";
}

// For Life Expectancy
if (!empty($lifeMin)) {
    $conditions[] = "i.life_expect >= ?";
    $params[] = $lifeMin;
    $paramTypes .= "d";
}
if (!empty($lifeMax)) {
    $conditions[] = "i.life_expect <= ?";
    $params[] = $lifeMax;
    $paramTypes .= "d";
}

// For Latitude
if (!empty($latMin)) {
    $conditions[] = "a.latitude >= ?";
    $params[] = $latMin;
    $paramTypes .= "d";
}
if (!empty($latMax)) {
    $conditions[] = "a.latitude <= ?";
    $params[] = $latMax;
    $paramTypes .= "d";
}

// For Longitude
if (!empty($longMin)) {
    $conditions[] = "a.longitude >= ?";
    $params[] = $longMin;
    $paramTypes .= "d";
}
if (!empty($longMax)) {
    $conditions[] = "a.longitude <= ?";
    $params[] = $longMax;
    $paramTypes .= "d";
}


// Order
// Order by Country
if (isset($countryOrder)) {
    $orderBy[] = 'a.country ' . $countryOrder;
}

// Order by GDP
if (isset($gdpOrder)) {
    $orderBy[] = 'd.gdp ' . $gdpOrder;
}

// Order by min wage
if (isset($minwageOrder)) {
    $orderBy[] = 'd.min_wage ' . $minwageOrder;
}

// Order by population
if (isset($popOrder)) {
    $orderBy[] = 'i.population ' . $popOrder;
}

// Order by tax rate
if (isset($taxRateOrder)) {
    $orderBy[] = 'd.tax_rate ' . $taxRateOrder;
}

// Order by tertiary enrollment
if (isset($tertOrder)) {
    $orderBy[] = 'e.tert_enroll ' . $tertOrder;
}

// Order by life expectancy
if (isset($lifeOrder)) {
    $orderBy[] = 'i.life_expect ' . $lifeOrder;
}

// Order by latitude
if (isset($latOrder)) {
    $orderBy[] = 'a.latitude ' . $latOrder;
}

// Order by longitude
if (isset($longOrder)) {
    $orderBy[] = 'a.longitude ' . $longOrder;
}



// Building MYSQL query
// Initial query
$query = "SELECT * FROM countries a
JOIN country_cities b ON a.cid=b.cid
JOIN country_currency c ON a.cid=c.cid
JOIN economics d ON a.cid=d.cid
JOIN education e ON a.cid=e.cid
JOIN environment f ON a.cid=f.cid
JOIN health g ON a.cid=g.cid
JOIN military h ON a.cid=h.cid
JOIN population i ON a.cid=i.cid";

// Append WHERE conditions
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Append ORDER BY conditions
if (!empty($orderBy)) {
    $query .= " ORDER BY " . implode(", ", $orderBy);
}

$stmt = $mysqli->prepare($query);

if (!empty($paramTypes)) {
    $stmt->bind_param($paramTypes, ...$params);
}

$stmt->execute();

// Get the result
$result = $stmt->get_result();
$rowCount = $result->num_rows;

// Display the results
if ($result->num_rows > 0) {
    echo "Number of results: " . $rowCount . "</br>" . "</br>" . "</br>";
    while ($row = $result->fetch_assoc()) {
        echo $row['country'] . " | GDP:" . $row['gdp'] . " | Min Wage:" . $row['min_wage'] . " | Pop:" . $row['population'] . " | Tax Rate:" . $row['tax_rate'] . " | Tert Enroll:" . $row['tert_enroll'] . " | Life Expect:" . $row['life_expect'] . " | Lat:" . $row['latitude'] . " | Long:" . $row['longitude'] . "</br>" . "</br>";
    }
} else {
    echo 'NO RESULTS';
}
?>
