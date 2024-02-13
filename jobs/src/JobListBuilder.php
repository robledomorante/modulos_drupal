<?php

namespace Drupal\jobs;


use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Job entities
 * 
 * @ingroup jobs
 */

 class JobListBuilder extends EntityListBuilder {

    /**
     * {@inheritdoc}
     */

     public function buildHeader()
     {
        $header['id'] = 'ID';
        $header['label'] = 'Empledo';

        return $header + parent::buildHeader();

     }

     public function buildRow(EntityInterface $entity)
     {                                                
        $row['id'] = $entity->id();
        $row['label'] = Link::createFromRoute(
            $entity->label(),
            'entity.job.canonical',
            ['job' => $entity->id()]

        );
        return $row + parent::buildRow($entity);
     }

 }
