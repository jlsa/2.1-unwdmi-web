import 'es6-promise';

import { render, tree } from 'deku';
import element from 'magic-virtual-element'; // eslint-disable-line no-unused-vars
import Chart from './components/Chart';
import RefreshingMap from './components/RefreshingMap';
import Station from './components/Map/Station';

const insertMap = document.querySelector('[data-insert="map"]');

if (insertMap) {
  const center = [ insertMap.dataset.lat || 0, insertMap.dataset.lon || 0 ];
  const heatMap = insertMap.dataset.heatMap;
  const heatMapUrl = heatMap ? `/measurements/heatmap.json?property=${heatMap}` : false;
  fetch('/stations.json', { credentials: 'same-origin' })
    .then(res => res.json())
    .then(stations => (
      <RefreshingMap
        center={center}
        zoom={insertMap.dataset.zoom || 1}
        height="100%"
        width="100%"
        heatMapUrl={heatMapUrl}
      >
        {stations.map(station => <Station {...station} />)}
      </RefreshingMap>
    ))
    .then(tree)
    .then(component => render(component, insertMap));
}
