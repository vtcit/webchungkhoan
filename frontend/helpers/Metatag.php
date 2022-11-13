<?php

namespace frontend\helpers;


class Metatag
{
	public $view;

	// public $metas = ['site_name', 'description', 'locale', 'type', 'url', 'tag', 'publisher', 'published_time', 'modified_time', 'updated_time', 'image', 'image_width', 'image_height'];
	// <meta property="og:title" content="" />
	// <meta name="twitter:title" content="" />
	public $title;
	// <meta property="og:site_name" content="" />
	public $site_name;
	// <meta property="og:description" content="" />
	// <meta name="twitter:description" content="" />
	public $description;
	// <meta property="og:locale" content="vi_VN" />
	public $locale;
	// <meta property="og:type" content="article" />
	public $type;
	// <link rel="canonical" href="" />
	// <link property="og:url" content="" />
	public $url;
	// <meta property="article:tag" content="" />
	// <meta property="article:section" content="" />
	public $tag;
	// <meta property="article:publisher" content="https://www.facebook.com/username/" />
	public $publisher;
	// <meta property="article:published_time" content="2019-08-20T03:15:37+00:00" />
	public $published_time;
	// <meta property="article:modified_time" content="2019-08-20T12:16:22+00:00" />
	public $modified_time;
	// <meta property="og:updated_time" content="2019-08-20T12:16:22+00:00" />
	public $updated_time;
	// <meta property="og:image" content="" />
	// <meta property="og:image:secure_url" content="" />
	public $image;
	// <meta property="og:image:width" content="" />
	public $image_width;
	// <meta property="og:image:height" content="" />
	public $image_height;


	public function addMeta($meta = [])
	{
		foreach($meta as $k => $v)
		{
			if(property_exists(get_class($this), $k))
			{
				$this->$k = (string) $v;
			}
		}
	}

	/**
	 * [register() Register Meta and Link html tag]
	 * @param  [yii\web\View] $view 
	 * @param  array  $metas []
	 * @return n/a
	 */
	public function register($view, $metas = [])
	{
		$this->view = $view;

		if($metas)
			$this->addMeta($metas);

		// Link
		$this->view->registerLinkTag(['rel' => 'canonical', 'href' => $this->url]);
		$this->registerTag(['name' => 'description'], $this->description);

		$this->registerTag(['property' => 'og:url'], $this->url, 'link');
		$this->registerTag(['property' => 'og:description', 'name' => 'twitter:description'], $this->description);
		$this->registerTag(['property' => 'og:title', 'name' => 'twitter:title'], $this->title);
		$this->registerTag(['property' => 'og:site_name'], $this->site_name);
		$this->registerTag(['property' => 'og:locale'], $this->locale);
		$this->registerTag(['property' => 'og:type'], $this->type);
		$this->registerTag(['property' => 'og:image', 'property' => 'og:image:secure_url'], $this->image);
		$this->registerTag(['property' => 'og:image:width'], $this->image_width);
		$this->registerTag(['property' => 'og:image:height'], $this->image_height);
		$this->registerTag(['property' => 'article:publisher'], $this->publisher);
		$this->registerTag(['property' => 'article:published_time'], $this->published_time);
		$this->registerTag(['property' => 'article:modified_time'], $this->modified_time);
		$this->registerTag(['property' => 'article:updated_time'], $this->updated_time);
	}

	// registerMetaTag($property = [], $content = '')
	// $view	yii\web\View
	private function registerTag($property, $content, $tag = 'meta')
	{
		if(!$content)
			return;

		foreach($property as $k => $v)
		{
			if($tag == 'meta')
				$this->view->registerMetaTag([ $k => $v, 'content' => $content ]);
			else
				$this->view->registerLinkTag([ $k => $v, 'content' => $content ]);
		}
	}

}

