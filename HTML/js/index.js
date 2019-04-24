$(document).ready(function(){

    var panel = document.getElementById("MainUL");

    for(var i=0;i<files_name.length;i++)
    {
        var li = document.createElement("li");
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = files_name[i]+'<span id='+files_name[i]+' class="down">Download</span><span id='+files_name[i]+' class="delete">X</span>';
        panel.appendChild(li);
        //console.log(files_name[i]);
    }

    $(".delete").on('click', function(event){
        $.post( "delete.php?id="+this.id, {
            id: this.id
            })
          .done(function( data ) {
             window.location.href = "";
          });
    });

    $(".down").on('click', function(event){
        window.location.href = "sounds/"+this.id;
    });

    document.getElementById("threshold").value = lineGet[1];
    document.getElementById("silence_count").value = lineGet[2];
    if(lineGet[3]==1) document.getElementById("saveVideo").checked = true;
    else document.getElementById("saveVideo").checked = false;

    $("#bt_save").on('click', function(event){
        var th = document.getElementById("threshold").value;
        var sl = document.getElementById("silence_count").value;
        var ch = 0;
        if(document.getElementById("saveVideo").checked) ch = 1;

        $.post( "saveFile.php?threshold="+th+"&silence_count="+sl+"&saveVideo="+ch, {
            id: 0
            })
          .done(function( data ) {
             window.location.href = "";
          });
    });

    $("#bt_download").on('click', function(event){
        
        var r = confirm("Are you sure?");
        if (r == true) {
            for(var i=0;i<files_name.length;i++)
            { 
                window.open("downloadAndDelete.php?id="+files_name[i]);
                console.log(files_name[i]);
                sleep(1000);
            }
        } else {
        }
        window.location.href = "";
      
    });

    function sleep(milliseconds) {
        var start = new Date().getTime();
        for (var i = 0; i < 1e7; i++) {
          if ((new Date().getTime() - start) > milliseconds){
            break;
          }
        }
      }
   
 });