<?php

namespace Drupal\config_entity_revisions;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Entity\RevisionableStorageInterface;

interface ConfigEntityRevisionsStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets the latest published revision ID of the entity.
   *
   * @param int $config_entity_id
   *   The config entity ID to match.
   *
   * @return int
   *   The identifier of the latest published revision of the entity, or NULL
   *   if the entity does not have a published revision.
   */
  public function getLatestPublishedRevisionID($config_entity_id);

  /**
   * Gets the latest published revision of the entity.
   *
   * @param int $config_entity_id
   *   The config entity ID to match.
   *
   * @return RevisionableStorageInterface;
   *   The identifier of the latest published revision of the entity, or NULL
   *   if the entity does not have a published revision.
   */
  public function getLatestPublishedRevision($config_entity_id);

  /**
   * Gets the latest revision of the entity.
   *
   * @param int $config_entity_id
   *   The config entity ID to match.
   *
   * @return RevisionableStorageInterface;
   *   The identifier of the latest revision of the entity, or NULL
   *   if the entity does not have a revision.
   */
  public function getLatestRevision($config_entity_id);

}