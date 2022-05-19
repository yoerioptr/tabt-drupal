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
 *     "access" = "Drupal\tabt\Handler\Access\TabtAccessControlHandler",
 *     "list_builder" = "Drupal\tabt\Handler\ListBuilder\TeamListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\tabt\Handler\RouteProvider\TabtRouteProvider",
 *     },
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "tabt_team",
 *   entity_keys = {
 *     "id" = "tid",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "canonical" = "/tabt/team/{tabt_team}",
 *     "collection" = "/admin/tabt/team",
 *   },
 * )
 */
final class Team extends TabtEntityBase implements TeamInterface {

  public function getTeamId(): string {
    return $this->get('team_id')->value;
  }

  public function getDivision(): ?DivisionInterface {
    return $this->get('division')->entity;
  }

  public function isExternal(): bool {
    return $this->get('external')->value;
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['division'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Division'))
      ->setDescription(t("The division's category ID."))
      ->setTargetEntityTypeId(Tabt::DIVISION)
      ->setSetting('target_type', Tabt::DIVISION)
      ->setSetting('handler', 'default');

    $fields['team_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Team id'))
      ->setDescription(t("The team's ID."));

    $fields['external'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('External'))
      ->setDescription(t("Flag that indicates if the team is external"));

    return $fields;
  }

}
