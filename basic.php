
<html>
    <head>
        <title>
            Basic - Text editor
        </title>
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
<style>
    .txtArea
    {
        background-color: #424242;
        border-color:#FFA726;
        color:#fff;
        font-size: 16pt;
        width:100%; 
        height:100vw; 
        resize: none;
        padding:15px;
    }
    ::selection {
        background: #FFA726; /* WebKit/Blink Browsers */
    }
    ::-moz-selection {
        background: #FFA726; /* Gecko Browsers */
    }
    
    #mydiv {
        display:none;
        position: fixed;
        z-index: 100;
        background-color: #424242;
        border: 1px solid #aa6f19;
        text-align: center;
        bottom:30;
        right:30;
    }

    #mydivheader {
      padding: 10px;
      z-index: 10;
      border: 1px solid #aa6f19;
      background-color: #ffa726;
      color: #fff;
    }
    
    #mydivheader span{
        cursor:default;
    }
    
    #mydivheader button{
        float:right;
        cursor:pointer;
    }

    
    .toolboxlist{
        padding:15px;
        width:250px;
    }
    .toolboxlist button{
        cursor:pointer;
        display:inline-block;
        padding-top:5px;
        padding-bottom:5px;
        padding-right:10px;
        padding-left:10px;
    }
    
    .toolboxlist span{
        color:white;
        font-size:13px;
        font-family: Arial, Helvetica, sans-serif;

    }
    
</style>
    
    
<body style ="background-color:#212121;">

        <textarea placeholder="Write your text here..." type="text" id="txtb" class="txtArea"  name="txtarea"></textarea>

         <!-- Draggable DIV -->
        <div id="mydiv">
            <!-- Include a header DIV with the same name as the draggable DIV, followed by "header" -->
            <div id="mydivheader"><span>More Options</span>

                <button type="button" onclick="document.getElementById('mydiv').style.display='none'">X</button>
            </div>
            <div class="toolboxlist">
                <button type="button" onclick="Save()">Save</button>
                <button type="button" onclick="Load()">Load</button>
                <button type="button" onclick="">Show</button>
                <button type="button" onclick="Wipe()">Wipe</button>
                <br><br><span>Shortcuts: CTRL + S / CTRL + L</span>
            </div>
            
        </div> 


<script>
    
    function Save(){
        
        var dateJS = new Date();
        const monthNames = ["Януари", "Февруари", "Март", "Април", "Май", "Юни", "Юли", "Август", "Септември", "Октомври", "Ноември", "Декември"];
        var currentTime = dateJS.getDate() + "/" + monthNames[dateJS.getMonth()] + "/" + dateJS.getFullYear() + " " + dateJS.getHours() + ":" + dateJS.getMinutes();
        
        var value = "\r\n Saved on: " + currentTime + " | User-agent: " + navigator.userAgent + " | Text: " + "\r\n" + "-------------------------------------------------" + "\r\n" + document.getElementById("txtb").value + "\r\n" + "-------------------------------------------------" + "\r\n";
            
        $.ajax ({ 
            type: "POST",
            url:"texter.php",
            data: {"txtValue" : value}, 
            success: function() {
            console.log("Saved!");
            alert("Информацията е запазена!");
            }
        
        });
        
        //Redo localStorage data also
        Cache();
    }
    
    function Wipe(){
        
        const wipe = "";
        
        $.ajax ({ 
            type: "POST",
            url:"texter.php",
            data: {"wipeData" : wipe}, 
            success: function() {
            console.log("Wiped!");
            alert("Информацията във файла е изтрита успешно! /To be fixed (does not work)");
            }
        
        });
        
    }
    
    //Loads data from localStorage of the browser
    function Load(){
        
        if(localStorage.getItem('texterDataLocalStorage')){
        	console.log('Loading cached data...');
        	cacheValue = localStorage.getItem('texterDataLocalStorage');
        	document.getElementById("txtb").value = cacheValue;
        	
        }else{
            
	        console.log('No cache has been generated yet!');
        }

    }
    
    function Show(){}
    
    //
    document.addEventListener("keydown", function(e) {
        if((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)  && e.keyCode == 72) {
            e.preventDefault();
            
            var toolbox = document.getElementById("mydiv");
            toolbox.style.display = "block";
        }
        
        if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)  && e.keyCode == 83) {
            e.preventDefault();
            Save();
        }
        
        if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)  && e.keyCode == 76) {
            e.preventDefault();
            Load();
        }
        
    }, false);


    //Redo localStorage data once every 2 minutes.
    var refreshCacheInterval = window.setInterval(Cache, 120000);
        
    function Cache(){
		localStorage.setItem('texterDataLocalStorage', document.getElementById("txtb").value);
        console.log("Cache refreshed!");
    }

</script>

</body>
</html>

<?php 

    $fileData = './BasicEditorData/' . date("Y-m-d") . '-' . 'basicData.log';

    $theHash = $_POST['txtValue'];
    
    if (!file_exists('./BasicEditorData/' . date("Y-m-d") . '-' . 'basicData.log')) {
    fopen($fileData, "w") or die("Unable to open file!");
    $writeToFile = fopen($fileData, "a") or die("Unable to open file!");
    fwrite($writeToFile, $theHash);
    fclose($writeToFile);
    }
    else{
    $writeToFile = fopen($fileData, "a") or die("Unable to open file!");
    fwrite($writeToFile, $theHash);
    fclose($writeToFile);
    }
?>
