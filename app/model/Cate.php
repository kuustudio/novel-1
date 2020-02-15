<?php


namespace app\model;


use think\Model;

class Cate extends Model
{
    public function setTagNameAttr($value){
        return trim($value);
    }
}