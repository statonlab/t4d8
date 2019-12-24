<?php

namespace Drupal\tripal;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Tripal Content entities.
 *
 * @ingroup tripal
 */
class TripalEntityListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['title'] = $this->t('Title');
    $header['type'] = $this->t('Type');
    $header['term'] = $this->t('Term');
    $header['author'] = $this->t('Author');
    $header['created'] = $this->t('Created');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {

    $type_name = $entity->getType();
    $type = $term = \Drupal\tripal\Entity\TripalEntityType::load($type_name);
    $term = $type->getTerm();

    $row['title'] = $this->l(
      $entity->getTitle(),
      new Url(
        'entity.tripal_entity.canonical', array(
          'tripal_entity' => $entity->id(),
        )
      )
    );

    $row['type'] = $type->getLabel();
    $row['term'] = $term->getVocab()->getLabel() . ':' . $term->getAccession();
    $row['author'] = $entity->getOwner()->getDisplayName();
    $row['created'] = \Drupal::service('date.formatter')->format($entity->getCreatedTime());
    
    return $row + parent::buildRow($entity);
  }

}
