<?php

namespace Drupal\d8_event_demo;

use Symfony\Component\EventDispatcher\Event;

class CustomEventClass extends Event {
  // Create an event.
  const  SEND_EMAIL = 'd8_event_demo.send_email';
  // payload
  protected $payload;
  /**
   * EmailStaticsEvent constructor.
   * @param $payload
   */
  public function __construct($payload) {
    $this->payload = $payload;
  }
  /**
   * Return payload
   * @return string
   */
  public function getPayload(){
    return  $this->payload;
  }
}
