<?php

namespace Drupal\commande_management\TwigExtension;

use Drupal\Core\Render\Renderer;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
/**
 * Class DefaultTwigExtension.
 */

class DefaultTwigExtension extends AbstractExtension {

        
   /**
    * {@inheritdoc}
    */
    public function getTokenParsers() {
      return [];
    }

   /**
    * {@inheritdoc}
    */
    public function getNodeVisitors() {
      return [];
    }

   /**
    * {@inheritdoc}
    */
    public function getFilters() {
      return [];
    }

   /**
    * {@inheritdoc}
    */
    public function getTests() {
      return [];
    }
     /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('commande_parser', ['Drupal\commande_management\TwigExtension\DefaultTwigExtension', 'commande_parser_twig']),
        ];
    }

    public static function commande_parser_twig($item){
      $service = \Drupal::service('commande_management.parser');
      return $service->node_parser($item);
    }

  }