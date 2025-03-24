<?php
add_filter('upload_mimes', function($mimes) {
    $mimes['epub'] = 'application/epub+zip';
    return $mimes;
});
