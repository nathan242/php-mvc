<?php
use gui\gui;

ob_start();
gui::button('Add', 'table_crud/add');
gui::panel('Test table', ob_get_clean());
gui::table($records, ['key', 'value'], ['route' => '/test_crud/', 'vkey' => '0']);
