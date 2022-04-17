<?php

namespace Drupal\tabt_sync\Model;

final class Venue extends SyncableItemBase {

  private string $name;

  private string $street;

  private string $town;

  private ?string $phone;

  private string $comment;

  public function __construct(
    string $name,
    string $street,
    string $town,
    ?string $phone,
    string $comment
  ) {
    $this->name = $name;
    $this->street = $street;
    $this->town = $town;
    $this->phone = $phone;
    $this->comment = $comment;
  }

  public function getComment(): string {
    return $this->comment;
  }

  public function getName(): string {
    return $this->name;
  }

  public function getPhone(): ?string {
    return $this->phone;
  }

  public function getStreet(): string {
    return $this->street;
  }

  public function getTown(): string {
    return $this->town;
  }

}
