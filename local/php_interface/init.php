<?
AddEventHandler("sale", "OnSaleStatusOrder", Array("MyClass", "OnSaleStatusOrder"));



class MyClass

{

    function OnSaleStatusOrder(&$ID, &$val){

        if ($val == "F"){
            define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");
            $order = CSaleOrder::GetByID($ID);
            $rsUser = CUser::GetByLogin($order[USER_LOGIN]);
            $arUser = $rsUser->Fetch();

            $user = new CUser;
            $fields = Array(
                "PERSONAL_BIRTHDAY"              => "",
                "PERSONAL_PROFESSION"         => "",
                "PERSONAL_WWW"              => "",
                "PERSONAL_ICQ"         => "",
                "PERSONAL_GENDER"              => "",
                "PERSONAL_BIRTHDATE"         => "",
                "PERSONAL_PHOTO"              => "",
                "PERSONAL_PHONE"         => "",
                "PERSONAL_FAX"              => "",
                "PERSONAL_MOBILE"         => "",
                "PERSONAL_PAGER"              => "",
                "PERSONAL_STREET"         => "",
                "PERSONAL_MAILBOX"              => "",
                "PERSONAL_CITY"         => "",
                "PERSONAL_STATE"              => "",
                "PERSONAL_ZIP"         => "",
                "PERSONAL_COUNTRY"              => "",
                "PERSONAL_NOTES"         => "",
            );
            $user->Update($arUser[ID], $fields);
            AddMessage2Log(print_r($arUser,true));
        }

    }

}
