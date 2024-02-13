<?php

namespace Drupal\jobs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\user\UserInterface;

/**
 * Provides an interface defining a job entity type
 */

interface JobInterface extends ContentEntityInterface
{
    public function getUId();

    public function setUId($id);

    public function getcreated();

    public function setcreated($created);

    public function getdescription();

    public function setdescription($description);

    public function getstatus();

    public function setstatus($status);
}
