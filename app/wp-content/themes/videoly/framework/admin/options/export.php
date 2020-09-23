<?php
$this->sections[] = array(
  'title' => esc_html__('Import/Export', 'videoly'),
  'desc' => esc_html__('Import/Export Options', 'videoly'),
  'icon' => 'el-icon-arrow-down',
  'fields' => array(

    array(
      'id'            => 'opt-import-export',
      'type'          => 'import_export',
      'title'         => esc_html__('Import Export', 'videoly'),
      'subtitle'      => esc_html__('Save and restore your Redux options', 'videoly'),
      'full_width'    => false,
    ),
  ),
);
