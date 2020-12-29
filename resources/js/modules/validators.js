// reviewFilterType property should work along with reviewFilterId property,
// they are used to select particular options in gql query 'filters' argument:

// - for user-track-list type you should specify user id or string "me"
// - for commented-by-user-track-list type you should specify user id or string "me"
// - for music-group-track-list type you should specify music group id
// - for track type you should specify track id

export const reviewFilterType = val => [
  'user-track-list',
  'commented-by-user-track-list',
  'music-group-track-list',
  'track',
  'tracks'
].includes(val);

export const reviewFilterId = val => (
  val === 'me' || val === 'main' || typeof val === 'number'
);

export const commentPeriod = val => (
  ['week', 'month', 'year'].includes(val)
);
