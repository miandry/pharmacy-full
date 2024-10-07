<?php

namespace Drupal\article_management\Plugin\views\style;


use Drupal\rest\Plugin\views\style\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Provides a style plugin for custom JSON output.
 *
 * @ViewsStyle(
 *   id = "custom_rest_export",
 *   title = @Translation("Bootstrap Table REST Export"),
 *   help = @Translation("Custom REST Export with total and rows structure."),
 *   display_types = {"data"}
 * )
 */
class CustomRestExport extends Serializer {

  /**
   * {@inheritdoc}
   */
  public function render() {
    $original_pager = $this->view->pager;
    $this->view->pager = null;
    $this->view->execute();

    // Get the total number of rows across all pages.
    $total = $this->view->total_rows;

    // Restore the original pager.
    $this->view->pager = $original_pager;
    $this->view->execute();

     $structured_rows = [];
     foreach ($this->view->result as $row) {
       $structured_rows[] = $this->view->rowPlugin->render($row);
     }
 

     // Structure the output with 'total' and 'rows'.
     $output = [
       'total' => $total,
       'rows' => $structured_rows,  // Proper array structure, not a string.
     ];
 
    // Return the custom JSON structure.
    return json_encode($output);

  }

}
