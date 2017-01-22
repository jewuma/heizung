function drawThermo(classname,values,value) {
    var gauge = new Gauge();
    var options={
        colors: {
            0 : '#0000CC',
            40: '#00CC00',
            60: '#ffa500',
            80: '#f00'
        },
        angles: [
            150,
            390
        ],
        lineWidth: 5,
        arrowWidth: 10,
        arrowColor: '#020202',
        inset:true
    };
    options.value=value;
    options.values=values;
    $(classname).gauge(options);
}
function drawall(params) {
/*var params={
    roomTemp: 22,
    HeaterTemp: 48,
    circuitTemp: 43,
    BoilerTemp: 65,
    solarTemp: 82,
    outsideTemp: -3
};*/
    drawThermo('.roomthermo',{
            0 : '10',
            10: '12',
            20: '14',
            30: '16',
            40: '18',
            50: '20',
            60: '22',
            70: '24',
            80: '26',
            90: '28',
            100: '30',
        },params.roomTemp-10*5);
    drawThermo('.heaterthermo',{
                0 : '0',
                20: '20',
                40: '40',
                60: '60',
                80: '80',
                100: '100',
            },params.HeaterTemp);
    drawThermo('.circuitthermo',{
                0 : '0',
                20: '20',
                40: '40',
                60: '60',
                80: '80',
                100: '100',
            },params.circuitTemp);
    drawThermo('.boilerthermo',{
                0 : '0',
                20: '20',
                40: '40',
                60: '60',
                80: '80',
                100: '100',
            },params.BoilerTemp);
    drawThermo('.solarthermo',{
            0: '0',
            12.5 : '20',
            25: '40',
            37.5: '60',
            50: '80',
            62.5: '100',
            75: '120',
            87.5: '140',
            100: '160'

        },params.solarTemp*.625);
    drawThermo('.outsidethermo',{
                0: '-40',
                12.5 : '-30',
                25: '-20',
                37.5: '-10',
                50: '0',
                62.5: '10',
                75: '20',
                87.5: '30',
                100: '40'
            },(params.outsideTemp+50)*1.25);
}
function getPage(data) {
   $.ajax({
    url: "/"+data+".html",
    data: {},
   success: function(result) {
        $("#main").html(result);
   }
    });
   $.ajax({
       url: "/"+data+".php",
       data: {},
   success: function(result) {
        result=JSON.parse(result);
      drawall(result);
   }
   })
}