import 'es6-promise';

import { render, tree } from 'deku';
import element from 'magic-virtual-element'; // eslint-disable-line no-unused-vars
import max from 'max-component';
import RefreshingMap from './components/RefreshingMap';
import Station from './components/Map/Station';

const insertMap = document.querySelector('[data-insert="map"]');
const insertChart = document.querySelector('[data-insert="chart"]');

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
        selected={insertMap.dataset.stationId || null}
        heatMapUrl={heatMapUrl}
      >
        {stations.map(station => <Station {...station} />)}
      </RefreshingMap>
    ))
    .then(tree)
    .then(component => render(component, insertMap));
}

if (insertChart) {
  const SNAP_SIZE = 2.5;
  const VALUE_MARGIN = 1.5;
  const stationId = insertChart.dataset.stationId;
  const update = () => {
    return fetch(`/measurements/graph.json?property=precipitation&station=${stationId}&interval=60`, { credentials: 'same-origin' })
      .then(res => res.json())
      .then(measurements => {
        Flotr.draw(insertChart, [ measurements ], {
          ...insertChart.dataset,
          yaxis: {
            min: 0,
            max: Math.max(
              Math.ceil((max(measurements.map(p => p[1])) * VALUE_MARGIN) / SNAP_SIZE) * SNAP_SIZE,
              1
            )
          },
          xaxis: {
            tickFormatter: time => new Date(time * 1000).toLocaleTimeString()
          }
        });
      })
      .then(() => {
        setTimeout(update, 3000);
      });
  };
  update();
}
