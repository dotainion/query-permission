<?php
namespace permission\module\permission\objects;

interface IPermission{
    public function id();
    public function read():bool;
    public function write():bool;
    public function edit():bool;
    public function delete():bool;
}