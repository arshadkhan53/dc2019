<?php

namespace Drupal\d8_event_demo\EventSubscriber;

use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Entity\EntityTypeEvents;
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\d8_event_demo\CustomEventClass;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;

class EventDemoClass implements EventSubscriberInterface
{

  /**
   * Returns an array of event names this subscriber wants to listen to.
   *
   * The array keys are event names and the value can be:
   *
   *  * The method name to call (priority defaults to 0)
   *  * An array composed of the method name to call and the priority
   *  * An array of arrays composed of the method names to call and respective
   *    priorities, or 0 if unset
   *
   * For instance:
   *
   *  * ['eventName' => 'methodName']
   *  * ['eventName' => ['methodName', $priority]]
   *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
   *
   * @return array The event names to listen to
   */
  /** kernal events
   *  KernelEvents::CONTROLLER
   *  KernelEvents::EXCEPTION
   *  KernelEvents::FINISH_REQUEST
   *  KernelEvents::REQUEST
   *  KernelEvents::RESPONSE
   *  KernelEvents::TERMINATE
   *  KernelEvents::VIEW
   * Ref: https://api.drupal.org/api/drupal/vendor!symfony!http-kernel!KernelEvents.php/class/KernelEvents/8.2.x
   *  no hook_boot or hook_init, instead use REQUEST event.
   */
  public static function getSubscribedEvents()
  {
    // Implement getSubscribedEvents() method.
    // kernal event
    $events[KernelEvents::EXCEPTION][] = ['doSomethingOnPageRequest', 30];
    // custom event.
    /// $events[CustomEventClass::SEND_EMAIL][] = ['senEmail'];
    // core event
   // $events[ConfigEvents::SAVE][] = ['onConfigSave'];
    // event to handle hook_form_alter
    //$events[HookEventDispatcherInterface::FORM_ALTER][] = ['handleFormAlter'];
    return $events;
  }

  public function doSomethingOnPageRequest(Event $event)
  {
    if (!$event->getRequest()->isXmlHttpRequest()) {
      $event->setResponse(new RedirectResponse(\Drupal::url('d8_event_demo.form'), 301, []));

      //dpm("Request event has occurred!");
    }
  }

  /**
   *  Listen to custom event.
   */
  function senEmail(CustomEventClass $event)
  {
    dpm("mail has been sent to " . $event->getPayload());
    \Drupal::service('config.factory')->getEditable('system.performance')->set('cache.page.enabled', 1)->save();
  }

  /**
   *  Listent to cor config save even.
   */
  function onConfigSave()
  {
    //exit;
    dpm("Config has been saved ");
  }

  /**
   *  implements hook_form_alter event
   */
  function handleFormAlter($event)
  {
    if ($event->getFormId() == "event_dispatcher_form") {
      $form = $event->getForm();
      $form[] = [
        '#type' => 'markup',
        '#markup' => '<h1>Its hook form alter event</h1>'
      ];
      $form[] = [
        '#type' => 'number',
        '#title' => 'Any number'
      ];
      $event->setForm($form);
      //kint($event);
    }

  }
}
