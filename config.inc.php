<?php
const LOOPWAIT=10;   //Time in seconds for next Parameter-Check;
/*const SENSORIDS=array(
    "heater"=>"28FF151E801605D0",
    "room"=>"room",
    "boiler"=>"analog1",
    "circuit"=>"circuit",
    "outside"=>"outside",
    "solar"=>"28FF7E38801605C8"
);*/
const SENSORIDS=array(
    "heater"=>"heater",
    "room"=>"room",
    "boiler"=>"boiler",
    "circuit"=>"circuit",
    "outside"=>"outside",
    "solar"=>"solar"
);
//"boiler"=>"28FFE820801605C7",
const HEATERSW=1;
const HEATERPUMPSW=2;
const CIRCUITPUMPSW=3;
const THREEWAYSW=4;
const DBHOST="localhost";
const DBUSER="heatcontrol";
const DBPASS="heatpwd";
const DATABASE="heatcontrol";
//const IOHOST="192.168.178.63";
const IOHOST="localhost";
const IOPORT="8081";
?>
