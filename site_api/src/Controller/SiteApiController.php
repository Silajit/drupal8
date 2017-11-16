<?php

/**
 * @file
 * Contains \Drupal\site_api\Controller\SiteApiController.
 */

namespace Drupal\site_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\field\FieldConfigInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
class SiteApiController extends ControllerBase {

      public function getJsonbyNodeId($sitekey,$nid) {
        
        if (!empty($nid) && !empty($sitekey)) {
          $siteapikey = \Drupal::config('site_api.settings')->get('siteapikey');
          $node = Node::load($nid);
          $output = array();
          // the rest of your code here
          if(!empty($node) && !empty($siteapikey) && ($sitekey == $siteapikey)) {
            foreach($node as $id => $val) {
              $output[$id] = $node->get($id)->getValue();
            }
            $type =  $node->getType();
            if($type == 'page') {
              return new JsonResponse(
                array(
                  'node' => $output,
                  'type' => $type,
                )
              );
            }
            else {
              throw new AccessDeniedHttpException();
            }
          }
        else {
          throw new AccessDeniedHttpException();
          
        }
    
  
  }
  else {
    throw new AccessDeniedHttpException();
    
  }

}   
  public function showMigration() {
    return array(
      '#markup' => t('Hello World!'),
    );
  }
}

