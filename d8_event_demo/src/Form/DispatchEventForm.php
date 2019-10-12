<?php

namespace Drupal\d8_event_demo\Form;

use Drupal\Core\Form\FormBase;
use Drupal\d8_event_demo\CustomEventClass;

class DispatchEventForm extends FormBase
{
  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId()
  {
    return 'event_dispatcher_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
  {
    $form['payload'] = [
      '#type'=>'email',
      '#title'=> 'Email Id'
    ];
    $form['clear_session_data'] = [
      '#type'=>'submit',
      '#value' => 'Send Email'
    ];
    return $form;
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state)
  {
    $payload = $form_state->getValue('payload');
    $event = new CustomEventClass($payload);
    $event_dispatcher = \Drupal::service('event_dispatcher');
    $event_dispatcher->dispatch(CustomEventClass::SEND_EMAIL,$event);
  }
}
