<?php

namespace Drupal\tabt\Entity;

/**
 * @ContentEntityType (
 *   id = "tabt_venue",
 *   label = @Translation("Venue"),
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "tabt_venue",
 *   entity_keys = {
 *     "id" = "tid",
 *     "label" = "title",
 *   }
 * )
 */
final class Venue extends TabtEntityBase implements VenueInterface {

}
