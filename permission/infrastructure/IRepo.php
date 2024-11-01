<?php

namespace permission\infrastructure;

interface IRepo{

    public function onQueryStart(string $dml):void;
}
