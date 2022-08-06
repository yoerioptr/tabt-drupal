<?php

namespace Drupal\tabt\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

abstract class TabtEntityBase extends ContentEntityBase implements TabtEntityInterface {

  public function getId(): int {
    return $this->get('tid')->value;
  }

  public function getUuid(): string {
    return $this->get('uuid')->value;
  }

  public function getRawData(): string {
    return $this->get('raw_data')->value;
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields['tid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the tabt entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the tabt entity.'))
      ->setReadOnly(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The title of the tabt entity.'))
      ->setReadOnly(TRUE);

    $fields['raw_data'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Raw data'))
      ->setDescription(t('The raw data of the tabt entity.'))
      ->setReadOnly(TRUE);

    return $fields;
  }

}
