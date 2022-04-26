<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <!-- Ajax -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
  </script>
</head>


<body>
  <nav class="navbar navbar-expand-md navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">RUNNER'S WORLD</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 ">
          <li class="nav-item">
            <a class="nav-link me-2" href="account.php"><i class="bi bi-person-fill me-1"></i>Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="participants.php"><i class="bi-card-list me-1"></i>Participants List</a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2 active" href="category.php"><i class="bi-plus me-1"></i>Add Category</a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="logout.php"><i class="bi-lock-fill me-1"></i>Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container col-xl-auto">
    <div class="pt-5 pb-3 jumbotron">
      <h1>Category</h1>
      <hr>

    </div>
    <table class="table table-responsive" id="tab_logic">
      <thead>
        <tr>
          <th class="text-center">
            #
          </th>
          <th class="text-center">
            Category Name
          </th>
          <th class="text-center">
            Quota
          </th>
          <th class="text-center">
            Mobile
          </th>
        </tr>
      </thead>
      <tbody>
        <tr id='addr0'>
          <td>1</td>
          <td>
            <input type="text" name='name[]' placeholder='Enter Full Name' class="form-control" />
          </td>
          <td>
            <input type="email" name='mail[]' placeholder='Enter Mail' class="form-control" />
          </td>
          <td>
            <input type="number" name='mobile[]' placeholder='Enter Mobile' class="form-control" />
          </td>
        </tr>
        <tr id='addr1'></tr>
      </tbody>
    </table>
    <button id="add_row" class="btn btn-default pull-left">Add Row</button><button id='delete_row' class="pull-right btn btn-default">Delete Row</button>
  </div>

</body>

</html>

<script>
  $(document).ready(function() {
    var i = 1;
    $("#add_row").click(function() {
      b = i - 1;
      $('#addr' + i).html($('#addr' + b).html()).find('td:first-child').html(i + 1);
      $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
      i++;
    });
    $("#delete_row").click(function() {
      if (i > 1) {
        $("#addr" + (i - 1)).html('');
        i--;
      }
    });

  });
  //window.onload = getParticipantList();

  function getParticipantList() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var decoded = JSON.parse(this.responseText);
        showAll(decoded);

      }
    };
    xhttp.open("GET", "../../PHP/adminDashboard/get_participant_list.php", true);
    xhttp.send();
  }


  function showAll(data) {
    // get table body element
    var tbody = document.getElementsByTagName("tbody")[0];

    // for each participant
    for (let i = 0; i < data.length; i++) {

      // create a row element 
      const row = document.createElement("tr");

      // for each attribute of the particpant
      for (const item in data[i]) {

        // if the attribute is participant_id 
        if (item == "participant_id") {
          //cretae th
          const index = document.createElement("th");

          // set attribute
          index.setAttribute("scope", "row");

          //set value 
          index.innerText = data[i][item];

          //add the th element into row 
          row.appendChild(index);

        } else {

          // create normal td element 
          const attribute = document.createElement("td");

          //set innerText
          attribute.innerText = data[i][item];


          // add the td element into row 
          row.appendChild(attribute);
        }

      }




      // add a new row 
      tbody.appendChild(row);

    }

    const rows = document.getElementsByTagName("tr");
    console.log(rows);

    for (let i = 1; i < rows.length; i++) {
      const deleteFunc = document.createElement("td");
      const link = document.createElement("a");
      const delete_icon = document.createElement("i");
      delete_icon.className += "fa-solid fa-circle-minus";
      link.appendChild(delete_icon);
      link.onclick = function() {
        var result = confirm("Are you sure you want to delete this data");

        if (result) {

          const td = link.parentNode;
          const tr = td.parentNode;
          deleteRow(tr.firstChild.innerText);
          tr.parentNode.removeChild(tr);

        }


      }


      deleteFunc.appendChild(link);
      rows[i].appendChild(deleteFunc);
    };

  }

  function deleteRow(ID) {

    $.ajax({
      type: "POST",
      url: "../../PHP/delete_process.php",
      data: {
        "ID": ID,
      },
      success: function(data) {
        alert(data + "has been deleted");
      },
      error: function(xhr, status, error) {
        console.error(xhr);
      }
    });
  }
</script>