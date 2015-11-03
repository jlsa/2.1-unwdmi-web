import assign from 'object-assign';
import element from 'magic-virtual-element'; // eslint-disable-line no-unused-vars
import leaflet from 'leaflet';
import titleCase from 'title-case';

const defaultProps = {
  width: 800,
  height: 480,
  center: [ 0, 0 ],
  zoom: 2
};

const stationIcon = leaflet.divIcon({
  iconSize: leaflet.point(5, 5),
  className: 'map-station'
});

function render({ props }) {
  return <div class="map" style={{ width: props.width, height: props.height }} />;
}

function afterMount({ props, state }, el, setState) {
  const tiles = leaflet.tileLayer('https://maps.wikimedia.org/osm/{z}/{x}/{y}.png', {
    attribution: 'Map data ⓒ <a href="http://osm.org/copyright" target="_blank">OpenStreetMap</a> contributors'
  });
  const markers = leaflet.layerGroup(
    props.children.map(({ attributes }) => {
      const { latitude, longitude, name, country } = attributes;
      return leaflet.marker([ latitude, longitude ], { icon: stationIcon })
        .bindPopup(`${titleCase(name)}, ${titleCase(country)}`);
    })
  );
  const map = leaflet.map(el, assign({}, props, { layers: [ tiles, markers ] }));
  tiles.addTo(map);
  setState({ map });
}

export default { defaultProps, render, afterMount };
