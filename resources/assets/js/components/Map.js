import leaflet from 'leaflet';
import element from 'magic-virtual-element';

const defaultProps = {
  width: 800,
  height: 480,
  center: [ 0, 0 ],
  zoom: 2
};

function render({ props, state }) {
  return <div class="map" style={{ width: props.width, height: props.height }} />;
}

function afterMount({ props, state }, el, setState) {
  const map = leaflet.map(el, props);
  const tiles = leaflet.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data ⓒ <a href="http://osm.org/copyright" target="_blank">OpenStreetMap</a> contributors'
  });
  tiles.addTo(map);
  setState({ map });
}

export default { defaultProps, render, afterMount };
