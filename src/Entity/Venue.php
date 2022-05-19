<?php

namespace Drupal\tabt\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * @ContentEntityType (
 *   id = "tabt_venue",
 *   label = @Translation("Venue"),
 *   handlers = {
 *     "access" = "Drupal\tabt\Handler\Access\TabtAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\tabt\Handler\RouteProvider\TabtRouteProvider",
 *     },
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "tabt_venue",
 *   entity_keys = {
 *     "id" = "tid",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "canonical" = "/tabt/venue/{tabt_venue}",
 *   }
 * )
 */
final class Venue extends TabtEntityBase implements VenueInterface {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['address'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Address'))
      ->setDescription(t("The venue's address."));

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'))
      ->setDescription(t("The venue's description."));

    $fields['phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Phone'))
      ->setDescription(t("The venue's phone number."));

    return $fields;
  }

}
