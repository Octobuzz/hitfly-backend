module.exports = (error, context) => {
  console.log(error);

  context.error({ statusCode: 500, message: 'Server error' });
};
