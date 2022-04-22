<?php

namespace Drupal\tabt\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * @ContentEntityType (
 *   id = "tabt_division",
 *   label = @Translation("Division"),
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "list_builder" = "Drupal\tabt\Handler\ListBuilder\DivisionListBuilder",
 *     "access" = "Drupal\tabt\Handler\Access\TabtAccessControlHandler",
 *   },
 *   base_table = "tabt_division",
 *   entity_keys = {
 *     "id" = "tid",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/tabt/division/list",
 *   }
 * )
 */
final class Division extends TabtEntityBase implements DivisionInterface {

  public function getDivisionId(): int {
    return $this->get('division_id')->value;
  }

  public function getDivisionCategory(): int {
    return $this->get('division_category')->value;
  }

  public function getDivisionName(): string {
    return $this->get('title')->value;
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['division_category'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Division category'))
      ->setDescription(t("The division's category ID."));

    $fields['division_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Division id'))
      ->setDescription(t("The division's ID."));

    return $fields;
  }

}
