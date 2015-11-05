import { render, tree } from 'deku';
import element from 'magic-virtual-element'; // eslint-disable-line no-unused-vars
import Map from './components/Map';
import Station from './components/Map/Station';

const insertMap = document.querySelector('[data-insert="map"]');

if (insertMap) {
  fetch('/stations.json')
    .then(res => res.json())
    .then(stations => (
      <Map
        center={[ 0, 0 ]}
        zoom="1"
        height="100%"
        width="100%"
      >
        {stations.map(station => <Station {...station} />)}
      </Map>
    ))
    .then(tree)
    .then(component => render(component, insertMap));
}
