<?php

namespace Drupal\mz_message;

use Drupal\message\Entity\Message;

/**
 * Class MessageService.
 */
class MessageService {

  /**
   * Constructs a new MessageService object.
   */
  public function __construct() {

  }
  public function isExistMessageTemplate($name_template){
    $templates = \Drupal::entityTypeManager()->getStorage('message_template')->loadMultiple(); 
    if($name_template && isset($templates[$name_template])){
       return true ;
    }
    return false ;
  }
  public function getLabelTemplate($name_template){
    $templates = \Drupal::entityTypeManager()->getStorage('message_template')->loadMultiple(); 
    if($name_template && isset($templates[$name_template])){
      return $templates[$name_template]->label() ;
    }
    return false ;
  }
  public function processMessage($action,$entity){
      $account = \Drupal::currentUser();
      $message = Message::create(['template' => $action, 'uid' => $account->id()]);
      $is_refered = $message->hasField('reference');
      if($is_refered == false){
        $message = "Field reference not exist in template ".$action;
        \Drupal::logger('mz_message')->error($message);
        return false ;
      }
      $message->set('reference', $entity);
      $status = $message->hasField('emails') ;
      $message->save();
      if($status){
          $email = $message->getFieldDefinition('emails')->getDefaultValueLiteral();
          if(!empty($email)){
            //  $email_list = $email[0]['value'] ;
              $array['message'] =   $message ;
              $array['emails'] = $email;
              $array['action'] =   $this->getLabelTemplate($action);
              \Drupal::service('mz_message.default')->send_mail_queue($array);

            //  $queue = \Drupal::queue('message_email_queue');
            //  $queue->createItem($array);
          }
      }
  }
  function getTextString($message_object,$i = 0){
    if(is_object($message_object)){
        $token_service = \Drupal::token();
        $message = $message_object->getText(); 
        $reference =  $message_object->reference->entity ;
        $token_service = \Drupal::token();
        return $token_service->replace( $message[$i] ,  ['node'=>$reference]);
    }
    return false ;
  }
  function send_mail_queue($data){
      $message_object = $data['message'];
      $email_list = $data['emails'];
      $action = $data['action'] ;

      $message_string = $this->getTextString($message_object)  ;  
      $status = $message_object->hasField('send_owner') ;
      if( $status){
        $is_onwer_send = $message_object->getFieldDefinition('send_owner')->getDefaultValueLiteral();
        if(!empty($is_onwer_send) && $is_onwer_send[0]['value'] == 1){
            $reference =  $message_object->reference->entity ;
            $user = $reference->getOwner();
            $email_user = $user->getEmail();
            $this->send_mail_simple($message_string,$email_user,$action);
        }
      }
   
      foreach($email_list as $key => $email){
        $text_messages = $this->getTextString($message_object,$key)  ;  
        $email_items = explode(',',$email['value']);
        foreach($email_items as $key => $email_item){
          $this->send_mail_simple($text_messages,$email_item ,$action);
        }
      }
     
  }

  function send_mail_simple($message,$to,$subject) {
    $site_mail = \Drupal::config('system.site')->get('mail');
   // $to = "miandrilala9@yahoo.fr";
   //  $subject = "Download link for file";
   // $message = "Click the link below to download the file:<br>";
   // $message .= "<a href='http://example.com/path/to/file.pdf'>Download File</a>";

    // Headers
    $headers = "From: booking@travelbugusa.com\r\n";
    $headers .= "Reply-To: booking@travelbugusa.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    // Send email
    if (mail($to, $subject, $message, $headers)) {
       $message = "Email sent successfully.";
       \Drupal::logger('mz_message')->notice($message);
    } else {
       $message = "Email sending failed.";
      \Drupal::logger('mz_message')->error($message);
    }
    
  }

    function manageMessage( $action, $entity ) {   
        $bundle  = $entity->bundle() ;     
        if( $entity->moderation_state && $entity->moderation_state->value) {
            $curent_state = ($entity->moderation_state->value);
           
            $action = $bundle."_moderation_".$curent_state ;      
            $curent_old = ($entity->original && $entity->original->moderation_state)?($entity->original->moderation_state->value) : null;
            $action_priority = false ;
            if($curent_old ){
                $action_priority = $bundle."_moderation_".$curent_old ."_to_".$curent_state;
            }
          
            //moderation
            $status = $this->isExistMessageTemplate($action_priority);
            if( $status ) {
                $action = $action_priority ;
            }  
  
               
        } else{
            //update insert delete  
            $action_base = $bundle."_".$action ;
            $status = $this->isExistMessageTemplate($action_base);
            if( $status ) {
                $action = $action_base ;
            }   
        }
        \Drupal::logger('mz_message')->notice("notifiction:".$action);        
        return $this->processMessage($action, $entity);
    }
}
