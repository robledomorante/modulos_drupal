<?php

namespace Drupal\jobs\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\jobs\JobInterface;


/**
 * Defines the job entity class
 * 
 * @ContentEntityType(
 *   id = "job",
 *   label = @Translation("Empleo"),
 *   label_collection = @Translation("Empleos"),
 *   label_singular = @Translation("empleo"),
 *   label_plural = @Translation("empleos"),
 *   label_count = @PluralTranslation(
 *     singular = "@count empledo",
 *     plural = "@count empleos",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\jobs\JobListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\jobs\Form\JobForm",
 *       "edit" = "Drupal\jobs\Form\JobForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\jobs\JobHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "job",
 *   admin_permission = "administer jobs",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/job",
 *     "add-form" = "/job/add",
 *     "canonical" = "/job/{job}",
 *     "edit-form" = "/job/{job}/edit",
 *     "delete-form" = "/job/{job}/delete",
 *   },
 *   field_ui_base_route = "entity.job.settings",
 *  )
 */

class job extends ContentEntityBase implements JobInterface
{
    use EntityChangedTrait;



    /**
     * 
     * @return type
     */
    // id de la entidad
    public function getUId()
    {
        return $this->get('id')->target_id;
    }

    public function setUId($id)
    {
        $this->set('id', $id);
        return $this;
    }

    // FECHA DE CREACCION
    public function getcreated()
    {
        return $this->get('created')->value;
    }

    public function setcreated($created)
    {
        $this->set('created', $created);
        return $this;
    }

    // OBSERVACIONES

    public function getdescription()
    {
        return $this->get('description')->value;
    }

    public function setdescription($description)
    {
        $this->set('description', $description);
        return $this;
    }

    // STATUS (TERMINADO EL FICHAJE)

    public function getstatus()
    {
        return $this->get('status')->value;
    }

    public function setstatus($status)
    {
        $this->set('status', $status);
        return $this;
    }

    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {

        // Agrega los campos id, uuid
        $fields = parent::baseFieldDefinitions($entity_type);

        $fields['uuid'] = BaseFieldDefinition::create('uuid')
            ->setLabel(t('uuid'))
            ->setDescription(t('The node UUID.'))
            ->setReadOnly(TRUE);

        // core/lib/Drupal/Core/Field/Plugin/Field/FieldType/StringItem.php
        $fields['label'] = BaseFieldDefinition::create('string')
            ->setLabel(t('Job'))
            ->setDescription(t('trade etiquette. Job'))
            ->setSettings([
                'max_length' => 50,
                'text_processing' => 0,
            ])
            ->setDefaultValue('')
            ->setDisplayOptions('view', [
                'label' => 'above',
                'type' => 'string',
                'weight' => -4,
            ])
            ->setDisplayOptions('form', [
                'type' => 'string_textfield',
                'weight' => -4,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        // core/lib/Drupal/Core/Field/Plugin/Field/FieldType/BooleanItem.php
        $fields['status'] = BaseFieldDefinition::create('boolean')
            ->setLabel(t('Status'))
            ->setDefaultValue(FALSE)
            ->setDescription(t('End of the working day.'))
            ->setSetting('on_label', 'Enabled')
            ->setDisplayOptions('form', [
                'type' => 'boolean_checkbox',
                'settings' => [
                    'display_label' => FALSE,
                ],
                'weight' => 0,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayOptions('view', [
                'type' => 'boolean',
                'label' => 'above',
                'weight' => 0,
                'settings' => [
                    'format' => 'enabled-disabled',
                ],
            ])
            ->setDisplayConfigurable('view', TRUE);

        // core/lib/Drupal/Core/Field/Plugin/Field/FieldType/TextLongItem.php
        $fields['description'] = BaseFieldDefinition::create('string')
            ->setLabel(t('Description'))
            //->setRequired(TRUE)
            ->setSetting('max_length', 255)
            ->setDisplayOptions('form', [
                'type' => 'string_textfield',
                'weight' => 10,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayOptions('view', [
                'label' => 'hidden',
                'type' => 'string',
                'weight' => 10,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);
            

        $fields['created'] = BaseFieldDefinition::create('created')
            ->setLabel(t('Created'))
            ->setDescription(t('The time that the entity was created.'))
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);


        return $fields;
    }
}
