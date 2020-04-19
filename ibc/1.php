<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("1");
?>

<?
$APPLICATION->IncludeComponent(
	"ibc:exchange.rates", 
	".default", 
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "",
		"IBLOCK_ID" => "",
		"SECTION_IDS" => "",
		"LIST_CURRENCY" => "3"
	),
	false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
