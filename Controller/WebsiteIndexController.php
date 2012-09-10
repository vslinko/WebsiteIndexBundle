<?php
/**
 * @copyright 2012 Rithis Studio LLC
 * @author Vyacheslav Slinko <vyacheslav.slinko@rithis.com>
 */

namespace Rithis\WebsiteIndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WebsiteIndexController extends Controller
{
    public function getAction()
    {
		$pathInfo = $this->getRequest()->getPathInfo();

        $routes = array_filter($this->get('router')->getRouteCollection()->all(), function (&$route) use ($pathInfo) {
            return strpos($route->getPattern(), '/_') !== 0
				&& strpos($route->getPattern(), '/admin/') !== 0
				&& $route->getPattern() != $pathInfo;
        });

        $links = array();
        $config = $this->container->getParameter('rithis.website_index.config');

        foreach ($routes as $key => $route) {
            $requirements = $route->getRequirements();
            if (isset($requirements['_method']) && $requirements['_method'] != 'GET') {
                continue;
            }

			$variables = $route->compile()->getVariables();

            if (count($variables)) {
                if (array_key_exists($key, $config)) {
                    $repository = $this->getDoctrine()->getManager()->getRepository($config[$key]);

                    foreach ($repository->findAll() as $entity) {
                        $parameters = array();

                        foreach ($variables as $variable) {
                            $method = 'get' . ucfirst($variable);

                            if (!method_exists($entity, $method)) {
                                break;
                            }

                            $parameters[$variable] = $entity->$method();
                        }

                        $links[] = $this->generateUrl($key, $parameters, true);
                    }
                }
            } else {
                $links[] = $this->generateUrl($key, array(), true);
            }
        }

        return $this->render('RithisWebsiteIndexBundle:WebsiteIndex:get.html.twig', array(
            'links' => $links,
        ));
    }
}
