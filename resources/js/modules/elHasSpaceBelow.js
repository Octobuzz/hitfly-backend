export default (domEl, spacePx) => {
  const bodyHeight = document.body.offsetHeight;
  const domElBottom = domEl.getBoundingClientRect().bottom;

  return bodyHeight - domElBottom >= spacePx;
};
