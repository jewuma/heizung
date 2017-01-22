<?php
if (!class_exists("ControlIO")) require "classControlIO.php";
if (!function_exists("getParams")) require "functions.php";
class Heater {
    private $temperature;
    private $burnerState=2;
    private $heaterPumpState=2;
    private $threeWayState=2;
    private $heatCircuitPumpState=2;
    private $params;
    private $db;
    private $io;
    function __construct() {
        global $SENSORIDS,$DB;
        $this->sensorIDs=$SENSORIDS;
        $this->db=$DB;
        $this->io=new ControlIO();
    }
    function init() {
        $this->switchBurner(false);
        $this->switchHeatCircuitpump(false);
        $this->switchHeaterpump(false);
        $this->switchToBoiler();
    }
    function getTemperature() {
        $this->temperature=getTemperature("heater");
        return $this->temperature;
    }
    function getBurnerState() {
        return $this->burnerState;
    }
    function getHeaterPumpState() {
        return $this->heaterPumpState;
    }
    function getThreeWayState() {
        return $this->threeWayState;
    }
    function getHeatCircuitPumpState() {
        return $this->heatCircuitPumpState;
    }
    function insertParams($params) {
        $this->params=$params;
    }
    private function isOfftimeOK() {
        $lastoff=$this->db->query("SELECT `LastBurnerOff` FROM `parameters`")->fetch_row()[0];
        $OfftimeOK=true;
        if ($this->params["MinHeaterOffTime"]>(time()-$lastoff)) $OfftimeOK=false;
        //echo "OffTime: ".(time()-$lastoff)."* $OfftimeOK *\n";
        return $OfftimeOK;
    }
    function trySwitchBurner($onoff) {
        //Security
        if ($this->burnerState!=false) {
            if ($this->getTemperature()>$this->params["MaxHeaterTemp"]) {
                $this->switchBurner(false);
                return;
            }
        }
        if ($this->burnerState==$onoff) {
            return;
        }
        if ($onoff==false) {
            $this->switchBurner(false);
            return;
        }
        if ($this->isOfftimeOK()) $this->switchBurner(true);
    }
    private function switchBurner($onoff) {
        if ($onoff==false && $this->burnerState) $this->db->query("UPDATE `parameters` SET `LastBurnerOff`=".time());
        if ($this->burnerState!=$onoff) {
            $this->burnerState=$onoff;
            $this->io->changeRelayState(HEATERSW,$onoff);
            if ($onoff==false) {// Cut-Off-Cycle with three short starts to blow off
                sleep(2);
                $this->io->changeRelayState(HEATERSW,true);
                sleep(14);
                $this->io->changeRelayState(HEATERSW,false);
                sleep(2);
                $this->io->changeRelayState(HEATERSW,true);
                sleep(14);
                $this->io->changeRelayState(HEATERSW,false);
                sleep(2);
                $this->io->changeRelayState(HEATERSW,true);
                sleep(14);
                $this->io->changeRelayState(HEATERSW,false);
            }
        }
    }
    function switchToBoiler() {
        if ($this->threeWayState!=false) {
            $this->threeWayState=false;
            $this->io->changeRelayState(THREEWAYSW,false);
        }
    }
    function switchToHeating() {
        if ($this->threeWayState!=true) {
            $this->threeWayState=true;
            $this->io->changeRelayState(THREEWAYSW,true);
        }
    }
    function switchHeaterpump($onoff) {
        if ($this->heaterPumpState!=$onoff) {
            $this->heaterPumpState=$onoff;
            $this->io->changeRelayState(HEATERPUMPSW,$onoff);
        }
    }
    function switchHeatCircuitpump($onoff) {
        if ($this->heatCircuitPumpState!=$onoff) {
            $this->io->changeRelayState(CIRCUITPUMPSW,$onoff);
            $this->heatCircuitPumpState=$onoff;
        }
    }
}
?>
