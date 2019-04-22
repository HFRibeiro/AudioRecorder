$(document).ready(function(){

    var panel = document.getElementById("MainUL");

    for(var i=0;i<files_name.length;i++)
    {
        var li = document.createElement("li");
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = files_name[i]+'<span id='+files_name[i]+' class="down">Download</span><span id='+files_name[i]+' class="delete">X</span>';
        panel.appendChild(li);
        console.log(files_name[i]);
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
   
 });