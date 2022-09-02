<?php

namespace Drupal\tabt_team_management\Element;

use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\Core\Form\FormStateInterface;

/**
 * @FormElement("player_autocomplete")
 */
class PlayerAutocomplete extends EntityAutocomplete {

  public function getInfo(): array {
    $info = parent::getInfo();
    $info['#target_type'] = 'tabt_member';

    return $info;
  }

  public static function processEntityAutocomplete(
    array &$element,
    FormStateInterface $form_state,
    array &$complete_form
  ): array {
    $element = parent::processEntityAutocomplete($element, $form_state, $complete_form);
    $element['#autocomplete_route_name'] = 'tabt_team_management.entity_autocomplete.player';
    $element['#autocomplete_route_parameters']['tournament'] = $element['#tournament']->id();
    $element['#autocomplete_route_parameters']['team'] = $element['#team']->id();

    return $element;
  }

}
