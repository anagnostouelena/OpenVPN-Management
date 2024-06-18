let url = "http://home/elena/parks/getInfo.php";

var xhr = new XMLHttpRequest();
xhr.open("GET", url, true);
xhr.setRequestHeader('Content-Type', 'application/json');

xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
        // The request was successful, and the response is available in xhr.responseText
        var jsonResponse = JSON.parse(xhr.responseText);
        loadData(jsonResponse);
        // console.log(xhr.responseText);
    }
};

xhr.send();


function createButtonClickHandler(button,  ilektronomosCell, camerasCell, loggerCell, park) {
    return function () {
       
        button.disabled = true;

        if (park.on === 0) {
            let url = `http://home/elena/parks/connect.php?name=${park.cn}`;

            var xhr = new XMLHttpRequest();
            xhr.open("GET", url, true);
            xhr.setRequestHeader('Content-Type', 'application/json');


            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    park.on = 1;
      
                    button.disabled = false;
                    button.innerText = 'On';
                    //adds ilektronomos a tag to the html table
                    var ilektronomosLink = document.createElement("a");
                    ilektronomosLink.href = park.ilektronomosLink;
                    ilektronomosLink.target = "_blank";
                    ilektronomosLink.innerHTML = "http://10.101.10.52:8181";
                    ilektronomosCell.appendChild(ilektronomosLink);
                
                    //adds the cameras a tag to the html table
                    var camerasLink = document.createElement("a");
                    camerasLink.href = park.camerasLink;
                    camerasLink.target = "_blank";
                    camerasLink.textContent = "Go to camerasLink";
                    camerasCell.appendChild(camerasLink);
                
                    //adds the logger a tag to the html table
                    var loggerLink = document.createElement("a");
                    loggerLink.href = park.loggerLink;
                    loggerLink.target = "_blank";
                    loggerLink.textContent = "Go to loggerLink";
                    loggerCell.appendChild(loggerLink);

                    button.style.backgroundColor = "#4CAF50"; // Material Green
                }
            };

            xhr.send();

        } else {

            let url = `http://home/elena/kill.php?name=${park.cn}`;

            var xhr = new XMLHttpRequest();
            xhr.open("GET", url, true);
            xhr.setRequestHeader('Content-Type', 'application/json');


            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    park.on = 0;
                    console.log("click inside response");
                    button.disabled = false;
                    button.innerText = 'Off';
                    ilektronomosCell.innerHTML = "";
                    camerasCell.innerHTML = "";
                    loggerCell.innerHTML = "";
                    button.style.backgroundColor = "#F44336"; // Material Red



                }
            };

            xhr.send();

        }
    };
}


function loadData(json) {
    var tableBody = document.getElementById('table-data').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = "";
  
    for (var key in json) {
        let park = json[key];
        console.log(park);
        var newRow = tableBody.insertRow();
        var parkNameCell = newRow.insertCell(0);
        var parkLocationCell = newRow.insertCell(1);
        var ilektronomosCell = newRow.insertCell(2);
        var camerasCell = newRow.insertCell(3);
        var loggerCell = newRow.insertCell(4);
        var buttonCell = newRow.insertCell(5);
    
        //adds all the text fields to the table
        parkNameCell.textContent = park.parkName;
        parkLocationCell.textContent = park.parkLocation;  
    
        //adds the on/off button to the html table
        var button = document.createElement('button');
        
        button.className = 'button';
        button.style.marginTop="-1px";
        button.style.width = "100%";
   

        if(park.on === 0) {
            button.textContent = 'Off';
            button.style.backgroundColor = "#F44336"
        }
        else {
            button.textContent = 'On';
            button.style.backgroundColor = "#4CAF50"
            button.setAttribute('data-secret', 1);
            //adds ilektronomos a tag to the html table
            var ilektronomosLink = document.createElement("a");
            ilektronomosLink.href = park.ilektronomosLink;
            ilektronomosLink.target = "_blank";
            ilektronomosLink.textContent = "Go to ilektronomos";
            ilektronomosCell.appendChild(ilektronomosLink);
        
            //adds the cameras a tag to the html table
            var camerasLink = document.createElement("a");
            camerasLink.href = park.camerasLink;
            camerasLink.target = "_blank";
            camerasLink.textContent = "Go to camerasLink";
            camerasCell.appendChild(camerasLink);
        
            //adds the logger a tag to the html table
            var loggerLink = document.createElement("a");
            loggerLink.href = park.loggerLink;
            loggerLink.target = "_blank";
            loggerLink.textContent = "Go to loggerLink";
            loggerCell.appendChild(loggerLink);
        }
    
        button.addEventListener('click', createButtonClickHandler(button, ilektronomosCell, camerasCell, loggerCell, park));
        buttonCell.appendChild(button);
    }
    
    var loading = document.getElementById("loading-circle")
    loading.style.display = "none";

    
}










