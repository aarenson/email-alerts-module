<?php
namespace Vanderbilt\EmailTriggerExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;


$searchTerms = $_REQUEST['parameters'];
$project_id = $_REQUEST['project_id'];
$variables = explode(',',$_REQUEST['variables']);

if(!empty($variables)){
    $sqlvariables = "";
    $numItems = count($variables);
    $i = 0;
    foreach ($variables as $var){
        if ($i == $numItems - 1) {
            $sqlvariables .= "'".substr($var, 1, strlen($var)-2)."'";
        }else{
            $sqlvariables .= "'".substr($var, 1, strlen($var)-2)."',";
        }
        $i++;
    }
}

$sql = "SELECT DISTINCT(value) from `redcap_data` where project_id = ".$project_id." AND field_name in (".$sqlvariables.") AND value LIKE '".$searchTerms."%' ";
$result = $module->query($sql);

$matchingProjects = '';
while($row = db_fetch_assoc($result)) {
    $matchingProjects .= "<option value='".$row['value']."'>";
}

echo json_encode($matchingProjects);

