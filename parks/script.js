const ip = myApp.ip; 
var jsonData = {};
function sendData() {
    var parkName = document.getElementById('parkName').value;
    var parkLocation = document.getElementById('parkLocation').value;
    var ilektronomosLink = document.getElementById('ilektronomosLink').value;
    var camerasLink = document.getElementById('camerasLink').value;
    var loggerLink = document.getElementById('loggerLink').value;
    

    if (parkName != "" && parkLocation != "" && ilektronomosLink != "" && camerasLink != "" && loggerLink != "" ) {
        let Addbutton = document.getElementById('add');
        Addbutton.disabled = true;

        jsonData = {
            "parkName": parkName,
            "parkLocation": parkLocation,
            "ilektronomosLink": ilektronomosLink,
            "camerasLink": camerasLink,
            "loggerLink": loggerLink
         
        };

        document.getElementById('parkName').disabled = true;
        document.getElementById('parkLocation').disabled = true;
        document.getElementById('ilektronomosLink').disabled = true;
        document.getElementById('camerasLink').disabled = true;
        document.getElementById('loggerLink').disabled = true;

         const url = ip + "/parks/postData.php";

        var xhr = new XMLHttpRequest();
       
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                Addbutton.disabled = false;
                var modalDiv = document.getElementById("addmodal")
                modalDiv.style.display='none';
                document.getElementById('parkName').disabled = false;
                document.getElementById('parkLocation').disabled = false;
                document.getElementById('ilektronomosLink').disabled = false;
                document.getElementById('camerasLink').disabled = false;
                document.getElementById('loggerLink').disabled = false;

                document.getElementById('parkName').value = "";
                document.getElementById('parkLocation').value = "";
                document.getElementById('ilektronomosLink').value = "";
                document.getElementById('camerasLink').value = "";
                document.getElementById('loggerLink').value = "";
               
                getData();
            }
        };
        var json = JSON.stringify(jsonData);
        xhr.send(json);
        console.log(jsonData);

    }
    else {
        alert("Please fill in all the fields");
    }
}

function getData() {
   
    const url = ip + "/parks/getInfo.php";

    var xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');


    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // The request was successful, and the response is available in xhr.responseText
            var jsonResponse = JSON.parse(xhr.responseText);
            loadData(jsonResponse);
        }
    };

    xhr.send();
}

function popModal() {
    var modalDiv = document.getElementById("addmodal")
    modalDiv.style.display='flex';
}

function closeModal() {
    var modalDiv = document.getElementById("addmodal")
    modalDiv.style.display='none';
}
