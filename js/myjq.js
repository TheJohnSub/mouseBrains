/*
Version 1.1.0

Change Log
  V1.1.0 2017-05-11 jrs: Added functionality support for "Edit Map"
*/

var PublicCoorType = '';

function LoadMapInformation(mapObject){
    $("#map-name-field").html(mapObject.Name);
    $("#map-description-field").html(mapObject.Description);
    $("#map-datecreated-field").html(mapObject.DateCreated);


    $("#mouse-name-field").html(mapObject.MouseName);
    $("#map-editlink-field").attr("href", "/mouseBrains/map/edit/" + mapObject.MapID)
    $("#mouse-strain-field").html(mapObject.MouseStrain);
    $("#body-weight-field").html(mapObject.BodyWeight);
    $("#birth-date-field").html(mapObject.DateOfBirth);

    if (mapObject.Notes) {
      $("#map-notes-field").html(mapObject.Notes);
    }
    else $("#map-notes-field").html("You haven't saved any notes on this map yet.");

    $("#bregma-field").html('(' + mapObject.BregmaX + ', ' + mapObject.BregmaY + ', ' + mapObject.BregmaZ + ')');
    $("#lambda-field").html('(' + mapObject.LambdaX + ', ' + mapObject.LambdaY + ', ' + mapObject.LambdaZ + ')');
    $("#midline-field").html('(' + mapObject.MidlineX + ', ' + mapObject.MidlineY + ', ' + mapObject.MidlineZ + ')');

    $("#saveMap-mapId").val(mapObject.MapID);  
}

function LoadPointInformation(pointObject){
    $("#point-name-field").html(pointObject.Name);
    $("#point-notes-field").html(pointObject.Notes);
    $("#point-coordinates-field").html('(' + pointObject.XCoordinate + ', ' + pointObject.YCoordinate + ')');
    $("#point-datecreated-field").html(pointObject.DateCreated);
}

function LaunchCoordinateModal(CoorType){
  PublicCoorType = CoorType;
  var CurrentX = '';
  var CurrentY = '';
  var CurrentZ = '';
  if (CoorType == 'Bregma'){
    CurrentX = mapObject.ResponseObject.BregmaX;
    CurrentY = mapObject.ResponseObject.BregmaY;
    CurrentZ = mapObject.ResponseObject.BregmaZ;
  }
  else if (CoorType == 'Lambda'){
    CurrentX = mapObject.ResponseObject.LambdaX;
    CurrentY = mapObject.ResponseObject.LambdaY;
    CurrentZ = mapObject.ResponseObject.LambdaZ;
  }
  else if (CoorType =='Midline'){
    CurrentX = mapObject.ResponseObject.MidlineX;
    CurrentY = mapObject.ResponseObject.MidlineY;
    CurrentZ = mapObject.ResponseObject.MidlineZ;
  }
  $("#EditCoordinateX").val(CurrentX);
  $("#EditCoordinateY").val(CurrentY);
  $("#EditCoordinateZ").val(CurrentZ);
  $("#CoordinateModal").modal('show');
  $("#CoordinateModalLabel").text('Edit ' + CoorType + ' Coordinates (X, Y, Z)');
}

$(document).ready(function(){
  $("#addNewPt-btn").click(
    function(){
      var formData = {};
      formData.MapID = mapObject.ResponseObject.MapID;
      formData.Name = $("#pointName").val();
      formData.Notes = $("#pointNotes").val();
      formData.XCoordinate = $("#XCoordinate").val();
      formData.YCoordinate = $("#YCoordinate").val();

      $.ajax({
        type: "post",
        url: "/mouseBrains/map/point/add",
        data: JSON.stringify(formData),
        contentType: "application/json",
        success: function(result){
            if (result.ResponseCode == 200) {
              AddPointToMap(result.ResponseObject);  
              $('#addNewPt-form')[0].reset();
            }
            else {
            }  
          },
        error: function(result){
            new_content = JSON.parse(result);
            if (new_content.Status == 200) {
              LoadContent();
            }
            else {
            }  
          },
      });

      //AddPointToMap(formData);
  });

  $("#updateMapCoor-btn").click(
      function(){
        var formData = {};
        formData.MapID = mapObject.ResponseObject.MapID;
        formData.X = $("#EditCoordinateX").val();
        formData.Y = $("#EditCoordinateY").val();
        formData.Z = $("#EditCoordinateZ").val();
        formData.CoorType = PublicCoorType;        
      $.ajax({
        type: "post",
        url: "/mouseBrains/map/coorup",
        data: JSON.stringify(formData),
        contentType: "application/json",
        success: function(result){
            if (result.ResponseCode == 200) {
              Alert('Hey!');
              //AddPointToMap(result.ResponseObject);  
              //$('#addNewPt-form')[0].reset();
            }
            else {
            }  
          },
        error: function(result){
            Alert('Fail!');
          },
      });          
  
  });
});