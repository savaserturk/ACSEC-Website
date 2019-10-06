$(document).ready(function() {
  var codeCounter = 2;
  $("#add-another-file").click(function() {
    var newFileArea = "<div>";
    newFileArea += "<input type=\"text\" name=\"filename" + codeCounter + "\" placeholder=\"filename\">";
    newFileArea += "<textarea name=\"code" + codeCounter + "\" rows=4 cols=50 placeholder=\"Enter your solution here...\"></textarea>";
    newFileArea += "</div>";
    $("#file-area").append(newFileArea);

    codeCounter++;
  });
});
