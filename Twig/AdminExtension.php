<?php
namespace Puzzle\Admin\AdminBundle\Twig;

use Doctrine\ORM\EntityManager;

/**
 *
 * @author AGNES Gnagne Cedric <cecenho55@gmail.com>
 *
 */
class AdminExtension extends \Twig_Extension
{
    /**
     * @var EntityManager $em
     */
    protected $em;
    
    /**
     * @var \Twig_Environment $twig
     */
    protected $twig;
    
    /**
     * @var array
     */
    protected $config;
    
    public function __construct(EntityManager $em, \Twig_Environment $twig, array $config) {
        $this->em = $em;
        $this->twig = $twig;
        $this->config = $config;
    }
    
    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('render_menu', [$this, 'renderMenu'], ['needs_environment' => false, 'is_safe' => ['html']]),
            new \Twig_SimpleFunction('render_head_meta', [$this, 'renderHeadMeta'], ['needs_environment' => false, 'is_safe' => ['html']]),
            new \Twig_SimpleFunction('render_head_title', [$this, 'renderHeadTitle'], ['needs_environment' => false, 'is_safe' => ['html']]),
            
        ];
    }
    
    public function renderMenu($active, $subactive) {
        $modulesAvailables = explode(',', $this->config['modules_availables']);
        $bundleNames = [];
        
        foreach ($modulesAvailables as $moduleAvailable) {
            $bundleNames[] = 'PuzzleAdmin'.ucwords($moduleAvailable).'Bundle';
        }
        
        return $this->twig->render('PuzzleAdminBundle:Default:navigation.html.twig', [
            'bundleNames' => $bundleNames,
            'active' => $active,
            'subactive' => $subactive
        ]);
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
