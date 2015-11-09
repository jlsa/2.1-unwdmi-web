import assign from 'object-assign';
import element from 'magic-virtual-element'; // eslint-disable-line no-unused-vars
import leaflet from 'leaflet';
import 'leaflet.heat';
import 'leaflet.markercluster';
import max from 'max-component';
import { render as renderTree, tree } from 'deku';
import titleCase from 'title-case';
import values from 'object-values';
import StationPopup from './Map/Station';

const defaultProps = {
  width: 800,
  height: 480,
  center: [ 0, 0 ],
  zoom: 2,
  heatMap: false
};

function render({ props }) {
  return <div class="map" style={{ width: props.width, height: props.height }} />;
}

function afterMount({ props, state }, el, setState) {
  const tiles = leaflet.tileLayer('http://{s}.tile.openstreetmap.se/hydda/base/{z}/{x}/{y}.png', {
    attribution: 'Tiles courtesy of <a href="http://openstreetmap.se/" target="_blank">OpenStreetMap Sweden</a> — Map data ⓒ <a href="http://osm.org/copyright" target="_blank">OpenStreetMap</a> contributors'
  });

  const layers = [];
  layers.push(tiles);

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
  layers.push(clusters);

  const heat = leaflet.heatLayer([], {
    radius: 15,
    max: 1,
    gradient: {
        0.1: 'blue',
        0.3: 'cyan',
        0.5: 'lime',
        0.7: 'yellow',
        0.9: 'red'
    },
    minOpacity: 0.1
  });
  layers.push(heat);

  const map = leaflet.map(el, assign({}, props, { layers }));
  setState({ map, heatLayer: heat });
}

function afterRender({ props, state }) {
  const { heatMap, children } = props;
  const { heatLayer } = state;
  if (heatMap && heatLayer) {
    const maxPoint = max(values(heatMap));
    const heatPoints = children.map(({ attributes }) =>
      [ attributes.latitude, attributes.longitude, heatMap[attributes.id] / maxPoint ]
    );
    heatLayer.setLatLngs(heatPoints);
  }
}

export default { defaultProps, render, afterMount, afterRender };
