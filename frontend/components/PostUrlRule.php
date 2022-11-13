<?php

namespace frontend\components;

use yii\web\UrlRuleInterface;
use yii\base\BaseObject;
use common\models\Post;
use common\models\Category;

class PostUrlRule extends BaseObject implements UrlRuleInterface
{

    public function createUrl($manager, $route, $params)
    {
        $postTypes = Post::getTypes();
        $catTypes = Category::getTypes();
        if(isset($params['type'], $params['slug'])
         && (($route === 'post/category' && isset($catTypes[$params['type']])) || ($route === 'post/view' && isset($postTypes[$params['type']]))))
        {
             $url = $params['type'].'/'.$params['slug'];
            if(isset($params['id']))
                $url .= '-'.$params['id'];
            
            return $url.'/';
        }
        return false;
    }

    public function parseRequest($manager, $request)
    {
        $postTypes = Post::getTypes();
        $catTypes = Category::getTypes();
        $pathInfo = $request->getPathInfo();
        $params = [];
        if (preg_match('%^([\w-]+)/([\w-]+)-([\w]+)/$%', $pathInfo, $matches)) {
            if(isset($matches[1], $matches[2]))
            {
                $params['type'] = $matches[1];
                $params['slug'] = $matches[2];
                if(isset($postTypes[$matches[1]], $matches[3])) {
                    $params['id'] = $matches[3];
                    return ['post/view', $params];
                }
                if(isset($catTypes[$matches[1]])) {
                    if(isset($matches[3])) {
                         $params['slug'] = $matches[2].'-'.$matches[3];
                    }
                    return ['post/category', $params];
                }
            }
        }
        return false;
    }
}
