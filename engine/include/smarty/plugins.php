<?php

function smarty_modifier_print_status($value)
{
    
    //statuses: 'verificaredocumente','deexpediat','expediat','expediatramburs','refuzplata','nelivrat','livrat'
    $html = "";
    if($value=="verificat"){
        $html = "<img src='/img/icons/pending.gif' /> <em>Polita verificata.</em>";
    
    }elseif($value=="verificaredocumente"){
        $html = "<img src='/img/icons/pending.gif' /> <em>Verificare documente</em>";
    
    }elseif($value=="deexpediat"){
        
        $html = "<img src='/img/icons/pending.gif' /> <em>De livrat</em>";
    
    }elseif($value=="expediat" || $value=="expediatramburs"){
        
        $html = "<img src='/img/icons/ok.png' /> Expediat";
    }elseif($value=="livrat" ){
        
        $html = "<img src='/img/icons/ok.png' /> Livrat";
    }
    return $html;
}

function smarty_modifier_localitate_name($value){
    
    $tmp = getLocalitateInfo($value);
    if(isset($tmp->id))
        return $tmp->nume." - Judetul {$tmp->judet}";
    else return "";
}

function smarty_modifier_toTypeName($value){
    $values = array(
        "0"=>"",
        "1"=>"reala",
        "2"=>"de inlocuire",
        "3"=>"de piata",
    );
    return $values[$value];
}