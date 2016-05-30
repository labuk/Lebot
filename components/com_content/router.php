<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Routing class of com_content
 *
 * @since  3.3
 */
class ContentRouter extends JComponentRouterView
{
	/**
	 * Content Component router constructor
	 * 
	 * @param   JApplicationCms  $app   The application object
	 * @param   JMenu            $menu  The menu object to work with
	 */
	public function __construct($app = null, $menu = null)
	{
		$categories = new JComponentRouterViewconfiguration('categories');
		$categories->setKey('id');
		$this->registerView($categories);
		$category = new JComponentRouterViewconfiguration('category');
		$category->setKey('id')->setParent($categories, 'catid')->setNestable()->addLayout('blog');
		$this->registerView($category);
		$article = new JComponentRouterViewconfiguration('article');
		$article->setKey('id')->setParent($category, 'catid');
		$this->registerView($article);
		$this->registerView(new JComponentRouterViewconfiguration('archive'));
		$this->registerView(new JComponentRouterViewconfiguration('featured'));
		$this->registerView(new JComponentRouterViewconfiguration('form'));

		parent::__construct($app, $menu);

		$this->attachRule(new JComponentRouterRulesMenu($this));

		$params = JComponentHelper::getParams('com_content');

		if ($params->get('sef_advanced', 0))
		{
			$this->attachRule(new JComponentRouterRulesStandard($this));
		}
		else
		{
			require_once JPATH_SITE . '/components/com_content/helpers/legacyrouter.php';
			$this->attachRule(new ContentRouterRulesLegacy($this));
		}
	}

	/**
	 * Method to get the segment(s) for a category
	 * 
	 * @param   string  $id     ID of the category to retrieve the segments for
	 * @param   array   $query  The request that is build right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getCategorySegment($id, $query)
	{
		$category = JCategories::getInstance($this->getName())->get($id);

		if ($category)
		{
			return array_reverse($category->getPath());
		}

		return array();
	}

	/**
	 * Method to get the segment(s) for a category
	 * 
	 * @param   string  $id     ID of the category to retrieve the segments for
	 * @param   array   $query  The request that is build right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getCategoriesSegment($id, $query)
	{
		return $this->getCategorySegment($id, $query);
	}

	/**
	 * Method to get the segment(s) for an article
	 * 
	 * @param   string  $id     ID of the article to retrieve the segments for
	 * @param   array   $query  The request that is build right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getArticleSegment($id, $query)
	{
		return array($id);
	}

	/**
	 * Method to get the id for a category
	 * 
	 * @param   string  $segment  Segment to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getCategoryId($segment, $query)
	{
		if (isset($query['id']))
		{
			$category = JCategories::getInstance($this->getName())->get($query['id']);

			foreach ($category->getChildren() as $child)
			{
				if ($child->id == (int) $segment)
				{
					return $child->id;
				}
			}
		}

		return false;
	}

	/**
	 * Method to get the segment(s) for a category
	 * 
	 * @param   string  $segment  Segment to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 * 
	 * @return  mixed   The id of this item or false
	 */
	public function getCategoriesId($segment, $query)
	{
		return $this->getCategoryId($segment, $query);
	}

	/**
	 * Method to get the segment(s) for an article
	 * 
	 * @param   string  $segment  Segment of the article to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 * 
	 * @return  mixed   The id of this item or false
	 */
	public function getArticleId($segment, $query)
	{
		return (int) $segment;
	}
}

/**
 * Content router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @param   array  &$query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function contentBuildRoute(&$query)
{
	$app = JFactory::getApplication();
	$router = new ContentRouter($app, $app->getMenu());

	return $router->build($query);
}

/**
 * Parse the segments of a URL.
 *
 * This function is a proxy for the new router interface
 * for old SEF extensions.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 *
 * @since   3.3
 * @deprecated  4.0  Use Class based routers instead
 */
function contentParseRoute($segments)
{
	$app = JFactory::getApplication();
	$router = new ContentRouter($app, $app->getMenu());

	return $router->parse($segments);
}