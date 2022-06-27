import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    };

    render() {
        return <div>Favourite league</div>
    }
}


const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches } = state;
    return { matches };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);