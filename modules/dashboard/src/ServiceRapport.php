<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/03/2018
 * Time: 14:49
 */

namespace Drupal\dashboard;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\node\Entity\Node;

class ServiceRapport
{

       function valeurDuStock(){
         // Get the entity query service.
        // Get the entity query service for nodes.
            $entity_query = \Drupal::entityQuery('node');

            // Query for article nodes.
            $nids = $entity_query->condition('type', 'article')
            ->execute();

                // Load the nodes.
            $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
            $sum = 0 ;
            // Output the node titles.
            foreach ($nodes as $article) {
               $stock =  $article->field_quantite_stock->value ;
               $pv = $article->field_prix_unitaire->value  ;
               $sum  = $sum  + floatval($pv)*floatval($stock);

            }
            return ($sum);
       }
       function getStockBas(){
        $entity_query = \Drupal::entityQuery('node');
        $nids = $entity_query->condition('type', 'article')
        ->execute();
        $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
        $stockbas=[] ;
        foreach ($nodes as $article) {
           $stock =  $article->field_quantite_stock->value ;
           if($article->field_limite_stock){
                $min =  $article->field_limite_stock->value ;
                if( 0 < $stock &&  $stock <= $min){
                    $t = \Drupal::service('entity_parser.manager')->node_parser($article) ;
                    $stockbas[] = [
                        'id' => $t['nid'],
                        'name' => $t['title'],
                        'stock' => $t['field_quantite_stock']
                    ];
                }
           }
        }
        return $stockbas;
       }
       function stockBas($min){
        $entity_query = \Drupal::entityQuery('node');
        $nids = $entity_query->condition('type', 'article')
        ->execute();
        $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
        $stockbas=[] ;
        foreach ($nodes as $article) {
           $stock =  $article->field_quantite_stock->value ;
           if($article->field_limite_stock){
                $min =  $article->field_limite_stock->value ;
                if( 0 < $stock &&  $stock <= $min){
                $stockbas[] = $article ;
                }
           }
        }
        return sizeof($stockbas);
       }
       function stockRuputure(){
        $entity_query = \Drupal::entityQuery('node');
        $nids = $entity_query->condition('type', 'article')
        ->execute();
        $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
        $stockbas=[] ;
        foreach ($nodes as $article) {
           $stock =  $article->field_quantite_stock->value ;
           if(  $stock <= 0){
            $stockbas[] = $article ;
           }
        }
        return sizeof($stockbas);
       }
       function topAchactParMois(){

       }
       function topVenteParMois(){
            // Get the current date.
            $current_date = \Drupal::service('datetime.time')->getCurrentTime();

            // Get the first and last day of the current month.
            $first_day_of_month = strtotime('first day of this month', $current_date);

            $entity_query = \Drupal::entityQuery('node');
            // Query for article nodes.
            $nids = $entity_query->condition('type', 'commande')
            ->condition('field_date',$first_day_of_month, '>=')
            ->condition('field_status','payed', '=')
            ->sort('field_date','desc')
            ->range(0,10)
            ->execute();     
                // Load the nodes.

            $top = [] ;
            $sorts=[];
            // Output the node titles.
            foreach ($nids as $nid) {
                $commande = \Drupal::service('entity_parser.manager')->node_parser($nid);
                $paras =   $commande["field_articles"];
                foreach ( $paras as $p) {
                    $para = \Drupal::service('entity_parser.manager')->paragraph_parser($p["id"]);
                    if(isset($para['field_article']) && isset($para['field_article']["nid"])){
                        $id = $para['field_article']["nid"];
                        $top[$id]['name'] = $para['field_article']['title'];
                        if(!isset($top[$id]['num'])){
                            $top[$id]['num'] = 0 ;
                        }
                        $top[$id]['num'] =  $top[$id]['num'] + floatval($para["field_quantite"]);   
                        $sorts[$id] = $top[$id]['num'] ; 
                    }
             
                }
            }
            uasort($sorts, function($a, $b) {
                return $b - $a;
            });
            $sort_final = [];
            foreach ($sorts as $key =>  $s) {
                $sort_final[] =  $top[$key];
            }

            return  $sort_final;   
       }
       function totalVenteParMois(){
            // Get the current date.
            $current_date = \Drupal::service('datetime.time')->getCurrentTime();

            // Get the first and last day of the current month.
            $first_day_of_month = strtotime('first day of this month', $current_date);

            $entity_query = \Drupal::entityQuery('node');
            // Query for article nodes.
            $nids = $entity_query->condition('type', 'commande')
            ->condition('field_date',$first_day_of_month, '>=')
            ->condition('field_status','payed', '=')
            ->execute();     
                // Load the nodes.
            $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
            $sum = 0 ;
            // Output the node titles.
            foreach ($nodes as $commande) {
                $pv = $commande->field_total_vente->value  ;
                $sum  = $sum  + floatval($pv) ;
                            
            }
            return ($sum);
       }
       function totalAchatParMois(){
        // Get the current date.
        $current_date = \Drupal::service('datetime.time')->getCurrentTime();

        // Get the first and last day of the current month.
        $first_day_of_month = strtotime('first day of this month', $current_date);

        $entity_query = \Drupal::entityQuery('node');
        // Query for article nodes.
        $nids = $entity_query->condition('type', 'commande')
        ->condition('field_date',$first_day_of_month, '>=')
        ->condition('field_status','payed', '=')
        ->execute();     
            // Load the nodes.
        $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
        $sum = 0 ;
        // Output the node titles.
        foreach ($nodes as $commande) {
            $pv = $commande->field_total_achat->value  ;
            $sum  = $sum  + floatval($pv) ;
                        
        }
        return ($sum);
       }
       function getDaysList(){
                    // Get the current year and month
            $currentYear = date('Y');
            $currentMonth = date('m');

            // Get the number of days in the current month
            $numDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

            // Initialize an empty array to store date strings
            $dateArray = array();

            // Loop through each day of the month
            for ($day = 1; $day <= $numDaysInMonth; $day++) {
                // Format the day and month with leading zeros if necessary
                $formattedDay = str_pad($day, 2, '0', STR_PAD_LEFT);
                $formattedMonth = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
                // Construct the date string
                $date = $currentYear . '-' . $formattedMonth . '-' . $formattedDay;
                // Add the date string to the array
                $dateArray[] = $date;
            }
            return  $dateArray;
       }
       function getMoisList(){
                    // Get the current year
            $currentYear = date('Y');

            // Initialize an empty array to store year-month strings
            $yearMonthArray = array();

            // Loop through each month of the current year
            for ($month = 1; $month <= 12; $month++) {
                // Format the month with leading zero if necessary
                $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);
                // Construct the year-month string
                $yearMonth = $currentYear . '-' . $formattedMonth;
                // Add the year-month string to the array
                $yearMonthArray[] = $yearMonth;
            }
            return  $yearMonthArray ;
       }
       function venteParAnnulle(){
            $current_year = date('Y');
            $start_date = new DrupalDateTime("{$current_year}-01-01T00:00:00");
            $start_timestamp = $start_date->getTimestamp();

            $entity_query = \Drupal::entityQuery('node');
            // Query for article nodes.
            $nids = $entity_query->condition('type', 'commande')
            ->condition('field_status','payed', '=')
            ->condition('field_date', $start_timestamp, '>=')
            ->execute();     
                // Load the nodes.
            $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
            $resultat=[];
            $resultat_achat=[];
            // Output the node titles.
            foreach ($nodes as $commande) {
                $dateString =  $commande->field_date->value ;
                $date = new \DateTime($dateString);
                $yearAndMonth = $date->format('Y-m');
                if(!isset($resultat[$yearAndMonth])){$resultat[$yearAndMonth] = 0 ;}
                if(!isset($resultat_achat[$yearAndMonth])){$resultat_achat[$yearAndMonth] = 0 ;}
                $resultat[$yearAndMonth] = $resultat[$yearAndMonth] + $commande->field_total_vente->value ;             
                $resultat_achat[$yearAndMonth] = $resultat_achat[$yearAndMonth] + $commande->field_total_achat->value ;      
            }
            $months = $this->getMoisList();
            $final_vente = [];
            $final_achat = [];
            foreach ($months as $mt) {
                if(!isset($resultat[$mt])){
                    $final_vente[$mt] = 0 ;
                }else{
                    $final_vente[$mt] = $resultat[$mt];
                }
                if(!isset($resultat_achat[$mt])){
                    $final_achat[$mt] = 0 ;
                }else{
                    $final_achat[$mt] = $resultat_achat[$mt];
                }
            }
            return [
                'vente' =>  $final_vente ,
                'achat' => $final_achat
            ];
       }
       function venteParMois(){
        // Get the current date.
        $current_date = \Drupal::service('datetime.time')->getCurrentTime();

        // Get the first and last day of the current month.
        $first_day_of_month = strtotime('first day of this month', $current_date);

        $entity_query = \Drupal::entityQuery('node');
        // Query for article nodes.
        $nids = $entity_query->condition('type', 'commande')
        ->condition('field_date',$first_day_of_month, '>=')
        ->condition('field_status','payed', '=')
        ->execute();     
            // Load the nodes.
        $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
        $resultat=[];
        // Output the node titles.
        foreach ($nodes as $commande) {
            if(!isset($resultat[$commande->field_date->value])){$resultat[$commande->field_date->value] = 0 ;}
            $resultat[$commande->field_date->value] = $resultat[$commande->field_date->value] + $commande->field_total_vente->value ;              
        }
        $days = $this->getDaysList();
        $final_vente = [];
        foreach ($days as $day) {
            if(!isset($resultat[$day])){
                $final_vente[$day] = 0 ;
            }else{
                $final_vente[$day] = $resultat[$day];
            }

        }
        return $final_vente;
   }
       function dateProche(){
                // Get the current timestamp
            $currentTimestamp = time();

            // Calculate the timestamp for 5 days ago
            $fiveDaysAgoTimestamp = strtotime('-5 days', $currentTimestamp);

            // Convert the timestamp to a DrupalDateTime object
            $fiveDaysAgoDateTime = new DrupalDateTime('@' . $fiveDaysAgoTimestamp);

            $entity_query = \Drupal::entityQuery('node');
            // Query for article nodes.
            $nids = $entity_query->condition('type', 'stock')
            ->condition('field_peremption', $fiveDaysAgoDateTime->format('Y-m-d\TH:i:s'), '>=')
            ->execute();   
            $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
            $result = [];
            foreach ($nodes as $stock) {
                if($stock->field_article && $stock->field_article->entity){
                   $result[$stock->id()] = $stock->field_article->entity->label();
                }
            }
             return $result ;
       }
    

}
