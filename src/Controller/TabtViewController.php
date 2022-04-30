<?php

namespace Drupal\tabt\Controller;

use Drupal\Core\Entity\Controller\EntityViewController;
use Drupal\Core\Entity\EntityInterface;
use Drupal\tabt\Entity\TabtEntityInterface;

final class TabtViewController extends EntityViewController {

  /**
   * @param \Drupal\tabt\Entity\TabtEntityInterface $tabt
   * @param string $view_mode
   *
   * @return array
   */
  public function view(EntityInterface $tabt, $view_mode = 'full'): array {
    $page = $this->entityTypeManager
      ->getViewBuilder($tabt->getEntityTypeId())
      ->view($tabt, $view_mode);

    $page['#pre_render'][] = [$this, 'buildTitle'];
    $page['#entity_type'] = $tabt->getEntityTypeId();
    $page["#{$page['#entity_type']}"] = $tabt;

    $page['#cache']['contexts'][] = 'url.site';

    return $page;
  }

  public function title(TabtEntityInterface $tabt): string {
    return $tabt->label();
  }

}
