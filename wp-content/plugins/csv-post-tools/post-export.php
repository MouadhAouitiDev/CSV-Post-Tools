<?php

add_action('admin_action_my_post_export_all', 'my_export_all_posts');
function my_export_all_posts() {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
    );

    $posts = get_posts($args);

    if (!empty($posts)) {
        // Prepare CSV data
        $csv_data = array();
        foreach ($posts as $post) {
            $post_title = $post->post_title;
            $post_date = date('Y-m-d H:i:s', strtotime($post->post_date)); 
            $post_author = get_the_author_meta('display_name', $post->post_author);
            $category = get_the_category($post->ID);
            $category_name = !empty($category) ? $category[0]->name : '';
            $csv_data[] = array($post_title, $post_date, $post_author, $category_name);
        }
        
    
        // Output CSV
        $delimiter = "\t";
        $enclosure = '"';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="exported_posts.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF");
        fputs($output, "sep=\t" . PHP_EOL);
        fputcsv($output, array("Post Title", "Date", "Author", "Category"), $delimiter, $enclosure); // headers
        foreach ($csv_data as $row) {
            fputcsv($output, $row, $delimiter, $enclosure);
        }
        fclose($output);
        exit;
    }
}
