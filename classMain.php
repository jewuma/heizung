<?php
    if (!defined("DBHOST")) require "config.inc.php";
    if (!class_exists("Heater")) require "classHeater.php";
    if (!function_exists("getParams")) require "functions.php";
    $DB=new mysqli(DBHOST,DBUSER,DBPASS,DATABASE);
    dbinit();
    $heater=new Heater();
    $heater->init();
    MainLoop();
    function MainLoop() {
        global $heater;
        $loopcount=0;
        while (1) {
            $params=getParams();
            $heater->insertParams($params);
            $desiredHeaterState=false;
            $boilerFirst=false;
            $desiredBoilerTemp=$params["DesiredBoilerTemp"];
            $desiredCircuitTemp=calculateCircuitTemp($params);
            $targetTemp=$params["HeaterTemp"];
            if ($heater->getBurnerState()) $desiredBoilerTemp+=$params["Hysteresis"];
            //Check Boiler
            if ($params["BoilerTemp"]<$desiredBoilerTemp) {
                if ($params["solarTemp"]<($params["BoilerTemp"]+5)) {
                    $heater->switchToBoiler();
                    $boilerFirst=true;
                    $targetTemp=$params["BoilerTemp"];
                    if ($params["HeaterTemp"]-$params["BoilerTemp"]<15) {
                        $desiredHeaterState=true;
                    }
                }
            } else {
                $heater->switchToHeating();
                $targetTemp=$params["circuitTemp"];
            }
            //Check Heating-Circuit
            if (!$boilerFirst) {
                if ($params["circuitTemp"]<$desiredCircuitTemp &&
                    $params["HeaterTemp"]<$desiredCircuitTemp+15) $desiredHeaterState=true;
            }
            $heater->trySwitchBurner($desiredHeaterState);
            if ($heater->getBurnerState() || $params["HeaterTemp"]>$targetTemp+$params["Hysteresis"]) $heater->switchHeaterpump(true);
            else $heater->switchHeaterpump(false);
            $checkTemp=$params["OutsideNoHeatTemp"];
            if ($heater->getHeatCircuitPumpState()) $checkTemp++;
            $heater->switchHeatCircuitpump($params["outsideTemp"]<$checkTemp && !$boilerFirst);
            echo "Loop ".++$loopcount."\n";
            echo "Brenner  : ".tf($heater->getBurnerState())."\n";
            echo "Brenner S: ".tf($desiredHeaterState)."\n";
            echo "HPumpe   : ".tf($heater->getHeaterPumpState())."\n";
            echo "HKPumpe  : ".tf($heater->getHeatCircuitPumpState())."\n";
            echo "Dreiwege : ".dw($heater->getThreeWayState())."\n";
            echo "Heizkreis: ".$params["circuitTemp"]."\n";
            echo "Aussen   : ".$params["outsideTemp"]."\n";
            echo "Raum     : ".$params["roomTemp"]."\n";
            echo "Kessel   : ".$params["HeaterTemp"]."\n";
            echo "Boiler   : ".$params["BoilerTemp"]."\n";
            echo "Solar    : ".$params["solarTemp"]."\n";
            echo "HK-Wunsch: ".$desiredCircuitTemp."\n";
            echo "B-Wunsch : ".$desiredBoilerTemp."\n";
            sleep(LOOPWAIT);
        }
    }
?>
