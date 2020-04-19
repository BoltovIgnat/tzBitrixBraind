<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
if ( $arResult['ERROR']['VAL']){
    echo 'Что пошлно не так посмотрите курс валют в другом месте';
}else{
    echo 'Курс '.$arResult['STRCUR'].' = '.$arResult['RATE'];
}
?>
