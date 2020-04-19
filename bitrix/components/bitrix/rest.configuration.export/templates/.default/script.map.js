{"version":3,"sources":["script.js"],"names":["BX","namespace","Rest","Configuration","Export","prototype","init","params","this","next","section","errors","id","downloadPage","signedParameters","bind","findChildByClassName","delegate","e","start","addClass","style","insertAfter","create","attrs","className","children","text","message","remove","sendAjax","response","data","length","load","showFatalError","step","code","finish","barContainer","infoContainer","removeClass","cleanNode","appendChild","href","data-slider-ignore-autobinding","events","click","openPopupErrors","action","callback","ajax","runComponentAction","mode","then","addErrors","console","log","catch","i","push","errorText","forEach","item","errorTextArea","props","placeholder","html","restConfigWindowContent","restConfigWindow","PopupWindowManager","titleBar","content","contentBackground","contentPadding","minWidth","maxWidth","autoHide","closeIcon","animation","buttons","UI","Button","color","Color","LINK","select","document","execCommand","onPopupClose","destroy","show","window"],"mappings":"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","file":"script.map.js"}