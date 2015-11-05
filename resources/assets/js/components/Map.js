import assign from 'object-assign';
import element from 'magic-virtual-element'; // eslint-disable-line no-unused-vars
import leaflet from 'leaflet';
import 'leaflet.markercluster';
import { render as renderTree, tree } from 'deku';
import titleCase from 'title-case';
import StationPopup from './Map/Station';

const defaultProps = {
  width: 800,
  height: 480,
  center: [ 0, 0 ],
  zoom: 2
};

function render({ props }) {
  return <div class="map" style={{ width: props.width, height: props.height }} />;
}

function afterMount({ props, state }, el, setState) {
  const tiles = leaflet.tileLayer('https://maps.wikimedia.org/osm/{z}/{x}/{y}.png', {
    attribution: 'Map data ⓒ <a href="http://osm.org/copyright" target="_blank">OpenStreetMap</a> contributors'
  });
  const markers = props.children.map(({ attributes }) => {
    const { id, name, latitude, longitude } = attributes;
    const el = document.createElement('div');
    return leaflet.marker(
      [ latitude, longitude ],
      { icon: leaflet.divIcon({
        iconSize: leaflet.point(17, 17),
        className: 'map-marker map-marker--station map-marker--small',
        html: titleCase(name)
      }) }
    )
      .bindPopup(el)
      .once('popupopen', e => {
        // poopy! ):
        renderTree(tree(<StationPopup id={id} popup={e.popup} />), el);
      });
  });
  const sizes = {
    small: leaflet.point(18, 18),
    medium: leaflet.point(23, 23),
    large: leaflet.point(28, 28)
  };
  const clusters = leaflet.markerClusterGroup({
    maxClusterRadius: 40,
    iconCreateFunction(cluster) {
      const count = cluster.getChildCount();
      const size = count < 10 ? 'small' : count < 30 ? 'medium' : 'large';
      return leaflet.divIcon({
        iconSize: sizes[size],
        html: count,
        className: `map-marker map-marker--cluster map-marker--${size}`
      });
    }
  });
  clusters.addLayers(markers);
  const map = leaflet.map(el, assign({}, props, { layers: [ tiles, clusters ] }));
  setState({ map });
}

export default { defaultProps, render, afterMount };
