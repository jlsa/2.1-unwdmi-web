import element from 'magic-virtual-element'; // eslint-disable-line no-unused-vars
import Map from './Map';

const INTERVAL = 5000;

function update(heatMapUrl) {
  return fetch(heatMapUrl, { credentials: 'same-origin' })
    .then(res => res.json())
    .then(heatMap => ({ heatMap }));
}

function initialState() {
  return {
    heatMap: {}
  };
}

function render({ props, state }) {
  const { heatMap } = state;
  return (
    <Map {...props} heatMap={heatMap} />
  );
}

function afterMount({ props }, el, setState) {
  const { heatMapUrl, ...attrs } = props;
  const nxt = () => {
    update(heatMapUrl).then(setState).then(() => {
      setTimeout(nxt, INTERVAL);
    });
  };
  nxt();
}

export default { initialState, render, afterMount };
