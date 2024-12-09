<?php

namespace Drupal\dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

  /**
   * Topvente.
   *
   * @return string
   *   Return Hello string.
   */
  public function topvente() {
    $service = \Drupal::service('dashboard.manager');
    $service_helper = \Drupal::service('drupal.helper');
    $params = $service_helper->helper->get_parameter();
    $items = $service->getQueryTopVenteArticleParMois( $params);

    die();
 
    return [
      '#theme' => 'dashboard_topvente', // The theme hook defined in hook_theme.
      '#items' => $items,
      '#cache' => [
        'max-age' => 0,  // Disable caching.
      ]
    ];
  }

}
