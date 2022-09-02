<?php

namespace Drupal\tabt\Handler\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\tabt\Entity\TabtEntityInterface;

class TabtAccessControlHandler extends EntityAccessControlHandler {

  /**
   * @param \Drupal\tabt\Entity\TabtEntityInterface $entity
   * @param string $operation
   * @param \Drupal\Core\Session\AccountInterface $account
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   */
  protected function checkAccess(
    EntityInterface $entity,
    $operation,
    AccountInterface $account
  ): AccessResultInterface {
    switch ($operation) {
      case 'view':
        $access_result = $this->entityViewAccess($entity, $account);
        break;

      default:
        $access_result = NULL;
    }

    return $access_result ?: parent::checkAccess($entity, $operation, $account);
  }

  private function entityViewAccess(
    TabtEntityInterface $entity,
    AccountInterface $account
  ): ?AccessResultInterface {
    if ($account->hasPermission('access tabt overview')) {
      return AccessResult::allowed();
    }

    if ($account->hasPermission("view {$entity->getEntityTypeId()} tabt entities")) {
      return AccessResult::allowed();
    }

    return NULL;
  }

}
