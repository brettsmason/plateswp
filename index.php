<?php

// Render a template.
// Using https://github.com/justintadlock/hybrid-core/blob/master/inc/functions-context.php#L36 as a basis.

// Front page of the site.
if ( is_front_page() )
    $context = 'front-page';
// Blog page.
if ( is_home() ) {
    $context = 'blog';
}
// Singular views.
elseif ( is_singular() ) {
    $context = 'singular';
    // $context = "singular-{$object->post_type}";
    // $context = "singular-{$object->post_type}-{$object_id}";
}
// Archive views.
elseif ( is_archive() ) {
    $context = 'archive';
    // Post type archives.
}
// Search results.
elseif ( is_search() ) {
    $context[] = 'search';
}
// Error 404 pages.
elseif ( is_404() ) {
    $context[] = '404';
}

echo $templates->render($context);