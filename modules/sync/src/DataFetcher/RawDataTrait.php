<?php

namespace Drupal\tabt_sync\DataFetcher;

trait RawDataTrait {

  protected function getRawData($entry): array {
    $raw_data = [];

    try {
      foreach ((array) $entry as $key => $value) {
        $clean_key = preg_replace('/\\x00.*\\x00/', '', $key);
        $raw_data[$clean_key] = is_object($value)
          ? $this->getRawData($value)
          : $value;
      }
    }
    catch (\Exception $exception) {}

    return $raw_data;
  }

}
