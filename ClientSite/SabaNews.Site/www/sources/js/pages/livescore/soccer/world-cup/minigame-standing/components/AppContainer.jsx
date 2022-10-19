import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { Width1024, WorldCup } from '@jsroot/common/constants';
import Site from '@jsroot/common/site';
import LangHelper from '@jsroot/common/langHelper';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }

    render() {        
        return (
            <div className="test">
                <p>demo minigame</p>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, players} = state;
    return { matches, players};
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);