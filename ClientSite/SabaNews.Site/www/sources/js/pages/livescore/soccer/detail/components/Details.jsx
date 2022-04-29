import React, { PureComponent } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import MatchSummary from './MatchSummary';
import Lineups from './Lineups';
import Statistics from './Statistics';
import HeadToHead from './HeadToHead';
import queryString from 'query-string';

class Details extends PureComponent {

    constructor(props) {
        super(props);
    }

    componentDidMount() {
        let defaultTab = "";
        let params = queryString.parse(window.location.search);
        if (params["tab"]) {
            defaultTab = params["tab"].toLowerCase();
        }
        var section = document.getElementById(defaultTab);
        if (!section) return null;
        window.scroll({
            top: section.offsetTop,
            left: 0,
            behavior: 'smooth'
        });
    }

    render() {
        return (
            <div className="details">
                <MatchSummary />
                <Statistics />
                <Lineups />
                <HeadToHead />
            </div>
        );
    }
}


const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match } = state;
    return { match };
};

export default connect(mapStateToProps, mapDispatchToProps)(Details);
