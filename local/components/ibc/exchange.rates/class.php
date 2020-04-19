<?php
use \Bitrix\Main\Loader,
    \Bitrix\Main\Application,
    \Bitrix\Main\Web\Uri,
    \Bitrix\Main\Web\HttpClient,
    \Bitrix\Main\Data\Cache;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class ExampleCompSimple extends CBitrixComponent
{
	/**
	 * Component constructor.
	 * @param CBitrixComponent | null $component
	 */
	public function __construct($component = null)
	{
		parent::__construct($component);
	}

	/**
	 * Проверка наличия модулей требуемых для работы компонента
	 * @return bool
	 * @throws Exception
	 */
	private function _checkModules()
	{
		if (!Loader::includeModule('iblock')
			|| !Loader::includeModule('sale')
		) {
			throw new \Exception('Не загружены модули необходимые для работы модуля');
		}

		return true;
	}

	/**
	 * Обертка над глобальной переменной
	 * @return CAllMain|CMain
	 */
	private function _app()
	{
		global $APPLICATION;
		return $APPLICATION;
	}

	/**
	 * Обертка над глобальной переменной
	 * @return CAllUser|CUser
	 */
	private function _user()
	{
		global $USER;
		return $USER;
	}

	/**
	 * Подготовка параметров компонента
	 * @param $arParams
	 * @return mixed
	 */
	public function onPrepareComponentParams($arParams)
	{
		// тут пишем логику обработки параметров, дополнение параметрами по умолчанию
		// и прочие нужные вещи
		return $arParams;
	}

	/**
	 * Точка входа в компонент
	 * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
	 * всю логику стараемся разносить по классам и методам
	 */
	public function executeComponent()
	{
		$this->_checkModules();

		if ($this->request->isPost()) {
			// some post actions
		}
        switch ($this->arParams["LIST_CURRENCY"]) {
            case 2:
                $currency = "R01235";
                $strcurrency = "USD";
                break;
            case 3:
                $currency = "R01239";
                $strcurrency = "EUR";
                break;
        }
        $url = "http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1=18/03/2020&date_req2=18/03/2020&VAL_NM_RQ=".$currency;

		if ($this->arParams["CACHE_TYPE"] == 'A') {

            $cacheId = 'ibc_exchange_rate_'.$strcurrency;
            $cacheTtl = $this->arParams["CACHE_TIME"];

            $cache = \Bitrix\Main\Application::getInstance()->getManagedCache();

            if ($cache->read($cacheTtl, $cacheId)) {
                $vars = $cache->get($cacheId); // достаем переменные из кеша
                $this->arResult['RATE'] =  $vars;
            } else {
                try {
                    $httpClient = new HttpClient();
                    $rez = $httpClient->get($url);

                    $xml = simplexml_load_string($rez);
                    $json = json_encode($xml);
                    $array = json_decode($json,TRUE);

                    $this->arResult['RATE'] = $array[Record][Value];
                    $this->arResult['ERROR']['VAL'] = 0;

                } catch (Throwable $e) {
                    $this->arResult['ERROR']['TEXT'] = $e->getMessage();
                    $this->arResult['ERROR']['VAL'] = 1;
                }
            }

        }else{
            try {
                $httpClient = new HttpClient();
                $rez = $httpClient->get($url);

                $xml = simplexml_load_string($rez);
                $json = json_encode($xml);
                $array = json_decode($json,TRUE);

                $this->arResult['RATE'] = $array[Record][Value];
                $this->arResult['ERROR']['VAL'] = 0;

            } catch (Throwable $e) {
                $this->arResult['ERROR']['TEXT'] = $e->getMessage();
                $this->arResult['ERROR']['VAL'] = 1;
            }
        }
        $this->arResult['STRCUR'] = $strcurrency;
		$this->includeComponentTemplate();
	}
}
