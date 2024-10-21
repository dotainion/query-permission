<?php
namespace permission\module\permission\objects;

interface IPermission{
    public function id();
    public function userId();
    public function read():bool;
    public function write():bool;
    public function edit():bool;
    public function delete():bool;
}