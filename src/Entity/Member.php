<?php

namespace Drupal\tabt\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * @ContentEntityType (
 *   id = "tabt_member",
 *   label = @Translation("Member"),
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "tabt_member",
 *   entity_keys = {
 *     "id" = "tid",
 *     "first_name" = "first_name",
 *     "label" = "title",
 *     "last_name" = "last_name",
 *     "postition" = "position",
 *     "ranking" = "ranking",
 *     "ranking_index" = "ranking_index",
 *     "unique_index" = "unique_index",
 *     "uuid" = "uuid",
 *   }
 * )
 */
final class Member extends TabtEntityBase implements MemberInterface {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First name'))
      ->setDescription(t("The member's first name"));

    $fields['last_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last name'))
      ->setDescription(t("The member's last name"));

    $fields['position'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Position'))
      ->setDescription(t("The member's position"));

    $fields['ranking'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Ranking'))
      ->setDescription(t("The member's ranking"));

    $fields['ranking_index'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Ranking index'))
      ->setDescription(t("The member's ranking index"));

    $fields['unique_index'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Unique index'))
      ->setDescription(t("The member's unique index"));

    return $fields;
  }

  public function getPosition(): int {
    return $this->get('position')->value;
  }

  public function getUniqueIndex(): int {
    return $this->get('unique_index')->value;
  }

  public function getRankingIndex(): int {
    return $this->get('ranking_index')->value;
  }

  public function getFirstName(): string {
    return $this->get('first_name')->value;
  }

  public function getLastName(): string {
    return $this->get('last_name')->value;
  }

  public function getRanking(): string {
    return $this->get('ranking')->value;
  }

}
