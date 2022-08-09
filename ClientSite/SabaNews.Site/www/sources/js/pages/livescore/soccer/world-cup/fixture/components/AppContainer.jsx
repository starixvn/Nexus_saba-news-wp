import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Grid from './Grid';
export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        this.props.getMatches();
    }

    render() {
        const { matches, league } = this.props;
        return (
            <div className="wc-bg-overflow">
                { matches !== null && <Grid matches={matches} league={league} /> }
                <div className="wc-date-control wc-date-control-next"><i className="icon-chevron-right"></i></div>
                <div className="wc-date-control wc-date-control-prev"><i className="icon-chevron-left"></i></div>
            </div>
        );
    }

}


const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, league } = state;
    return { matches, league };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);