<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Geo-simulator for drivers and users waiting for a men who can park his car">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.2.0/normalize.css">
    <link href="https://fonts.googleapis.com/css?family=Slabo+27px" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@0.7.7/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ URL::asset("css/main.css") }}">
    <title>Geo Simulator / Entidades</title>
</head>
<body>
<header>
    <div class="header">
        <nav>
            <div class="nav">
                <span style="font-size: 1.5em; box-shadow: none;">Geo Simulator / Entidades</span>
            </div>
        </nav>
    </div>
</header>


<div class="content">
    <div class="left-panel">
        <div class="table-container">
            <table class="table" id="grid">
                <thead>
                <tr>
                    <th style="width: 5%;">Id</th>
                    <th style="width: 30%;">Nombre</th>
                    <th style="width: 30%;">Tipo</th>
                    <th style="width: 30%;">Ruta</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="rigth-panel">
        <iframe src="" frameborder="0" id="iframe" style="width: 100%; height: calc(100vh - 60px);">
        </iframe>
    </div>
</div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="http://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
{{-- DataTable for every entity --}}
<script>
    var table;
    var editEntities = "{{ URL::route("entities_edit", ["__ID__"]) }}";

    table = $("#grid").dataTable({
        language: {
            url: "http://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
        },
        columns: [
            {data: "id", render: function (data, type, row) {
                return "<div class='id' data-id='" + data + "'>" + data + "</div>";
            }, width: "0px"},
            {data: "name", render: function (data, type, row) {
                return "<div class='name'>" + data + "</div>";
            }},
            {data: "entity_type", render: function (data, type, row) {
                if (data == 0) {
                    return "Usuario"
                }
                return "Conductor";
            }},
            {data: "route", render: function (data, type, row) {
                return "<div class='route' data-id='" + row.route_id + "'>" + data + "</div>";
            }, sortable: false},
        ],
        paging: true,
        searching: false,
        autoWidth: false,
        info: false,
        lengthChange: true,
        serverSide: true,
        pageLength: 25,
        ajax: {
            url: "{{ URL::route("entities_rows") }}",
            type: "get",
            data: function (d) {
                d._token = "{{ csrf_token() }}";
            }
        },
        "fnInitComplete": function(settings, json) {

        },
        "dom": '<"top"iflp<"clear">>',
    });

    $("body").on("click", "#grid tbody tr", function() {

        $("table#grid tbody tr").each(function(index, value) {
            if ($(value).hasClass("selected")) {
                $(value).removeClass("selected");
                return false;
            }

        });

        if ($(this).hasClass("selected")) {
            $(this).removeClass("selected");
        } else {
            $(this).addClass("selected");
        }

        var id = $(this).find(".id").attr("data-id");

        $("#iframe").attr("src", editEntities.replace("__ID__", id) );

        console.log(id);
    })
</script>
</html>