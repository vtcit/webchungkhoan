<?php
$category_type = $params['category_type'];
$page_type = $params['page_type'];
$post_type = $params['post_type'];

$return = [
    '' => 'site/index',
    'lien-he' => 'site/contact',
    'link/<type:(image|url)>/<code:[\w+-=]+>' => 'link/<type>',
];

if(isset($page_type)) {
    $type = array_merge(['page'], array_keys($page_type));
    $return['<type:('.implode('|', $type).')>'] = 'page/index';
    $return['<type:('.implode('|', $type).')>/<slug:[\w-]+>'] = 'page/view';
}
if(isset($category_type)) {
    $type = array_merge(['category'], array_keys($category_type));
    $return['<type:('.implode('|', $type).')>/<slug:[\w-]+>'] = 'post/category';
}
if(isset($post_type)) {
    $type = array_merge(['post'], array_keys($post_type));
    $return['<type:('.implode('|', $type).')>'] = 'post/index';
    $return['<type:('.implode('|', $type).')>/<slug:[\w-]+>-<id:[\w]+>'] = 'post/view';
}
// print_r($return);exit;
return $return;
