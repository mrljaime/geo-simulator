@extends("rightiframe")
@section("content")

    <div class="content-inside">
        <form action="{{ URL::route("entities_edit", [$entity->id]) }}" method="post">
            <div class="form-row">
                <div class="label-on-top">
                    <label for="name">Nombre</label>
                    <input type="text" value="{{ $entity->name }}" maxlength="120" required class="myInput" name="name">
                </div>
                <div class="label-on-top">
                    <label for="entityType">Tipo de entidad</label>
                    <select name="entityType" id="entityType" disabled class="myInput">
                        <option value="0" {{ ($entity->entity_type == 0) ? 'selected' : '' }}>Usuario</option>
                        <option value="1" {{ ($entity->entity_type == 1) ? 'selected' : '' }}>Conductor</option>
                    </select>
                </div>
                @if ($entity->entity_type == 1)
                <div class="label-on-top">
                    <label for="routes">Rutas</label>
                    <select name="routeId" id="routeId" class="myInput">
                        @foreach ($routes as $route)
                            <option value="{{ $route->id }}"
                            {{ $entity->route->id == $route->id ? 'selected' : '' }}>
                                {{ $route->origin }} / {{ $route->destination }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
            <input type="hidden" name="lat" value="{{ $entity->lat }}" id="lat">
            <input type="hidden" name="lng" value="{{ $entity->lng }}" id="lng">
            <input type="hidden" name="_method" value="put">
            {{ csrf_field() }}
            <div class="form-row" style="margin-top: 10px;">
                <button class="btn myButton" type="submit">Guardar</button>
            </div>
        </form>
    </div>

    @if ($entity->entity_type == 0)
        <div id="googleMap" style="width: 100%; height: 320px;"></div>
    @endif

@endsection
@section("script")
    @if ($entity->entity_type == 0)
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASUGVsb9Pi-w167QDPMyiWbHhGxFhj_xU&callback=mapInstance"
                async defer></script>

        <script>
            var map;
            var latLng = {lat: {{ $entity->lat }}, lng: {{ $entity->lng }}};


            function mapInstance() {
                map = new google.maps.Map(document.getElementById('googleMap'), {
                    center: latLng,
                    zoom: 16,
                    disableDoubleClickZoom: true
                });


                var mapUses = new MapUses();
                var icon = "{{ URL::asset("images/markers/driver.png") }}";
                var marker = mapUses.addMarker(map, latLng, icon, "{{ $entity->name }}", true);

                marker.addListener("dragend", function(e) {
                    $("#lat").val(e.latLng.lat());
                    $("#lng").val(e.latLng.lng());
                });

                map.addListener("dblclick", function(e) {
                    marker.setPosition({lat: e.latLng.lat(), lng: e.latLng.lng()});
                    $("#lat").val(e.latLng.lat());
                    $("#lng").val(e.latLng.lng());
                })
            }

        </script>

    @endif
@endsection

