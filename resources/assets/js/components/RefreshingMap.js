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
    heatMap: false
  };
}

function render({ props, state }) {
  const { heatMap } = state;
  return (
    <Map heatMap={heatMap} {...props} />
  );
}

function afterMount({ props }, el, setState) {
  const { heatMapUrl, ...attrs } = props;
  const nxt = () => {
    if (heatMapUrl) {
      update(heatMapUrl).then(setState).then(() => {
        setTimeout(nxt, INTERVAL);
      });
    }
  };
  nxt();
}

export default { initialState, render, afterMount };
