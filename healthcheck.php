<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;


/**
 * Class HealthcheckPlugin
 * @package Grav\Plugin
 */
class HealthcheckPlugin extends Plugin
{

	/**
	 * @var
	 */
	protected $uri;

    /**
     * @return array
     *
     */
    public static function getSubscribedEvents(): array
    {
        return [
			 'onPluginsInitialized' => ['onPluginsInitialized', 0],
	         'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
	         'onOutputGenerated' => ['onOutputGenerated', 0],
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

	    $uri = $this->grav['uri'];
	    $config = $this->config();

	    $route = $config['route'] ?? null;
	    if ($route && $route == $uri->path()) {
		    $this->enable([
			    'onPageInitialized' => ['onPageInitialized', 0]
		    ]);
	    }
    }

	/**
	 * Send user to a random page
	 */
	public function onPageInitialized(): void
	{
		$payload = [
			'status' => 200,
			'message' => 'OK'
		];

		$json = json_encode($payload);
		echo $json;

	}

	public function onOutputGenerated()
	{

		$this->grav['page']->modifyHeader('http_response_code', 200);
	}

	/**
	 * Add current directory to twig lookup paths.
	 */
	public function onTwigTemplatePaths()
	{
		array_unshift($this->grav['twig']->twig_paths, __DIR__ . '/templates');
	}
}
