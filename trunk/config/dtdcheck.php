<?php
function validateXML($xmlFile){
  $dom = new DomDocument();
  $dom->load($xmlFile);
  
  if (@$dom->validate())
    echo $xmlFile." valido<br/>";
  else
    echo $xmlFile." no valido<br/>";
}

validateXML("actions.xml");
validateXML("application.xml");
validateXML("datasources.xml");
validateXML("ldap.xml");
?>