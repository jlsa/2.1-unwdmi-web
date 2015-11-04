import assign from 'object-assign';
import element from 'magic-virtual-element'; // eslint-disable-line no-unused-vars
import titleCase from 'title-case';

function render({ props, state }) {
  const { station } = state;
  if (!station) {
    return <div class="map-marker-station is-loading" data-station-id={props.id} />;
  }
  const { name, country, latitude, longitude, elevation } = station;
  return (
    <div class="map-marker-station" data-station-id={props.id}>
      <strong>{titleCase(name)}, {titleCase(country)}</strong>
      <p>
        Location: {latitude}°N {longitude}°E <br />
        Elevation: {elevation}m
      </p>
    </div>
  );
}

function afterMount({ props }, el, setState) {
  fetch(`/stations/${props.id}`)
    .then(res => res.json())
    .then(data => ({ station: data }))
    .then(setState);
}

function afterRender({ props }) {
  props.popup.update();
}

export default { render, afterMount, afterRender };
