<?php

// Render a template.
// Using https://github.com/justintadlock/hybrid-core/blob/master/inc/functions-context.php#L36 as a basis.

$context = theme_get_context();

foreach($context as $cxt) {
    if ($templates->exists($cxt)) {
        echo $templates->render($cxt, ['template' => $cxt]);
        break;
    }
}