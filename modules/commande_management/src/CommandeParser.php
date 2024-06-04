<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/03/2018
 * Time: 14:49
 */

namespace Drupal\commande_management;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\node\Entity\Node;
use Drupal\entity_parser\EntityParser;

class CommandeParser extends EntityParser
{
           
    function ers_451e8e6d7b53f8a06e3f8517cf02b856_field_articles($entity, $field){

      $bool = \Drupal\entity_parser\UtilityParser::is_field_ready($entity, $field);

      $item = [];
      if ($bool) {
          $fields = $entity->get($field)->getValue();
          if (!empty($fields)) {
              foreach ($fields as $key => $field_item) {
                  $paragraph = $this->loader_object($field_item['target_id'], 'paragraph');
                  $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
                  if ($paragraph && method_exists($paragraph, 'hasTranslation') && $paragraph->hasTranslation($language)) {
                      $paragraph = $paragraph->getTranslation($language);
                  }
                  if (is_object($paragraph)) {
                          $el = $this->entity_parser_load($paragraph);  
                          $price = 0 ;
                          if(isset($el['field_article']["#object"])){
                            $article = $el['field_article']["#object"];
                            $price = is_object($article)?$article->field_prix_unitaire->value : 0 ;
                          } 
                          if(isset($el['field_article']['nid'])){
                            $item[]=[
                                'id' => $el['field_article']['nid'],
                                'field_quantite' => $el['field_quantite'],
                                'field_article' => $el['field_article']['title'],  
                                'price_unitaire' =>   $price
                               ];
                          }             
                    
                  }
              }
              $is_multple = $entity->get($field)->getFieldDefinition()->getFieldStorageDefinition()->isMultiple();
              if (!$is_multple && count($item) == 1) {
                  return array_shift($item);
              }
          }

      }
      return $item;

    }
}
