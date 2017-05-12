function GetPointDataFromMapObject(mapObject) {
  var index;
  var pointData = [];
  for (index = 0; index < mapObject.Points.length; ++index) {
      var newPoint1 =   {
        x: [mapObject.Points[index].XCoordinate],
        y: [mapObject.Points[index].YCoordinate],
        mode: 'markers',
        type: 'scatter',
        name: mapObject.Points[index].Name,
        text: [mapObject.Points[index].Name],
        notes: mapObject.Points[index].Notes,
        datecreated: mapObject.Points[index].DateCreated,
        marker: { size: 12 }
      }
      pointData.push(newPoint1);
  }

  return pointData;
}

function CreateBrainMap(mapObject) {
  var pointData = [];
  if ($.isEmptyObject(mapObject.Points) == false) {
    pointData = GetPointDataFromMapObject(mapObject);
  }

  var myPlot = document.getElementById('myDiv'),
    layout = {
      hovermode:'closest',
      xaxis: {
        range: [ -5, 5 ] 
      },
      yaxis: {
        range: [-5, 5]
      },
      margin: {
        l: 30,
        r: 30,
        b: 30,
        t: 30,
        pad: 4
      },
      title:'Mouse Brain Map'
    };

  Plotly.newPlot('myDiv', pointData, layout);

  myPlot.on('plotly_click', function(data){
    var pts = '';
    var pointObj = {
      Name: data.points[0].data.name,
      Notes: data.points[0].data.notes,
      XCoordinate: data.points[0].data.x,
      YCoordinate: data.points[0].data.y,
      DateCreated: data.points[0].data.datecreated
    };

    LoadPointInformation(pointObj);

  });

}

function AddPointToMap(pointObject) {
  var pointPlot = {
    x: [pointObject.XCoordinate],
    y: [pointObject.YCoordinate],
    mode: 'markers',
    type: 'scatter',
    name: pointObject.Name,
    text: [pointObject.Name],
    notes: pointObject.Notes,
    datecreated: pointObject.DateCreated,
    marker: { size: 12 }
  };

  Plotly.addTraces(myDiv, pointPlot);
}



