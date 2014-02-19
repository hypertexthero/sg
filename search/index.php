---
layout: search
title: "Search"
---

<?php 
$db = new SQLite3('search.db', SQLITE3_OPEN_READONLY);

$query = $_GET['s'];

if ( $query == ""  || preg_match("/^\s+/",$query) ) {
    echo "<p>No query specified.</p>";
}    else {
    echo "<p><strong>Query</strong> : $query </p>";

    $query = preg_replace("/^\s+/", "", $query);
    $query = preg_replace("/\s+$/", "", $query);
    $query = preg_replace("/(\s+)(\w+)/", "% %", $query);
    $query = "%" . $query . "%" ;
  
    # Currently returns max of 50 results, count to be used for pagination etc
    $count_stmt = $db->prepare('SELECT count(*) as num_pages FROM pages WHERE title like :search or text_content like :search  or permalink like :search or meta_description like :search or meta_keywords like :search');
    $count_stmt->bindValue(':search', $query, SQLITE3_TEXT);
    $count = $count_stmt->execute();

    $count_result = $count->fetchArray();

    if ( $count_result['num_pages'] == 0){
        echo "<p>No results for query.</p>";
    } else {
        $results_text = ($count_result['num_pages'] == 1) ? 'result' : 'results';
        $max_results_text = ($count_result['num_pages'] > 50) ? 'Showing first 50 results of ' : '';
        echo "<p>$max_results_text{$count_result['num_pages']} $results_text for query.</p>";

        $stmt = $db->prepare('SELECT title, permalink, search_excerpt FROM pages WHERE title like :search or text_content like :search  or permalink like :search or meta_description like :search or meta_keywords like :search  LIMIT 50');
        $stmt->bindValue(':search', $query, SQLITE3_TEXT);

        $result = $stmt->execute();

        while($search_result = $result->fetchArray(SQLITE3_ASSOC)){ 
            echo '<p>';
            echo "<strong><a href='{$search_result['permalink']}'>{$search_result['title']}</a></strong></br>";
            echo "{$search_result['search_excerpt']}";
            echo '</p>';
        }
    }
}
?>