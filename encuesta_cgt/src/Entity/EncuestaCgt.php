<?php

namespace Drupal\encuesta_cgt\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\encuesta_cgt\EncuestaCgtInterface;

/**
 * Defines the encuesta cgt entity class.
 *
 * @ContentEntityType(
 *   id = "encuesta_cgt",
 *   label = @Translation("Encuesta cgt"),
 *   label_collection = @Translation("Encuesta cgts"),
 *   label_singular = @Translation("encuesta cgt"),
 *   label_plural = @Translation("encuesta cgts"),
 *   label_count = @PluralTranslation(
 *     singular = "@count encuesta cgts",
 *     plural = "@count encuesta cgts",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\encuesta_cgt\EncuestaCgtListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\encuesta_cgt\Form\EncuestaCgtForm",
 *       "edit" = "Drupal\encuesta_cgt\Form\EncuestaCgtForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "encuesta_cgt",
 *   admin_permission = "administer encuesta cgt",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/encuesta-cgt",
 *     "add-form" = "/admin/content/encuesta-cgt/add",
 *     "canonical" = "/admin/content/encuesta-cgt/{encuesta_cgt}",
 *     "edit-form" = "/admin/content/encuesta-cgt/{encuesta_cgt}/edit",
 *     "delete-form" = "/admin/content/encuesta-cgt/{encuesta_cgt}/delete",
 *   },
 *   field_ui_base_route = "entity.encuesta_cgt.settings",
 * )
 */
class EncuestaCgt extends ContentEntityBase implements EncuestaCgtInterface
{

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
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

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'label' => 'above',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the encuesta cgt was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the encuesta cgt was last edited.'));

    $fields['pregunta1'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Pregunta 1'))
      ->setDescription(t('Do you think you have had a significant loss of purchasing power in recent years.'))
      ->setDefaultValue(TRUE)
      ->setSettings(['on_label' => 'Published', 'off_label' => 'Unpublished'])
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'boolean',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
      $fields['pregunta1'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Pregunta 1'))
      ->setDescription(t('Do you think you have had a significant loss of purchasing power in recent years.'))
      ->setDefaultValue(TRUE)
      ->setSettings(['on_label' => 'Published', 'off_label' => 'Unpublished'])
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'boolean',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
    return $fields;
  }
}
