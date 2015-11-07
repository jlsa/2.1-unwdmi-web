import { render, tree } from 'deku';
import element from 'magic-virtual-element'; // eslint-disable-line no-unused-vars
import Map from './components/Map';
import Station from './components/Map/Station';

const insertMap = document.querySelector('[data-insert="map"]');

if (insertMap) {
  const center = [ insertMap.dataset.lat || 0, insertMap.dataset.lon || 0 ];
  fetch('/stations.json', { credentials: 'same-origin' })
    .then(res => res.json())
    .then(stations => (
      <Map
        center={center}
        zoom={insertMap.dataset.zoom || 1}
        height="100%"
        width="100%"
      >
        {stations.map(station => <Station {...station} />)}
      </Map>
    ))
    .then(tree)
    .then(component => render(component, insertMap));
}
