<?php

add_action('after_setup_theme', 'headless_setup');
function headless_setup()
{
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    remove_theme_support('block-templates');
}



add_filter('rest_prepare_post', 'headless_add_post_data', 10, 3);
function headless_add_post_data($data, $post, $context)
{
    $id = $data->data['id'];

    $data->data['temp_id'] = $id;

    // Featured Image https://wordpress.stackexchange.com/a/317331
    $featured_image_id = $data->data['featured_media'];
    $featured_image_url = wp_get_attachment_image_src($featured_image_id, 'original');
    if ($featured_image_url) {
        $data->data['featured_image_url'] = $featured_image_url[0];
    }

    // Category
    $categories = get_the_category($id);
    if ($categories) {
        $data->data['categories_info'] = $categories;
    }

    // Tags
    $tags = get_the_tags($id);
    if ($tags) {
        $data->data['tags_info'] = $tags;
    }

    // Author
    $author_id = get_post_field('post_author', $id);
    if ($author_id) {
        $author = get_userdata($author_id);
        $data->data['author_info'] = [
            'display_name' => $author->display_name ?? null,
            'description' => $author->description ?? null,
            'avatar_url' => get_avatar_url($author_id) ?? null,
        ];
    }

    return $data;
}


add_filter('rest_post_query', 'headless_filter_by_category_slug', 10, 3);
function headless_filter_by_category_slug($args, $request)
{
    // Usage: /wp-json/wp/v2/posts?category_slug=news

    if (isset($request['category_slug'])) {
        $category_slug = sanitize_text_field($request['category_slug']);
        $args['tax_query'] = [
            [
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $category_slug,
            ]
        ];
    }
    return $args;
}
