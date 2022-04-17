<?php

namespace Drupal\tabt\Entity;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\tabt\Util\Enum\Tabt;

/**
 * @ContentEntityType (
 *   id = "tabt_tournament",
 *   label = @Translation("Tournament"),
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "list_builder" = "Drupal\tabt\Handler\ListBuilder\TournamentListBuilder",
 *   },
 *   base_table = "tabt_tournament",
 *   entity_keys = {
 *     "id" = "tid",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/tabt/tournament/list",
 *   }
 * )
 */
final class Tournament extends TabtEntityBase implements TournamentInterface {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['away_forfeited'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Away forfeited'))
      ->setDescription(t('An indication of a forfeit by the visiting team.'));

    $fields['away_team'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Away team'))
      ->setDescription(t('A reference to the visiting team.'))
      ->setTargetEntityTypeId(Tabt::TEAM)
      ->setRequired(FALSE);

    $fields['away_withdrawn'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Away withdrawn'))
      ->setDescription(t('An indication of a withdrawal by the visiting team.'));

    $fields['date'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Date'))
      ->setDescription(t('The date of the tournament.'))
      ->setRequired(FALSE);

    $fields['division'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Division'))
      ->setDescription(t('The division of the tournament.'))
      ->setTargetEntityTypeId(Tabt::DIVISION);

    $fields['home_forfeited'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Away forfeited'))
      ->setDescription(t('An indication of a forfeit by the home team.'));

    $fields['home_team'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Home team'))
      ->setDescription(t('A reference to the home team.'))
      ->setTargetEntityTypeId(Tabt::TEAM)
      ->setRequired(FALSE);

    $fields['home_withdrawn'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Home withdrawn'))
      ->setDescription(t('An indication of a withdrawal by the home team.'));

    $fields['score'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Score'))
      ->setDescription(t('The results of the tournament.'));

    $fields['tournament_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('TabT tournament ID'))
      ->setDescription(t('The TabT ID of the tournament.'));

    $fields['venue'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Venue'))
      ->setDescription(t('The venue of the tournament.'))
      ->setTargetEntityTypeId(Tabt::VENUE)
      ->setRequired(FALSE);

    $fields['week_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Week name'))
      ->setDescription(t('The week name of the tournament.'));

    return $fields;
  }

  public function getTournamentId(): string {
    return $this->get('tournament_id')->value;
  }

  public function getTournamentUniqueId(): int {
    return $this->get('tournament_unique_id')->value;
  }

  public function getWeekName(): string {
    return $this->get('week_name')->value;
  }

  public function getDate(): ?DrupalDateTime {
    $timestamp = $this->get('date')->value;

    return !empty($timestamp)
      ? DrupalDateTime::createFromTimestamp($timestamp)
      : NULL;
  }

  public function getHomeTeam(): ?TeamInterface {
    return Team::load($this->get('home_team')->target_id);
  }

  public function getAwayTeam(): ?TeamInterface {
    return Team::load($this->get('away_team')->target_id);
  }

  public function getScore(): ?string {
    return $this->get('score')->value;
  }

  public function getDivision(): DivisionInterface {
    return $this->get('division')->entity;
  }

  public function getVenue(): ?VenueInterface {
    return $this->get('venue')->entity;
  }

  public function isHomeForfeited(): bool {
    return $this->get('home_forfeited')->value;
  }

  public function isAwayForfeited(): bool {
    return $this->get('away_forfeited')->value;
  }

  public function isHomeWithdrawn(): bool {
    return $this->get('home_withdrawn')->value;
  }

  public function isAwayWithdrawn(): bool {
    return $this->get('away_withdrawn')->value;
  }

  public function getRawData(): array {
    return json_decode($this->get('raw_data')->value, TRUE);
  }

}
