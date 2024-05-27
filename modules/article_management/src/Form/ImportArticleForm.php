<?php

namespace Drupal\article_management\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
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

    return $form;
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
                    $result[$row][$c] = $data[$c] ;
                }
                $row++;
            }
            fclose($handle);
            }
    }
    $service_helpe = \Drupal::service('drupal.helper');
    $service_helpe->helper->storage_set('transfer_uploader',$result);
    foreach ($$result as $key => $value){

    }
  //  kint(    $result);
  // die();

  // \Drupal::messenger()->addMessage($this->t('CSV file imported successfully.'));
  }



}
