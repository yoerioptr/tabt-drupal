<?php

namespace Drupal\tabt\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

interface TabtEntityInterface extends ContentEntityInterface {

  public function getId(): int;

  public function getRawData();

}
