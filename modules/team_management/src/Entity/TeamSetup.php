<?php

namespace Drupal\tabt_team_management\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\tabt\Entity\MemberInterface;
use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt\Entity\TournamentInterface;
use Drupal\tabt\Util\Enum\Tabt;

/**
 * @ContentEntityType (
 *   id = "tabt_team_setup",
 *   label = @Translation("Team setup"),
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "tabt_team_setup",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 * )
 */
final class TeamSetup extends ContentEntityBase implements TeamSetupInterface {

  public function label() {
    return $this->id();
  }

  public function getId(): int {
    return $this->get('tid')->value;
  }

  public function getUuid(): string {
    return $this->get('uuid')->value;
  }

  public function getTournament(): TournamentInterface {
    return $this->get('tournament')->entity;
  }

  public function getTeam(): TeamInterface {
    return $this->get('team')->entity;
  }

  public function getPlayers(): array {
    return $this->get('members')->referencedEntities();
  }

  public function removePlayer(MemberInterface $member): void {

  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the entity.'))
      ->setReadOnly(TRUE);

    $fields['tournament'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the entity.'))
      ->setRequired(TRUE)
      ->setTargetEntityTypeId(Tabt::TOURNAMENT)
      ->setSetting('target_type', Tabt::TOURNAMENT)
      ->setSetting('handler', 'default');

    $fields['team'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the entity.'))
      ->setRequired(TRUE)
      ->setTargetEntityTypeId(Tabt::TEAM)
      ->setSetting('target_type', Tabt::TEAM)
      ->setSetting('handler', 'default');

    $fields['members'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the entity.'))
      ->setTargetEntityTypeId(Tabt::MEMBER)
      ->setCardinality(4)
      ->setSetting('target_type', Tabt::MEMBER)
      ->setSetting('handler', 'default');

    return $fields;
  }

}
