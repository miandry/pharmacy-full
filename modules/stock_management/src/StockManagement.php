<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/03/2018
 * Time: 14:49
 */

namespace Drupal\stock_management;



class StockManagement 
{

     function calculatePrixDeVente($achat , $marge ){
        return  $achat + ( $achat * $marge)/100  ;
     }    
     function addStockNumberOnInsertDefecteux($entity){
         $node = \Drupal::service('entity_parser.manager')->node_parser($entity);
         $article =   $node["field_article"]["#object"];
         $nbrStock = 0;
         if($article->field_quantite_stock 
               && $article->field_quantite_stock->value ){
            $nbrStock = $article->field_quantite_stock->value ;  
         }                           
         $article->field_quantite_stock->value = $nbrStock - $node["field_quantite"];
         $article->save();
     }
     function addStockNumberOnDeleteDefecteux($entity){
         $node = \Drupal::service('entity_parser.manager')->node_parser($entity);
         $article =   $node["field_article"]["#object"];
         $nbrStock = 0;
         if($article->field_quantite_stock 
               && $article->field_quantite_stock->value ){
            $nbrStock = $article->field_quantite_stock->value ;  
         }                           
         $article->field_quantite_stock->value = $nbrStock + $node["field_quantite"];
         $article->save();
     }
     function addStockNumberOnInsertCommande($entity){
      $commande = \Drupal::service('entity_parser.manager')->node_parser($entity);
        $articles =   $commande["field_articles"];
        foreach ($articles as $article) {
         $para = \Drupal::service('entity_parser.manager')->paragraph_parser($article["id"]);
         $article = $para['field_article']["#object"];
         $nbrStock = 0;
             if($article->field_quantite_stock 
                && $article->field_quantite_stock->value ){
                $nbrStock = $article->field_quantite_stock->value ;                            
             }
             $article->field_quantite_stock->value = $nbrStock - $para["field_quantite"];
             $article->save();
        }
     }
     function decreaseStockNumberOnCancelCommande($entity){
      $commande = \Drupal::service('entity_parser.manager')->node_parser($entity);
        $articles =   $commande["field_articles"];
        foreach ($articles as $article) {
         $para = \Drupal::service('entity_parser.manager')->paragraph_parser($article["id"]);
         $article = $para['field_article']["#object"];
         $nbrStock = 0;
             if($article->field_quantite_stock 
                && $article->field_quantite_stock->value ){
                $nbrStock = $article->field_quantite_stock->value ;                            
             }
             $article->field_quantite_stock->value = $nbrStock + $para["field_quantite"];
             $article->save();
        }
     }
     function updateStockNumberOnDeleteStock($entity){
        $article = $entity->field_article->entity;
        $nombre_par_unite = $article->field_nombre_par_unite->value ;   
        $stock_par_unite = $entity->field_quantite->value * $nombre_par_unite  ;
        $nbrStock = 0;
        if($article->field_quantite_stock 
            && $article->field_quantite_stock->value ){
            $nbrStock = $article->field_quantite_stock->value ;
        }
        $article->field_quantite_stock->value = $nbrStock - $stock_par_unite;
        return $article->save();
     }
     function executeStockInsert($entity){
        $article = $entity->field_article->entity;
      
        // stock update
        $nbrStock = 0;
        if($article->field_quantite_stock 
            && $article->field_quantite_stock->value ){
            $nbrStock = $article->field_quantite_stock->value ;
        }
        $nombre_par_unite = $article->field_nombre_par_unite->value ;   
        $qte_stock_par_boite  = $entity->field_quantite->value ;

        $qte_stock_par_unite = floatval($qte_stock_par_boite) * $nombre_par_unite  ;    ;
        $article->field_quantite_stock->value = $nbrStock +  $qte_stock_par_unite;

        // prix achat par pieces

 

        $achat_but = $entity->field_prix_achat_brut->value;

        $achat =  (floatval($achat_but)/$qte_stock_par_boite)/$nombre_par_unite ;
        $marge = $article->field_marge->value ;
        $entity->field_prix_d_achat->value =  $achat ;

        // prix vente
        $pv  =  $this->calculatePrixDeVente($achat , $marge )  ;

        // update article
      
        $article->field_prix_d_achat->value =  $achat ;
        $prix_v_actuelle = $article->field_prix_unitaire->value ;
        if( $prix_v_actuelle < $pv){
         \Drupal::messenger()->addMessage('Prix de vente actuelle : ' .$prix_v_actuelle.' AR est plus petit que prix de votre stock '.$pv . ' AR', 'warning');
        }
       // $article->field_prix_unitaire->value = $pv ;
        $entity->field_prix_unitaire->value = $pv ;
        $article->save();
        return  $entity ;
     }
     function executeComputedBenefice($entity){
         //   $achat = $entity->field_prix_d_achat->value;
         //   $article = $entity->field_article->entity;
         //   $marge = $article->field_marge->value ;
         //   $pv  =   $this->calculatePrixDeVente($achat , $marge)  ;
         //   return $pv - $achat ; 
         return false ;
     }

     
      
}
