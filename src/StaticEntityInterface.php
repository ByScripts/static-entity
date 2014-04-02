<?php

namespace Byscripts\StaticEntity;

interface StaticEntityInterface
{
    static function getDataSet();
    static function getAssoc($valueKey = 'name');
    static function get($id, $class = null);
    static function getIds();
    public function getId();
    public function is($id);
}