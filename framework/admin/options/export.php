<?php


$this->sections[] = array(
  'title' => esc_html__('Import/Export', 'animo'),
  'desc' => esc_html__('Import/Export Options', 'animo'),
  'icon' => 'el-icon-arrow-down',
  'fields' => array(
    array(
      'id'            => 'opt-import-export',
      'type'          => 'import_export',
      'title'         => esc_html__('Import Export', 'animo'),
      'subtitle'      => esc_html__('Save and restore your Redux options', 'animo'),
      'full_width'    => false,
    ),
  ),
);
