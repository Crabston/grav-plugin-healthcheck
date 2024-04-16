<?php
namespace Grav\Plugin;

use Grav\Common\Page\Collection;
use Grav\Common\Page\Page;
use Grav\Common\Plugin;
use Grav\Common\Processors\Events;
use Grav\Common\Uri;

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
	protected $healthConfig;

    /**
     * @return array
     *
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                ['onPluginsInitialized', 0],
            ],
	         'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0]
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
		header('Content-Type: application/json');
		//http_response_code(200);
		// add status code
		header('HTTP/1.1 200 OK');
		echo $json;

	}

	/**
	 * Add current directory to twig lookup paths.
	 */
	public function onTwigTemplatePaths()
	{
		array_unshift($this->grav['twig']->twig_paths, __DIR__ . '/templates');
	}
}
