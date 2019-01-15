const STATUS = require('./STATUS');
const Logger = require('./Logger');

const ErrorHandler = err => {

  if(err.isErrorLogged) return err;

  // error object can be overwritten in switch statements
  const errorForClient = {
    success: false,
    message: "Server error",
    status: STATUS.INTERNAL_ERROR,
    isErrorLogged: true
  };

  if(!err.status){
    Logger.createLog(err);
    return errorForClient;
  }

  if(err.status === STATUS.INVALID_INPUT_PARAMETERS || err.status === STATUS.NOT_FOUND){
    errorForClient.status = err.status;
    errorForClient.message = err.message;
    return errorForClient;
  }

  Logger.createLog(err);
  return errorForClient;
};

module.exports = ErrorHandler;