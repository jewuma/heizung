
<?php
if (!class_exists("ControlIO")) require "classControlIO.php";
if (!defined('SENSORIDS')) require "config.inc.php";
dbinit();
function getParams() {  //get actual temperatures and States
    $DB=new mysqli(DBHOST,DBUSER,DBPASS,DATABASE);
    $res=$DB->query("SELECT * FROM `parameters`");
    $params=$res->fetch_assoc();
    $params["HeaterTemp"]=getTemperature("heater");
    $params["BoilerTemp"]=getTemperature("boiler");
    $params["outsideTemp"]=getTemperature("outside");
    $params["circuitTemp"]=getTemperature("circuit");
    $params["roomTemp"]=getTemperature("room");
    $params["solarTemp"]=getTemperature("solar");
    return $params;
}
function getTemperature($name) { //Read Temp-Sensor
    $id=SENSORIDS[$name];
    if (substr($id,0,2)=="28") {
        $io=new ControlIO();
        return $io->getSensor($id);
    } elseif (substr($id,0,6)=="analog") {
        $io=new ControlIO();
        return $io->getAnalogInput(substr($id,-1));
    } else {
        $DB=new mysqli(DBHOST,DBUSER,DBPASS,DATABASE);
        $res=$DB->query("SELECT ".$id." FROM temperatures");
        return $res->fetch_row()[0];
    }
}
function dbinit() { //Initialize Database if empty
    $DB=new mysqli(DBHOST,DBUSER,DBPASS,DATABASE);
    $res=$DB->query("SHOW TABLES LIKE 'parameters'");
    if ($res->num_rows!=1) {
        $DB->query("CREATE TABLE `parameters` (
            `MaxHeaterTemp` INT,
            `MinFloorTemp` INT,
            `MaxFloorTemp` INT,
            `OutsideNoHeatTemp` INT,
            `DesiredBoilerTemp` INT,
            `HeaterSpreadOnOff` INT,
            `MinHeaterOffTime` INT,
            `BeginNight` CHAR(4),
            `EndNight` CHAR(4),
            `HeatCurve` INT,
            `LastBurnerOff` BIGINT,
            `Hysteresis` INT,
            `MinYearTemp` INT
        )");
        $DB->query("INSERT INTO `parameters` SET
            `MaxHeaterTemp`=70,
            `MinFloorTemp`=25,
            `MaxFloorTemp`=45,
            `OutsideNoHeatTemp`=21,
            `DesiredBoilerTemp`=50,
            `HeaterSpreadOnOff`=10,
            `MinHeaterOffTime`=600,
            `BeginNight`='2200',
            `EndNight`='0400',
            `HeatCurve`=1,
            `LastBurnerOff`=0,
            `Hysteresis`=3,
            `MinYearTemp`=-15
        ");
    }

}
function calculateCircuitTemp($params) {  //Calculate needed circuit-Temperature based on given Parameters
    $desiredCircuitTemp=$params["MinFloorTemp"];
    $addTemp=$params["MaxFloorTemp"]-$params["MinFloorTemp"];
    $tempspan=$params["OutsideNoHeatTemp"]-$params["MinYearTemp"];
    if ($tempspan==0) return 10; //Max-Temp=Min-Temp: No Heating
    $aktspan=$params["outsideTemp"]-$params["MinYearTemp"];
    if ($aktspan<0) return $params["MaxFloorTemp"];
    if ($aktspan>$tempspan) return 10;    //No Heating if actual Temp > Max-Temp for Heating
    $factor=$addTemp*(1-($aktspan/$tempspan));
    $desiredCircuitTemp+=ceil($factor);
    if ($params["BurnerState"]) $desiredCircuitTemp+=$params["Hysteresis"];
    return $desiredCircuitTemp;
}
function tf($val) {     //convert True/False to "an" and "aus"
    if ($val) return "an";
    else return "aus";
}
function dw($val) {     //convert State of Threeway-Valve
    if ($val) return "Heizen";
    else return "Speicher laden";
}

?>
