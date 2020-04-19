<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ibc");
?>

<?
if(\Bitrix\Main\Loader::includeModule('ibc.tz')) {

    $test = new \Ibc\Tz\Helper();
    $n = 10;
    echo "В Вашем почтовом ящике $n ";
    echo $test->declination($n, "письмо", "письма", "писем");

}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>