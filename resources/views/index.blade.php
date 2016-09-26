<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Geo-simulator for drivers and users waiting for a men who can park his car">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.2.0/normalize.css">
    <link href="https://fonts.googleapis.com/css?family=Slabo+27px" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@0.7.7/dist/leaflet.css" />
    <title>Geo Simulator</title>
</head>
<style>
    * {
        font-family: 'Slabo 27px', serif;
        font-size: 12pt;
    }
    .config {
        background-color: #3ba9a6;
        -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.69);
        -moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.69);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.69);
        height: 60px;
        font-size: 1.5em;
        line-height: 60px;
        width: 100%;
    }
    .nav {
        margin-left: 10px;
        vertical-align: middle;
    }
    .right-nav {
        box-shadow: none;
        float: right !important;
        line-height: 60%;
    }
    .content {
        height: calc(100% - 60px);
        width: 100%;
    }
    .left-panel {
        background-color: #ffffff;
        -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.8);
        -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.8);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.8);
        display: inline-block;
        height: 100%;
        vertical-align: top;
        width: 30%;
    }
    .rigth-panel {
        display: inline-block;
        height: 100%;
        position: relative;
        vertical-align: top;
        width: calc(70% - 4px);
    }
    .map {
        position: absolute;
        height: 100%;
        width: 100%;
    }
    .table-container {
        padding-top: 5px;
        height: 100%;
    }
    table.dataTable thead .sorting_asc {
        background: none !important;
        cursor: auto;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0;
    }
    .modal {
        background-color: rgba(0, 0, 0, 0.56);
        height: 100%;
        width: 100%;
        z-index: 999999;
        display: none;
    }
    .modal-content {
        width: 35%;
        margin-left: auto;
        margin-right: auto;
        margin-top: 10%;
    }
    .form-row {
        clear: both;
        margin-bottom: 5px;
    }
    .form-row label {
        display: block;
    }
    .choice {
        width: 50%;
        border-radius: .2em;
        background-color: white;
    }
    .myButton {
        border-radius: .2em !important;
        background-color: transparent;
        border: solid 1px black;
        transition: all .3s;
    }
    .myButton:hover {
        background-color: #3ba9a6;
    }
    .delimiter {
        border: solid 1px black;
        margin-top: 35px;
        width: 100%;
    }

    @media screen and (max-width: 520px) {
        .left-panel {
            display: none;
        }
        .rigth-panel {
            width: 100%;
        }
        .modal-content {
            width: 75%;
        }
        .choice {
            width: 75%;
        }
    }

</style>
<body>
    <div class="config">
        <nav>
            <div class="nav">
                <span style="font-size: 1.5em; box-shadow: none;">Geo Simulator</span>
                <div class="right-nav">
                    <span class="glyphicon glyphicon-cog" id="config"
                          style="line-height: 60px; cursor: pointer;
                          font-size: 1.5em; margin-right: 10px;"></span>
                </div>
            </div>
        </nav>
    </div>
    <div class="content">
        <div class="left-panel">
            <div class="table-container">
                <table class="table" id="grid">
                    <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Conductor cercano</th>
                        <th>Distancia</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="rigth-panel">
            <div id="map" class="map"></div>
        </div>

        <div class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <span>Configuración</span>
                    <span style="float: right !important; cursor: pointer;" id="closeModal">&#x2715;</span>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <label for="clients">Número máximo de clientes</label>
                        <select name="clients" id="clients" class="choice">
                            <option value="1">Uno</option>
                            <option value="2">Dos</option>
                            <option value="3">Tres</option>
                            <option value="4">Cuatro</option>
                            <option value="5">Cinco</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="clients">Número máximo de conductores</label>
                        <select name="clients" id="clients" class="choice">
                            <option value="1">Uno</option>
                            <option value="2">Dos</option>
                            <option value="3">Tres</option>
                            <option value="4">Cuatro</option>
                            <option value="5">Cinco</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <button class="btn myButton" id="saveConfig">Aplicar cambios</button>
                    </div>
                    <div class="delimiter"></div>
                    <div class="form-row" style="margin-top: 5px;">
                        <button class="btn myButton" id="resumeApp">Reanudar aplicación</button>
                        <button class="btn myButton" id="stopApp">Pausar aplicación</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://unpkg.com/leaflet@0.7.7/dist/leaflet.js"></script>
<script src="http://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.4.8/socket.io.js"></script>
{{-- Here's where table come with the data --}}
<script>
    var table;
    $(function () {
        table = $("#grid").DataTable({
            language: {
                url: "http://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
            },
            columns: [
                {data: "name", render: function (data, type, row) {
                    return data;
                }, sortable: false},
                {data: "closest", render: function (data, type, row) {
                    return row.closest.name
                }, sortable: false},
                {data: "distance", render: function (data, type, row) {
                    return row.closest.distance + " m";
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
                url: "{{ URL::route("entities_list") }}",
                type: "POST",
                data: function (d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            "fnInitComplete": function(settings, json) {
                entities = [];
                lngLats = [];
                var data = json.data;
                if (data.length == 0) {
                    return false;
                }

                for (var i in data) {
                    var iData = data[i];
                    var entity = new Entity(iData.id, iData.name, [Number(iData.lat), Number(iData.lng)]);
                    entities.push(entity);
                    latLngs.push(entity.latLng);
                    entity.marker = putMarker(entity.latLng, entity.name);

                    var closest = iData.closest;
                    console.log(closest);
                    var closestEntity = new Entity(closest.id, closest.name, [Number(closest.lat), Number(closest.lng)]);
                    entities.push(closestEntity);
                    latLngs.push(closestEntity.latLng);
                    closestEntity.marker = putMarker(closestEntity.latLng, closestEntity.name);
                }

                map.fitBounds(latLngs);
            },
            "dom": '<"top"iflp<"clear">>',
        });
    });

    $("#config").click(function () {
        $(".modal").show();
    });
    $("#closeModal").click(function() {
        $(".modal").hide();
    });

</script>


{{-- Here's all map stuff and sock connections to make this map a little interactive --}}
<script>
    var map = L.map('map').setView( [23.634501, -102.552784], 5);

    L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v9/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiamFpbWVyYW1pcmV6YyIsImEiOiJjaXRhd205bnQwM3N0MnlscW16OG1oaDc0In0.xN9Tt_efCB-gsYsHaK3QKQ')
        .addTo(map);

</script>
<script>
    var Entity = function(id, name, latLng, marker) {
        this.id = id;
        this.name = name;
        this.latLng = latLng;
        this.marker = marker;
    }

    var entities = [];
    var latLngs = [];

    function putMarker(latLng, description) {
        var marker = new L.Marker(latLng).addTo(map);
        marker.bindPopup(description);
        return marker;
    }

</script>

<script>
    var sock = io("http://localhost:3000");
    sock.on("reload", function (data) {

        for (var i in entities) {
            var iEntity = entities[i];
            if (Number(iEntity.id) == data.id) {
                iEntity.marker.setLatLng([Number(data.lat), Number(data.lng)]);
            }
        }

        table.ajax.reload();
    });
</script>
</html>