<?php

function tokenizeText($text) {
    $text = preg_replace('/[\p{P}\p{S}]+/u', ' ', $text); 
    $words = preg_split('/\s+/', $text); 
    return $words;
}

function calculateWordFrequencies($words) {
    $stopWords = array("the", "and", "in", "to", "of", "is", "a", "are");
    $filteredWords = array_diff($words, $stopWords);
    $wordFrequencies = array_count_values($filteredWords);
    return $wordFrequencies;
}

function displayWordFrequencies($wordFrequencies, $order = 'asc', $limit = null) {
    echo "<h2>Word Frequencies:</h2>";

    if ($order === 'desc') {
        arsort($wordFrequencies);
    } else {
        asort($wordFrequencies);
    }

    $count = 0;
    foreach ($wordFrequencies as $word => $frequency) {
        echo $word . ": " . $frequency . "<br>";
        $count++;
        if ($limit && $count >= $limit) {
            break;
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputMethod = filter_input(INPUT_POST, "input_method", FILTER_SANITIZE_STRING);
    $order = filter_input(INPUT_POST, "order", FILTER_SANITIZE_STRING);
    $limit = filter_input(INPUT_POST, "limit", FILTER_VALIDATE_INT);

    if ($inputMethod === "paste") {
        $text = filter_input(INPUT_POST, "text", FILTER_SANITIZE_STRING);
        if ($text !== null && $text !== false) {
            $words = tokenizeText($text);
            $wordFrequencies = calculateWordFrequencies($words);

            if (!empty($wordFrequencies)) {
                displayWordFrequencies($wordFrequencies, $order, $limit);
            } else {
                echo "No text input found.";
            }
        } else {
            echo "Invalid text input.";
        }
    } elseif ($inputMethod === "file") {
        $file = $_FILES["file"]["tmp_name"] ?? "";
        if (!empty($file)) {
            $text = file_get_contents($file);
            if ($text !== false) {
                $words = tokenizeText($text);
                $wordFrequencies = calculateWordFrequencies($words);

                if (!empty($wordFrequencies)) {
                    displayWordFrequencies($wordFrequencies, $order, $limit);
                } else {
                    echo "No text input found.";
                }
            } else {
                echo "Error reading the file.";
            }
        } else {
            echo "Please upload a file.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Word Frequency Counter</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Word Frequency Counter</h1>

    <form method="POST" action="" enctype="multipart/form-data">
        <label for="input_method">Choose input method:</label><br>
        <select id="input_method" name="input_method">
            <option value="paste">Paste Text</option>
            <option value="file">Read File</option>
        </select><br><br>

        <div id="paste_text" class="input_section">
            <label for="text">Enter your text:</label><br>
            <textarea id="text" name="text" rows="10" cols="50"></textarea><br><br>
        </div>

        <div id="read_file" class="input_section" style="display: none;">
            <label for="file">Upload a text file:</label><br>
            <input type="file" id="file" name="file"><br><br>
        </div>

        <label for="order">Sorting Order:</label>
        <select id="order" name="order">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select><br><br>

        <label for="limit">Display Limit:</label>
        <input type="number" id="limit" name="limit" min="1"><br><br>

        <input type="submit" value="Calculate Word Frequency">
    </form>

    <script>
        document.getElementById("input_method").addEventListener("change", function() {
            var pasteText = document.getElementById("paste_text");
            var readFile = document.getElementById("read_file");

            if (this.value === "paste") {
                pasteText.style.display = "block";
                readFile.style.display = "none";
            } else if (this.value === "file") {
                pasteText.style.display = "none";
                readFile.style.display = "block";
            }
        });
    </script>
</body>
</html>