<?php
use gui\gui;

ob_start();
gui::button('Add', 'records/add');
gui::panel('Records', ob_get_clean());
gui::table($records, ['key', 'value'], ['route' => '/records/', 'vkey' => '0']);
