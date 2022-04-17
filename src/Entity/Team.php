<?php

namespace Drupal\tabt\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\tabt\Util\Enum\Tabt;

/**
 * @ContentEntityType (
 *   id = "tabt_team",
 *   label = @Translation("Team"),
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "list_builder" = "Drupal\tabt\Handler\ListBuilder\TeamListBuilder",
 *   },
 *   base_table = "tabt_team",
 *   entity_keys = {
 *     "id" = "tid",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/tabt/team/list",
 *   }
 * )
 */
final class Team extends TabtEntityBase implements TeamInterface {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['division'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Division'))
      ->setDescription(t("The division's category ID."))
      ->setTargetEntityTypeId(Tabt::DIVISION)
      ->setRequired(FALSE);

    $fields['team_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Team id'))
      ->setDescription(t("The team's ID."));

    $fields['external'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('External'))
      ->setDescription(t("Flag that indicates if the team is external"));

    return $fields;
  }

  public function getTeamId(): string {
    return $this->get('team_id')->value;
  }

}
