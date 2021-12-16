import React from "react";
import { Redirect } from "react-router-dom";

const RedirectPage = props => {
  return <Redirect to={`/${props.match.params.url}`} 
    />;
};

export default RedirectPage;