<?php

namespace Drupal\tabt\Access;

use Drupal\tabt\Util\Enum\Tabt;

final class EntityViewPermissions {

  public function __invoke(): array {
    $permissions = [];

    foreach (Tabt::labels() as $entity_type_id => $label) {
      $permissions["view $entity_type_id tabt entities"] = [
        'title' => t("View @tabt_entity_label TabT entities", ['@tabt_entity_label' => $label]),
        'description' => t('Grants @tabt_entity_label view permissions', ['@tabt_entity_label' => $label]),
      ];
    }

    return $permissions;
  }

}
