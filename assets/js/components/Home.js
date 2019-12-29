import React, { Component } from "react";
import { Route, Switch, Redirect, Link, withRouter } from "react-router-dom";

//import Posts from './Posts';

class Home extends Component {
  render() {
    return (
      <div className="container">
        <p className="green">React Component</p>
      </div>
    );
  }
}

export default Home;
