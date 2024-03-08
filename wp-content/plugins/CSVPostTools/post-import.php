<?php

function my_post_importer_page() {
    ?>
    <div class="wrap">
        <h1>Post Importer</h1>
        <form method="post" enctype="multipart/form-data">
            <label for="file">Choose CSV file to import:</label><br>
            <input type="file" name="file" id="file"><br>
            <input type="submit" name="submit" class="button button-primary" value="Import">
        </form>
    </div>
    <?php
}

function my_handle_post_import() {
    if (isset($_POST['submit'])) {
        $file = $_FILES['file']['tmp_name'];

        if (($handle = fopen($file, "r")) !== FALSE) {
            // Skip the header row
            fgetcsv($handle, 1000, ",");

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $post_title = $data[0];
                $category_name = $data[1];

                // Check if category exists, create if not
                $category = get_term_by('name', $category_name, 'category');
                if (!$category) {
                    $category_id = wp_insert_term($category_name, 'category');
                    if (is_wp_error($category_id)) {
                        continue;
                    }
                    $category = get_term($category_id['term_id'], 'category');
                } else {
                    $category = reset($category); // Get the first term object
                }

                // Prepare post data
                $post_data = array(
                    'post_title' => $post_title,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_author' => 1, // Change this if needed
                    'post_category' => array($category->term_id),
                );

                // Insert the post
                $post_id = wp_insert_post($post_data);

                if (is_wp_error($post_id)) {
                    error_log('Error inserting post: ' . $post_id->get_error_message());
                } else {
                    error_log('Post inserted successfully: ' . $post_id);
                }

            }
            fclose($handle);
        } else {
            error_log('Error opening file: ' . $file);
        }
    }
}
add_action('admin_init', 'my_handle_post_import');
