<?php

namespace Drupal\article_management\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
/**
 * Class CSVImportForm.
 */
class ImportArticleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_csv_importer_form';
  }

  

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $service_helpe = \Drupal::service('drupal.helper');
    $result = $service_helpe->helper->storage_get('transfer_uploader');
    if(is_array($result) && !empty($result)){
      $form['submit_two'] = [
        '#type' => 'submit',
        '#value' => $this->t('Submit Two'),
        '#submit' => ['::submitFormTwo'],
      ];
    }else{
      $form['upload'] = [
        '#type' => 'managed_file',
        '#required' => false,
        '#title' => $this->t('Upload Excel'),
        '#upload_location' => 'public://'.time(),
        '#description' => $this->t('File format allowed : xlsx,xls,csv'),
        '#upload_validators' => [
          'file_validate_extensions' => ['csv'],
        ],
      ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import'),
      '#button_type' => 'primary',
    ];

    }
  
    return $form;
  }
    /**
   * Méthode de soumission pour le deuxième bouton.
   */
  public function submitFormTwo(array &$form, FormStateInterface $form_state) {
      //\Drupal::messenger()->addMessage($this->t('Second button clicked.'));
       // Définir les opérations du batch.
       $operations = [];
       $service_helpe = \Drupal::service('drupal.helper');
       $result = $service_helpe->helper->storage_get('transfer_uploader');
       // Ajouter des opérations de traitement par lots
       $i = 0 ;
       foreach ($result as $item) {
         $operations[] = [
           '\Drupal\article_management\Form\ImportArticleForm::processBatch',
           [$item, $i],
         ];
         $i ++ ;
       }
   
       // Définir le batch.
       $batch = [
         'title' => $this->t('Processing Batch Operations'),
         'operations' => $operations,
         'finished' => '\Drupal\article_management\Form\ImportArticleForm::finishBatch',
       ];
   
       // Mettre en file d'attente et exécuter le batch.
       batch_set($batch);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // $validators = ['file_validate_extensions' => ['csv']];
    // $file = file_save_upload('csv_file', $validators, FALSE, 0);
    // if (!$file) {
    //   $form_state->setErrorByName('csv_file', $this->t('Please upload a valid CSV file.'));
    // } else {
    //   $file->setPermanent();
    //   $file->save();
    //   $form_state->setValue('csv_file', $file);
    // }
  }
  public function cleanString($string) {
    // Supprime les caractères non imprimables
    $cleanedString = preg_replace('/[^\x20-\x7E]/', '', $string);
    return $cleanedString;
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $form_file = $form_state->getValue('upload', 0);
 
    $id =   $form_file[0] ;
    $file = File::load($id);
    $uri = $file->getFileUri();
    $stream_wrapper_manager = \Drupal::service('stream_wrapper_manager')->getViaUri($uri);
    $file_path = $stream_wrapper_manager->realpath();
    if (file_exists($file_path)) {
        $result = [];
        $row = 1;
            if (($handle = fopen($file_path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $result[$row]=[];     
                for ($c=0; $c < $num; $c++) {
                    $result[$row][$c] = $this->cleanString($data[$c]);
                }
                $row++;
            }
            fclose($handle);
            }
    }
    $res_fix = [];
    $header = $result[1] ;
    $i = 1 ;
    foreach ($result as $key => $value){
      if($key > 1 && $value[2] !="" && $value[1]!=""){  
        foreach ($value as $key_c => $v){
          $res_fix[$i][$header[$key_c]] = $v ;
        }
        $i++ ;
      }
    }
    $service_helpe = \Drupal::service('drupal.helper');
    $service_helpe->helper->storage_set('transfer_uploader',$res_fix);
    $current_url = \Drupal::request()->getRequestUri();
    $response = new RedirectResponse($current_url);
    $response->send();
    return;

  }
  /**
   * Fonction de traitement par lots.
   */
  public static function processBatch($item, &$context) {
  
    \Drupal::service('crud')->save('node', 'article',   $item);
    
  }
  public static function nodeTitleDoesNotExist($title) {
    // Créez une requête pour les entités de type 'node'.
    $query = \Drupal::entityQuery('node')
      ->condition('title', $title)
      ->range(0, 1); // Limitez la recherche à un résultat.
  
    // Exécutez la requête.
    $nids = $query->execute();
  
    // Si aucun nœud n'est trouvé, la requête renvoie un tableau vide.
    return empty($nids);
  }

  /**
   * Fonction appelée une fois le traitement par lots terminé.
   */
  public static function finishBatch($success, $results, $operations) {
    $service_helpe = \Drupal::service('drupal.helper');
    $service_helpe->helper->storage_delete('transfer_uploader');
    if ($success) {
      \Drupal::messenger()->addMessage(t('Batch processing completed successfully.'));
    }
    else {
      \Drupal::messenger()->addMessage(t('Batch processing encountered errors.'));
    }
  }



}
