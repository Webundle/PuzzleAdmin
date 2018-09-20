<?php
namespace Puzzle\Admin\AdminBundle\Twig;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 *
 * @author AGNES Gnagne Cedric <ccecenho55@gmail.com>
 *
 */
class AdminExtension extends \Twig_Extension
{
	/**
	 * @var UrlGeneratorInterface $router
	 */
	protected $router;
	
	/**
	 * @var RequestStack $requestStack
	 */
	protected $requestStack;
	
	/**
	 * @var string $currentRoute
	 */
	protected $currentRoute;
	
	/**
	 * @var AuthorizationCheckerInterface $authorizationChecker
	 */
	protected $authorizationChecker;
	
	/**
	 * @var TranslatorInterface $translator
	 */
	protected $translator;
	
	/**
	 * @var CacheItemPoolInterface $cache
	 */
	protected $cache;
	
	
	/**
	 * @var array $config
	 */
	protected $config;
	
	/**
	 * @var array $navigationNodes
	 */
	protected $navigationNodes;
	
		
	public function __construct(UrlGeneratorInterface $router, RequestStack $requestStack, AuthorizationCheckerInterface $authorizationChecker, TranslatorInterface $translator, CacheItemPoolInterface $cache, array $config) {
		$this->router = $router;
		$this->requestStack = $requestStack;
		$this->authorizationChecker = $authorizationChecker;
		$this->translator = $translator;
		$this->cache = $cache;
		$this->config = $config;
		$this->navigationNodes = $config['navigation']['nodes'];
	}
	
	public function getFunctions() {
		return [
    	    new \Twig_SimpleFunction('render_navigation', [$this, 'renderNavigationBlock'], ['needs_environment' => false, 'is_safe' => ['html']]),
    	    new \Twig_SimpleFunction('render_head_meta', [$this, 'renderHeadMeta'], ['needs_environment' => false, 'is_safe' => ['html']]),
    	    new \Twig_SimpleFunction('render_head_title', [$this, 'renderHeadTitle'], ['needs_environment' => false, 'is_safe' => ['html']]),
		];
	}
	
	/**
	 * @param \Twig_Environment $env
	 * @return string
	 */
	public function renderNavigationBlock() :string {
		$request = $this->requestStack->getCurrentRequest();
		$currentPath = $request->attributes->get('_route');
// 		$cacheItem = $this->cache->getItem('app.navigation.' . $currentPath);
		
// 		if (true === $cacheItem->isHit()) {
// 			return $cacheItem->get();
// 		}
		
		$tree = $this->bildMenuTree($this->navigationNodes);
		$menu = '<ul class="navigation navigation-main navigation-accordion">';
		
		foreach ($tree as $key => $node) {
			$menu .= $this->createMenuNode($key, $node, $currentPath);
		}
		
		$menu .= '</ul>';
		
// 		$cacheItem->set($menu);
// 		$cacheItem->tag('app.navigation');
// 		$this->cache->saveDeferred($cacheItem);
		
		return $menu;
	}
	
	protected function createMenuNode(string $nodeName, array $node, string $currentPath) {
		$children = '';
		$toActivate = false;
		
		if (!empty($node['children'])) {			
			foreach ($node['children'] as $key => $nodeItem) {
				if (!empty($nodeItem['children'])) {
					$children .= $this->createMenuNode($key, $nodeItem, $currentPath);
				} else {
					$children .= $this->createMenuNodeItem($nodeItem, $currentPath);
					
					if (false === $toActivate) {
						$toActivate = $currentPath === $nodeItem['path'];
					}
				}
			}
			
			$children = '<ul'.(true === $toActivate ? ' class="active"' : '').'>'.$children.'</ul>';
		}
		
		$menu = $this->createMenuNodeItem($node, $currentPath, $children, $toActivate);
		
		return $menu;
	}
	
	protected function createMenuNodeItem(array $nodeItem, string $currentPath, string $childrenMenu = '', bool $toActivate = false) {
		$class = isset($nodeItem['attr']['class']) ? $nodeItem['attr']['class'] : '';
		$label = $this->translator->trans($nodeItem['label'], $nodeItem['translation_parameters'], $nodeItem['translation_domain'], $nodeItem['translation_locale']);
		$tooltip = $this->translator->trans($nodeItem['tooltip'], $nodeItem['translation_parameters'], $nodeItem['translation_domain'], $nodeItem['translation_locale']);
		
		if ($childrenMenu !== '') {
			$liAttr = ' class="' . ($toActivate ? 'active' : '') . '"';
			$path = '#';
		} else {
			$liAttr = $currentPath === $nodeItem['path'] ? ' class="active"' : '';
			$path = $nodeItem['path'] ? $this->router->generate($nodeItem['path']) : '#';
		}
		
		return sprintf('<li%s><a href="%s" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-html="true" data-original-title="<b>%s</b>" data-content="%s"><i class="%s"></i>%s</a>%s</li>', $liAttr, $path, $label, $tooltip, $class, $label, $childrenMenu);
	}
	
	/**
	 * @param array $nodes
	 * @param string $parent
	 * @return []
	 */
	protected function bildMenuTree(array $nodes, string $parent = null) {
		$tree = [];
		
		foreach ($nodes as $key => $node) {
			if ($node['parent'] !== $parent) {
				continue;
			}
			
			if (!empty($node['user_roles']) && false === $this->authorizationChecker->isGranted($node['user_roles'])) {
				continue;
			}
			
			unset($nodes[$key]);
			$tree[$key] = $node;
			$tree[$key]['children'] = $this->bildMenuTree($nodes, $key);
		}
		
		return $tree;
	}
	
	public function renderHeadTitle() {
	    return $this->config['website']['name'];
	}
	
	public function renderHeadMeta() {
	    $website = $this->config['website'];
	    $meta = '<meta name="description" content="'.$website['description'].'">';
	    //         $meta .= '<meta property="og:url" content="'.$website['og']['url'].'">';
	    
	    return $meta;
	}
}
