<? if(empty($id)){ ?>
    <?include Config::getRoot()."/views/newyear/categoryList.view.php";?>
<?}
else{
?>
    <?include Config::getRoot()."/views/newyear/viewCategory.view.php";?>
<?
}
?>
