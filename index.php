<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Word Frequency Counter</title>
    <link rel="stylesheet" type="text/css" href="styles.css">

</head>
<body>
    <h1>Samantha's Word Frequency Counter</h1>
    
    
    <form action="process.php" method="post">
        <label for="text">Paste your text here:</label><br>
        <textarea id="text" name="text" rows="10" cols="50" required></textarea><br><br>
        
        <label for="sort">Sort by frequency:</label>
        <select id="sort" name="sort">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select><br><br>
        
        <label for="limit">Number of words to display:</label>
        <input type="number" id="limit" name="limit" value="10" min="1"><br><br>
        
        <input type="submit" value="Calculate Word Frequency">
    </form>
</body>
</html>


<?php

function tokenizeText($text) {
  
    $words = preg_split('/\s+|[,.;?!]+/', $text);
    
    return $words;
}


function calculateWordFrequencies($words) {
  
    $stopWords = array("the", "and", "in", "to", "of", "is", "a");
   
    $wordFrequencies = array_count_values($words);
    
    
    foreach ($stopWords as $stopWord) {
        if (isset($wordFrequencies[$stopWord])) {
            unset($wordFrequencies[$stopWord]);
        }
    }
    
    return $wordFrequencies;
}


function sortWordsByFrequency($wordFrequencies, $sortOrder) {
    if ($sortOrder == "asc") {
        asort($wordFrequencies);
    } else {
        arsort($wordFrequencies);
    }
    
    return $wordFrequencies;
}


function limitWordDisplay($wordFrequencies, $limit) {

    $limitedWordFrequencies = array_slice($wordFrequencies, 0, $limit);
    
    return $limitedWordFrequencies;
}


function displayWordFrequencies($wordFrequencies) {
    
    echo "<h2>Word Frequencies:</h2>";
    
    foreach ($wordFrequencies as $word => $frequency) {
        echo $word . ": " . $frequency . "<br>";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $text = $_POST["text"];
    

    $words = tokenizeText($text);
    
  
    $wordFrequencies = calculateWordFrequencies($words);
    
    
    $sortOrder = $_POST["sort"];
    $sortedWordFrequencies = sortWordsByFrequency($wordFrequencies, $sortOrder);
    
   
    $displayLimit = $_POST["limit"];
    $limitedWordFrequencies = limitWordDisplay($sortedWordFrequencies, $displayLimit);
    

    displayWordFrequencies($limitedWordFrequencies);
}
?>
