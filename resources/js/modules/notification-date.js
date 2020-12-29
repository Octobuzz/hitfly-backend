export default dateString => (
  // format: 2019-09-20T16:17:10+0000, should be 2019-09-20T16:17:10+00:00
  // so that safari can read this
  `${dateString.slice(0, -2)}:${dateString.slice(-2)}`
);
