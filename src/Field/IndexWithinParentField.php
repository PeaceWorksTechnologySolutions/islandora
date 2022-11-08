<?php

namespace Drupal\islandora\Field;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;
use Drupal\Core\Field\FieldItemListInterface;


class IndexWithinParentField extends FieldItemList implements FieldItemListInterface {

  use ComputedItemListTrait;
  
  protected function computeValue() {
    // get current node
    $entity = $this->getEntity();
    
    // get ID of parent node
    $value = $entity->get('field_member_of')->getvalue();
    if (isset($value[0]['target_id'])) {
        $parent_id = $value['0']['target_id'];

        // query on to get all sorted children of parent and find index of current node
        $query = \Drupal::entityQuery('node');
        $query->condition('field_member_of', $parent_id);
        $query->sort('field_weight', 'ASC');
        $sibling_ids = $query->execute();
        $result = array_search($entity->id(), array_values($sibling_ids));
    } else {
        $result = 0;
    }

    $this->list[0] = $this->createItem(0, $result);
  }
  
}
