<?php

namespace Drupal\dashboard\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MyCustomForm.
 */
class BatchDashboardForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mymodule_my_custom_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
     $service = \Drupal::service('dashboard.manager');
    // $t = $service->getVenteParMois();
    // kint(sizeof($t));
    // die();
     //$t = $service->getBeneficeParJour();
     //kint($t);
     //die();
     $form['#bas_stock']=  sizeof($service->getQueryStockBas()); 
     $form['#top_vente'] = $service->getQueryTopVenteArticleParMois();
     $form['#rupture_stock'] =  $service->getStockRuputure(); 
     $form['#dateProche'] =  $service->dateProche(); 
     $form['#dateProche'] =  $service->dateProche(); 
     $form['#total_vente_par_jour'] = $service->getTotalVenteParJour();
     $form['#benefice'] = $service->getBeneficeParJour();
     
      $form['vente_par_mois'] = [
        '#type' => 'submit',
        '#value' => $this->t('Click pour Metter a jour le chart des ventes quotidiennes'),
        '#submit' => ['::submitFormOne'],
      ];
    // Text field example.
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Start Batch Process'),
     ];

      $form['#theme'] = 'dashboard_form_template';
      return $form;
  }


  /**
   * Submit handler for the first button.
   */
  public function submitFormOne(array &$form, FormStateInterface $form_state) {
    $service = \Drupal::service('dashboard.manager');
    $rs = $service->getVenteParMois();
    $result =[];
    foreach ($rs as $val) {
        $montant = $val->field_total_vente_value;
        $date = $val->field_date_value; ;
        if(!isset($result[$date])){  $result[$date]  = 0 ;}
        $result[$date] =   $result[$date] + $montant ;                  
    }

    $days = $service->getDaysList();
    $final_vente = [];
    foreach ($days as $day) {
        if(!isset($result[$day])){
            $final_vente[$day] = 0 ;
        }else{
            $final_vente[$day] = $result[$day];
        }
    }
    $config = \Drupal::configFactory()->getEditable('dashboard.list');
    $config->set('venteParMois', $final_vente);
    $config->save();
  }



  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
           // Start the batch process when the form is submitted.
      $batch = [
        'title' => $this->t('Processing tasks...'),
        'operations' => [],
        'finished' => '\Drupal\dashboard\Form\BatchDashboardForm::batchFinishedCallback',
        'init_message' => $this->t('Starting batch process...'),
        'progress_message' => $this->t('Processed @current out of @total.'),
        'error_message' => $this->t('The batch process encountered an error.'),
      ];
      $service = \Drupal::service('dashboard.manager');

    //   $nid_top_vente_par_mois = $service->getVenteParMois();
    //   foreach ($nid_top_vente_par_mois as $val) {
    //     $input['montant'] = $val->field_total_vente_value;
    //     $input['date'] = $val->field_date_value; ;
    //     $batch['operations'][] =  [
    //         '\Drupal\dashboard\Form\BatchDashboardForm::venteParMois' ,  [$input] 
    //     ];
    //   }
      batch_set($batch);
  }



    //ok
  public static function  valeurDuStock($input,&$context){
    $nid = $input['nid'];
    $article = \Drupal\node\Entity\Node::load($nid);
    $stock =  $article->field_quantite_stock->value ;
    $pv = $article->field_prix_unitaire->value  ;
    if(!isset($context['results']['valuer_stock'])){
        $context['results']['valuer_stock'] = 0 ;
    }
    $context['results']['valuer_stock'] = $context['results']['valuer_stock'] + floatval($pv)*floatval($stock);
   }



    /**
   * Batch finished callback.
   */
  public static function batchFinishedCallback($success, $results, $operations) {
    if ($success) {
      \Drupal::messenger()->addMessage(t('Batch process completed successfully.'));
       foreach ($results as $key => $result) {
        if($key == "venteParMois"){
            $service = \Drupal::service('dashboard.manager');
            $days = $service->getDaysList();
            $final_vente = [];
            foreach ($days as $day) {
                if(!isset($result[$day])){
                    $final_vente[$day] = 0 ;
                }else{
                    $final_vente[$day] = $result[$day];
                }
    
            }
            $config = \Drupal::configFactory()->getEditable('dashboard.list');
            $config->set('venteParMois', $final_vente);
            $config->save();
        }
     
      }
    }
    else {
      \Drupal::messenger()->addMessage(t('Batch process encountered an error.'), 'error');
    }
  }



}
