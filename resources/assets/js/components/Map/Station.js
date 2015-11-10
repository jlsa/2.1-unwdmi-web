import 'whatwg-fetch';
import element from 'magic-virtual-element'; // eslint-disable-line no-unused-vars
import titleCase from 'title-case';

function render({ props, state }) {
  const { station, measurement } = state;
  if (!station) {
    return <div class="map-marker-station is-loading" data-station-id={props.id} />;
  }
  const { name, country, latitude, longitude, elevation } = station;
  const measurementInfo = measurement && (
    <p>
      Temperature: {measurement.temperature}°C <br />
      Dew Point: {measurement.dew_point}°C <br />
      Station Air Pressure: {measurement.station_pressure}mbar <br />
      Sea Level Pressure: {measurement.sea_level_pressure}mbar <br />
      Visibility: {measurement.visibility}km <br />
      Precipitation: {measurement.precipitation}cm <br />
      Snow Depth: {measurement.snow_depth}cm <br />
      Cloud Cover: {measurement.cloud_cover}% <br />
      Wind Direction: {measurement.wind_direction}° <br />
      Wind Speed: {measurement.wind_speed}km/h
    </p>
  );

  return (
    <div class="map-marker-station" data-station-id={props.id}>
      <strong>{titleCase(name)}, {titleCase(country)}</strong>
      <p>
        Location: {latitude}°N {longitude}°E <br />
        Elevation: {elevation}m
      </p>
      <p>
        <a href={`/stations/${props.id}`}>Show »</a>
      </p>
      {measurementInfo}
    </div>
  );
}

function afterMount({ props }, el, setState) {
  fetch(`/stations/${props.id}.json`, { credentials: 'same-origin' })
    .then(res => res.json())
    .then(setState);
}

function afterRender({ props }) {
  props.popup.update();
}

export default { render, afterMount, afterRender };
